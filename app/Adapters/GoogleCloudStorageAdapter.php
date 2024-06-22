<?php

namespace App\Adapters;

use Google\Cloud\Storage\Bucket;
use League\Flysystem\Config;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\PathPrefixer;
use League\Flysystem\Visibility;
use League\Flysystem\FileAttributes;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToSetVisibility;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToMoveFile;

class GoogleCloudStorageAdapter implements FilesystemAdapter
{
    private Bucket $bucket;
    private PathPrefixer $prefixer;

    public function __construct(Bucket $bucket, string $prefix = '')
    {
        $this->bucket = $bucket;
        $this->prefixer = new PathPrefixer($prefix);
    }

    public function fileExists(string $path): bool
    {
        try {
            return $this->bucket->object($this->prefixer->prefixPath($path))->exists();
        } catch (\Exception $e) {
            throw UnableToCheckExistence::forLocation($path, $e);
        }
    }

    public function write(string $path, string $contents, Config $config): void
    {
        try {
            $this->bucket->upload($contents, [
                'name' => $this->prefixer->prefixPath($path),
                'predefinedAcl' => $this->getPredefinedAcl($config->get('visibility', Visibility::PUBLIC))
            ]);
        } catch (\Exception $e) {
            throw UnableToWriteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        try {
            $this->bucket->upload($contents, [
                'name' => $this->prefixer->prefixPath($path),
                'predefinedAcl' => $this->getPredefinedAcl($config->get('visibility', Visibility::PUBLIC)),
                'resumable' => true
            ]);
        } catch (\Exception $e) {
            throw UnableToWriteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function read(string $path): string
    {
        try {
            $object = $this->bucket->object($this->prefixer->prefixPath($path));
            return $object->downloadAsStream()->getContents();
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function readStream(string $path)
    {
        try {
            $object = $this->bucket->object($this->prefixer->prefixPath($path));
            return $object->downloadAsStream();
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function delete(string $path): void
    {
        try {
            $object = $this->bucket->object($this->prefixer->prefixPath($path));
            $object->delete();
        } catch (\Exception $e) {
            throw UnableToDeleteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function deleteDirectory(string $path): void
    {
        try {
            $objects = $this->bucket->objects(['prefix' => $this->prefixer->prefixPath($path)]);
            foreach ($objects as $object) {
                $object->delete();
            }
        } catch (\Exception $e) {
            throw UnableToDeleteDirectory::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function createDirectory(string $path, Config $config): void
    {
        try {
            // GCS doesn't have directories, but this method needs to be implemented
            // A zero-byte object with a trailing slash can be used to simulate a directory
            $this->bucket->upload('', [
                'name' => rtrim($this->prefixer->prefixPath($path), '/') . '/',
                'predefinedAcl' => $this->getPredefinedAcl($config->get('visibility', Visibility::PUBLIC))
            ]);
        } catch (\Exception $e) {
            throw UnableToCreateDirectory::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function setVisibility(string $path, string $visibility): void
    {
        try {
            $object = $this->bucket->object($this->prefixer->prefixPath($path));
            $object->update(['acl' => []], ['predefinedAcl' => $this->getPredefinedAcl($visibility)]);
        } catch (\Exception $e) {
            throw UnableToSetVisibility::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function visibility(string $path): FileAttributes
    {
        try {
            $object = $this->bucket->object($this->prefixer->prefixPath($path));
            $acl = $object->acl()->get();
            $visibility = $this->getVisibilityFromAcl($acl);
            return new FileAttributes($path, null, $visibility);
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function mimeType(string $path): FileAttributes
    {
        try {
            $object = $this->bucket->object($this->prefixer->prefixPath($path));
            $info = $object->info();
            return new FileAttributes($path, null, null, $info['updated'], $info['contentType']);
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function lastModified(string $path): FileAttributes
    {
        try {
            $object = $this->bucket->object($this->prefixer->prefixPath($path));
            $info = $object->info();
            return new FileAttributes($path, null, null, strtotime($info['updated']));
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function fileSize(string $path): FileAttributes
    {
        try {
            $object = $this->bucket->object($this->prefixer->prefixPath($path));
            $info = $object->info();
            return new FileAttributes($path, $info['size']);
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function listContents(string $path, bool $deep): iterable
    {
        try {
            $options = ['prefix' => $this->prefixer->prefixPath($path)];
            if (!$deep) {
                $options['delimiter'] = '/';
            }
            $objects = $this->bucket->objects($options);
            foreach ($objects as $object) {
                $info = $object->info();
                $name = $this->prefixer->stripPrefix($info['name']);
                yield $object->isDir() ? new DirectoryAttributes($name) : new FileAttributes($name);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage(), 0, $e);
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        try {
            $sourceObject = $this->bucket->object($this->prefixer->prefixPath($source));
            $destinationObject = $sourceObject->copy($this->bucket, ['name' => $this->prefixer->prefixPath($destination)]);
            $sourceObject->delete();
        } catch (\Exception $e) {
            throw UnableToMoveFile::fromLocationTo($source, $destination, $e);
        }
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        try {
            $sourceObject = $this->bucket->object($this->prefixer->prefixPath($source));
            $sourceObject->copy($this->bucket, ['name' => $this->prefixer->prefixPath($destination)]);
        } catch (\Exception $e) {
            throw UnableToCopyFile::fromLocationTo($source, $destination, $e);
        }
    }

    private function getPredefinedAcl(string $visibility): string
    {
        return $visibility === Visibility::PUBLIC ? 'publicRead' : 'private';
    }

    private function getVisibilityFromAcl(array $acl): string
    {
        foreach ($acl as $entry) {
            if ($entry['entity'] === 'allUsers' && $entry['role'] === 'READER') {
                return Visibility::PUBLIC;
            }
        }
        return Visibility::PRIVATE;
    }

    public function directoryExists(string $path): bool
    {
        $objects = $this->bucket->objects(['prefix' => $this->prefixer->prefixPath($path)]);
        foreach ($objects as $object) {
            return true;
        }
        return false;
    }
}

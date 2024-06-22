<?php

namespace App\GraphQL\Mutations;

use App\Models\Categories;
use App\Models\Customer;
use App\Models\Products;
use App\Models\Orders;
use App\Models\OrderDetails;

class GeneralMutator
{
    public function deleteCategory($root, array $args)
    {
        $data = Categories::find($args['id']);
        if (!$data) {
            throw new \Exception('Data not found');
        }

        $data->delete();

        return $data;
    }

    public function deleteCustomer($root, array $args)
    {
        $data = Customer::find($args['id']);
        if (!$data) {
            throw new \Exception('Data not found');
        }

        $data->delete();

        return $data;
    }

    public function deleteProduct($root, array $args)
    {
        $data = Products::find($args['id']);
        if (!$data) {
            throw new \Exception('Data not found');
        }

        $data->delete();

        return $data;
    }

    public function deleteOrder($root, array $args)
    {
        $data = Orders::find($args['id']);
        if (!$data) {
            throw new \Exception('Data not found');
        }

        $data->delete();

        return $data;
    }

    public function deleteOrderDetail($root, array $args)
    {
        $data = OrderDetails::find($args['id']);
        if (!$data) {
            throw new \Exception('Data not found');
        }

        $data->delete();

        return $data;
    }
}

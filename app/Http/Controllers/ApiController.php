<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Categories;
use App\Models\Orders;
use App\Models\OrderDetails;
use App\Models\Customer;
use App\Models\Admin;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    // Dashboard
    public function dashboard(Request $request)
    {
        $categories = Categories::get();
        $products = Products::get();
        $customers = Customer::get();
        $orders = Orders::get();
        $result = [
            "total_categories" => count($categories),
            "total_products" => count($products),
            "total_customer" => count($customers),
            "total_orders" => count($orders),
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }

    // Categories
    public function categories(Request $request)
    {
        $result_arr = Categories::get();
        if ($request->id) {
            $result_arr = Categories::where('id', $request->id)->get();
        }
        foreach ($result_arr as $k => $result_db) {
            $result_db->timestamp_str = date('d F Y', $result_db->timestamp_str);
            $result_arr[$k] = $result_db;
        }
        if ($request->id) {
            $result_arr = $result_arr[0];
        }

        $result = $result_arr;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function save_categories(Request $request)
    {
        $id = (int) $request->query('id');
        if ($id) {
            $data = Categories::where('id', $id)->first();
        } else {
            $data = new Categories();
        }

        $data->title = $request->title;
        $data->description = $request->description;
        $data->timestamp = time();
        if (!$id) {
            $data->status = 1;
        }

        $data->save();

        $result = [
            "status" => 200,
            "message" => "Data berhasil disimpan",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function delete_categories(Request $request)
    {
        $res = Categories::where('id', $request->id)->first();
        if ($res) {
            $res->delete();
        }

        $result = [
            "status" => 200,
            "message" => "Data berhasil dihapus",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }

    // Customer
    public function customers(Request $request)
    {
        $result_arr = Customer::get();
        if ($request->id) {
            $result_arr = Customer::where('id', $request->id)->get();
        }
        foreach ($result_arr as $k => $result_db) {
            $result_db->timestamp_str = date('d F Y', $result_db->timestamp_str);
            $result_arr[$k] = $result_db;
        }
        if ($request->id) {
            $result_arr = $result_arr[0];
        }

        $result = $result_arr;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function save_customers(Request $request)
    {
        $id = (int) $request->query('id');
        if ($id) {
            $data = Customer::where('id', $id)->first();
        } else {
            $data = new Customer();
        }

        $data->nama_lengkap = $request->nama_lengkap;
        $data->email = $request->email;
        $data->nomor_telpon = $request->nomor_telpon;
        $data->alamat = $request->alamat;
        $data->nama_perusahaan = $request->nama_perusahaan;
        $data->timestamp = time();
        if (!$id) {
            $data->status = 1;
        }

        $data->save();

        $result = [
            "status" => 200,
            "message" => "Data berhasil disimpan",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function delete_customers(Request $request)
    {
        $res = Customer::where('id', $request->id)->first();
        if ($res) {
            $res->delete();
        }

        $result = [
            "status" => 200,
            "message" => "Data berhasil dihapus",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }

    // Products
    public function products(Request $request)
    {
        $result_arr = Products::with('category')->get();
        if ($request->id) {
            $result_arr = Products::with('category')->where('id', $request->id)->get();
        }
        foreach ($result_arr as $k => $result_db) {
            $result_db->timestamp_str = date('d F Y', $result_db->timestamp_str);
            $result_db->thumbnail = url('/images/products/' . ($result_db->thumbnail ? $result_db->thumbnail : 'default.png'));
            $result_db->thumbnailUrl = $result_db->thumbnail;
            $result_arr[$k] = $result_db;
        }
        if ($request->id) {
            $result_arr = $result_arr[0];
        }

        $result = $result_arr;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function save_products(Request $request)
    {
        $id = (int) $request->query('id');
        if ($id) {
            $data = Products::where('id', $id)->first();
        } else {
            $data = new Products();
        }

        $data->name = $request->name;
        $data->description = $request->description;

        $id_category = 0;
        try {
            $category = Categories::where('title', $request->category)->first();
            $id_category = $category->id;
        } catch (\Throwable $th) {
        }
        $data->id_category = $id_category;

        try {
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $fileName = time() . '-' . $file->getClientOriginalName();

                $file->move(public_path('images/products'), $fileName);

                $data->thumbnail = $fileName;
            }
        } catch (\Throwable $th) {
        }

        $data->stock = $request->stock;
        $data->buy_price = $request->buy_price;
        $data->sell_price = $request->sell_price;
        $data->timestamp = time();
        if (!$id) {
            $data->status = 1;
        }

        $data->save();

        $result = [
            "status" => 200,
            "message" => "Data berhasil disimpan",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function delete_products(Request $request)
    {
        $res = Products::where('id', $request->id)->first();
        if ($res) {
            $res->delete();
        }

        $result = [
            "status" => 200,
            "message" => "Data berhasil dihapus",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }

    // Orders
    public function orders(Request $request)
    {
        $result_arr = Orders::with('details')->get();
        if ($request->id) {
            $result_arr = Orders::with('details')->where('id', $request->id)->get();
        }
        foreach ($result_arr as $k => $result_db) {
            $result_db->timestamp_str = date('d F Y', $result_db->timestamp_str);
            $result_arr[$k] = $result_db;
        }
        if ($request->id) {
            $result_arr = $result_arr[0];
        }

        $result = $result_arr;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function save_orders(Request $request)
    {
        $id = (int) $request->query('id');
        if ($id) {
            $data = Orders::where('id', $id)->first();
        } else {
            $data = new Orders();
        }

        $data->no_invoice = $request->no_invoice;
        $data->nama_lengkap = $request->nama_lengkap;
        $data->email = $request->email;
        $data->alamat = $request->alamat;
        $data->nomor_telpon = $request->nomor_telpon;
        $data->subtotal = $request->subtotal;
        $data->shipping_cost = $request->shipping_cost;
        $data->additional_cost = $request->additional_cost;
        $data->discount = $request->discount;
        $data->total = $request->total;

        $id_customer = 0;
        try {
            $customer = Customer::where('email', $request->email)->first();
            $id_customer = $customer->id;
        } catch (\Throwable $th) {
        }
        $data->id_customer = $id_customer;

        $data->status = $request->status;
        $data->notes = $request->notes;
        $data->timestamp = time();

        $data->save();

        if ($id <= 0) {
            $order_info = Orders::where('no_invoice', $request->no_invoice)->first();
        } else {
            $order_info = $data;
        }

        if ($id > 0) {
            $res_details = OrderDetails::where('id_order', $id)->first();
            if ($res_details) {
                $res_details->delete();
            }
        }

        foreach ($request->details as $p) {
            $item = new OrderDetails();

            $item->id_order = $order_info->id;
            $item->id_product = $p['id_product'];
            $item->name = $p['name'];
            $item->quantity = $p['quantity'];
            $item->price = $p['price'];

            $item->save();
        }

        $result = [
            "status" => 200,
            "message" => "Data berhasil disimpan",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function delete_orders(Request $request)
    {
        $res = Orders::where('id', $request->id)->first();
        if ($res) {
            $res->delete();
        }

        $res_details = OrderDetails::where('id_order', $request->id)->first();
        if ($res_details) {
            $res_details->delete();
        }

        $result = [
            "status" => 200,
            "message" => "Data berhasil dihapus",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }

    // Admin
    public function admin_list(Request $request)
    {
        $result_arr = Admin::get();
        if ($request->id) {
            $result_arr = Admin::where('id', $request->id)->get();
        }
        foreach ($result_arr as $k => $result_db) {
            $result_db->timestamp_str = date('d F Y', $result_db->timestamp_str);
            $result_db->avatar = '/images/avatar-1.png';

            $result_arr[$k] = $result_db;
        }
        if ($request->id) {
            $result_arr = $result_arr[0];
        }

        $result = $result_arr;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function save_admin(Request $request)
    {
        $id = (int) $request->query('id');
        if ($id) {
            $education_level = Admin::where('id', $id)->first();
        } else {
            $education_level = new Admin();
        }

        $education_level->nama_lengkap = $request->nama_lengkap;
        $education_level->email = $request->email;
        $education_level->nik = $request->nik;
        $education_level->tanggal_lahir = $request->tanggal_lahir;
        $education_level->password = $request->password;
        $education_level->timestamp = time();
        if (!$id) {
            $education_level->role = 'admin';
            $education_level->status = 1;
        }

        $education_level->save();

        $result = [
            "status" => 200,
            "message" => "Data berhasil disimpan",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
    public function delete_admin(Request $request)
    {
        $res = Admin::where('id', $request->id)->first();
        if ($res) {
            $res->delete();
        }

        $result = [
            "status" => 200,
            "message" => "Data berhasil dihapus",
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }

    // Auth
    public function login(Request $request)
    {
        $result_db = Candidate::where('email', $request->email)->where('password', $request->password)->first();
        if (!empty($result_db)) {
            $result_db->timestamp_str = date('Y-m-d H:i:s', $result_db->timestamp);
            $result = [
                "status" => 200,
                "message" => "Login Berhasil",
                "data" => $result_db
            ];

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
            exit;
        } else {
            $result = [
                "status" => 400,
                "message" => "Email / Password Anda salah",
                "data" => []
            ];
            http_response_code(400);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
            exit;
        }
    }
    public function register(Request $request)
    {
        $candidate = new Candidate();

        $candidate->nama_lengkap = $request->nama;
        $candidate->nomor_telpon = $request->nomorTelpon;
        $candidate->email = $request->email;
        $candidate->password = $request->password;
        $candidate->role = "user";
        $candidate->avatar = "avatar-default.png";
        $candidate->timestamp = time();

        $candidate->save();

        $result = [
            "status" => 200,
            "message" => "Akun berhasil terdaftar silahkan login"
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }

    // Change Status
    public function update_status(Request $request)
    {
        $id = (int) $request->id;
        $type = $request->type;
        if ($id) {
            if ($type == 'categories') {
                $data = Categories::where('id', $id)->first();
            } elseif ($type == 'customer') {
                $data = Customer::where('id', $id)->first();
            } elseif ($type == 'products') {
                $data = Products::where('id', $id)->first();
            }
        }

        $data->status = $request->status;
        $data->save();

        $result = [
            "status" => 200,
            "message" => "Status berhasil terupdate"
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }
}

<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Orderdetail;
use App\Models\Product;
use App\Models\ProductStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;




class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả hóa đơn';
        $list_order = Order::join('user', 'user.id', '=', 'order.user_id')
            ->join('orderdetail', 'orderdetail.order_id', '=', 'order.id')

            ->select('order.*', 'user.name as user_name', 'user.email as user_email', 'user.phone as user_phone')

            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();

        // return dd($list_order);
        $list_status = [
            ['type' => 'secondary', 'text' => 'Đơn hàng mới'],
            ['type' => 'primary', 'text' => 'Đã xác nhận'],
            ['type' => 'info', 'text' => 'Đóng gói'],
            ['type' => 'warning', 'text' => 'Vận chuyển'],
            ['type' => 'success', 'text' => 'Đã giao'],
            ['type' => 'danger', 'text' => 'Đã hủy'],
        ];
        return view("backend.order.index", compact('list_order',  'list_status', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $title = 'Chi tiết hóa đơn';
        $order = Order::find($id);
        $list_orderdetail = $order->orderdetail;
        $user = $order->user;
        // dd($list_orderdetail);


        // $orderdetail=Orderdetail::where('')
        // $list_orderdetail=Product::where('id','=',$order->)
        // $list_orderdetail = Product::join('Orderdetail', 'product.id', '=', 'orderdetail.product_id')
        //     ->join('product_sale', 'product.id', '=', 'product_sale.product_id')
        //     ->select('*',  'product_sale.price_sale as product_price_sale')
        //     ->where('orderdetail.order_id', '=', $id)
        //     ->get();
        $total = $list_orderdetail->sum(function ($sum) {
            return $sum->amount;
        });
        if ($order == null) {
            return redirect()->route('order.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        }

        return view('backend.order.show', compact('order',  'title', 'list_orderdetail', 'total', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order == null) {
            return redirect()->route('order.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            if ($order->delete()) {
                $order->orderdetails()->delete();
            }
            return redirect()->route('order.trash')->with('message', ['type' => 'success', 'msg' => 'Hủy sản phẩm thành công']);
        }
    }
    #delete
    public function delete($id, Request $request)
    {
        $order = Order::find($id);
        if ($order == null) {
            return redirect()->route('order.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $order->status = 0;
            $order->updated_at = date('Y-m-d H:i:s');
            $order->updated_by =   Auth::guard('admin')->user()->id;
            $order->save();
            return redirect()->route('order.index')->with('message', ['type' => 'success', 'msg' => 'Chuyển vào thùng rác thành công']);
        }
    }

    // status
    public function status(Request $request, $id)
    {
        $order = Order::find($id);
        if ($order == null) {
            return redirect()->route('order.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            $type = $request->status;
            switch ($type) {

                case 'xacnhan': {
                        $order->status = 1;
                        break;
                    }
                case 'donggoi': {
                        $order->status = 2;
                        break;
                    }
                case 'vanchuyen': {
                        $order->status = 3;
                        break;
                    }
                case 'dagiao': {
                        $order->status = 4;
                        break;
                    }
                case 'huy': {
                        $order->status = 5;
                        break;
                    }
            }
            $order->updated_at = date('Y-m-d H:i:m');
            $order->updated_by = 1;
            $order->save();
            return redirect()->route('order.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
        }
    }


    public function new()
    {
        $title = 'Hóa đơn mới';

        $list_order = Order::join('user', 'user.id', '=', 'order.user_id')
            ->join('orderdetail', 'orderdetail.order_id', '=', 'order.id')
            ->select('order.*', 'user.name as user_name', 'user.email as user_email', 'user.phone as user_phone')
            ->where('order.status', '=', '0')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();

        $list_status = [
            ['type' => 'secondary', 'text' => 'Đơn hàng mới'],
            ['type' => 'primary', 'text' => 'Đã xác nhận'],
            ['type' => 'info', 'text' => 'Đóng gói'],
            ['type' => 'warning', 'text' => 'Vận chuyển'],
            ['type' => 'success', 'text' => 'Đã giao'],
            ['type' => 'danger', 'text' => 'Đã hủy'],
        ];
        return view("backend.order.new", compact('list_order', 'list_status', 'title'));
    }
    public function confirm()
    {
        $title = 'Hóa đơn đã xác nhận';


        $list_order = Order::join('user', 'user.id', '=', 'order.user_id')
            ->join('orderdetail', 'orderdetail.order_id', '=', 'order.id')
            ->select('order.*', 'user.name as user_name', 'user.email as user_email', 'user.phone as user_phone')
            ->where('order.status', '=', '1')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();
        // return dd($list_order);
        $list_status = [
            ['type' => 'secondary', 'text' => 'Đơn hàng mới'],
            ['type' => 'primary', 'text' => 'Đã xác nhận'],
            ['type' => 'info', 'text' => 'Đóng gói'],
            ['type' => 'warning', 'text' => 'Vận chuyển'],
            ['type' => 'success', 'text' => 'Đã giao'],
            ['type' => 'danger', 'text' => 'Đã hủy'],
        ];
        return view("backend.order.confirm", compact('list_order',  'list_status', 'title'));
    }
    public function package()
    {
        $title = 'Hóa đơn đã đóng gói';


        $list_order = Order::join('user', 'user.id', '=', 'order.user_id')
            ->join('orderdetail', 'orderdetail.order_id', '=', 'order.id')
            ->select('order.*', 'user.name as user_name', 'user.email as user_email', 'user.phone as user_phone')
            ->where('order.status', '=', '2')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();
        // return dd($list_order);
        $list_status = [
            ['type' => 'secondary', 'text' => 'Đơn hàng mới'],
            ['type' => 'primary', 'text' => 'Đã xác nhận'],
            ['type' => 'info', 'text' => 'Đóng gói'],
            ['type' => 'warning', 'text' => 'Vận chuyển'],
            ['type' => 'success', 'text' => 'Đã giao'],
            ['type' => 'danger', 'text' => 'Đã hủy'],
        ];
        return view("backend.order.package", compact('list_order',  'list_status', 'title'));
    }


    public function transport()
    {
        $title = 'Hóa đơn đang vận chuyển';


        $list_order = Order::join('user', 'user.id', '=', 'order.user_id')
            ->join('orderdetail', 'orderdetail.order_id', '=', 'order.id')
            ->select('order.*', 'user.name as user_name', 'user.email as user_email', 'user.phone as user_phone')
            ->where('order.status', '=', '3')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();
        // return dd($list_order);
        $list_status = [
            ['type' => 'secondary', 'text' => 'Đơn hàng mới'],
            ['type' => 'primary', 'text' => 'Đã xác nhận'],
            ['type' => 'info', 'text' => 'Đóng gói'],
            ['type' => 'warning', 'text' => 'Vận chuyển'],
            ['type' => 'success', 'text' => 'Đã giao'],
            ['type' => 'danger', 'text' => 'Đã hủy'],
        ];
        return view("backend.order.transport", compact('list_order',  'list_status', 'title'));
    }
    public function delivered()
    {
        $title = 'Hóa đơn đã giao';


        $list_order = Order::join('user', 'user.id', '=', 'order.user_id')
            ->join('orderdetail', 'orderdetail.order_id', '=', 'order.id')
            ->select('order.*', 'user.name as user_name', 'user.email as user_email', 'user.phone as user_phone')
            ->where('order.status', '=', '4')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();
        // return dd($list_order);
        $list_status = [
            ['type' => 'secondary', 'text' => 'Đơn hàng mới'],
            ['type' => 'primary', 'text' => 'Đã xác nhận'],
            ['type' => 'info', 'text' => 'Đóng gói'],
            ['type' => 'warning', 'text' => 'Vận chuyển'],
            ['type' => 'success', 'text' => 'Đã giao'],
            ['type' => 'danger', 'text' => 'Đã hủy'],
        ];
        return view("backend.order.delivered", compact('list_order',  'list_status', 'title'));
    }
    public function trash()
    {
        $title = 'Hóa đơn đã hủy';


        $list_order = Order::join('user', 'user.id', '=', 'order.user_id')
            ->join('orderdetail', 'orderdetail.order_id', '=', 'order.id')
            ->select('order.*', 'user.name as user_name', 'user.email as user_email', 'user.phone as user_phone')
            ->where('order.status', '=', '5')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();
        // return dd($list_order);
        $list_status = [
            ['type' => 'secondary', 'text' => 'Đơn hàng mới'],
            ['type' => 'primary', 'text' => 'Đã xác nhận'],
            ['type' => 'info', 'text' => 'Đóng gói'],
            ['type' => 'warning', 'text' => 'Vận chuyển'],
            ['type' => 'success', 'text' => 'Đã giao'],
            ['type' => 'danger', 'text' => 'Đã hủy'],
        ];
        return view("backend.order.trash", compact('list_order',  'list_status', 'title'));
    }
}

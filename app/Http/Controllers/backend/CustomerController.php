<?php

namespace App\Http\Controllers\backend;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::now();
        $targetDate = Carbon::create(2023, 2, 31);
        if ($targetDate->isValid()) {
            $diffInDays = "Ngày tháng năm hợp lệ.";
        } else {
            $diffInDays = "Ngày tháng năm không hợp lệ.";
        }

        $title = 'Tất cả khách hàng';
        $list_customer = User::where([['status', '<>', '0'], ['roles', '=', '0']])->orderBy('created_at', 'desc')->get();
        return view("backend.customer.index", compact('list_customer', 'title', 'diffInDays'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm Thành Viên';
        return view("backend.customer.create", compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:user|max:35|min:5|string',
            'email' => 'required|unique:user,email|email|max:30',
            'password' => 'required|max:40',
            'confirm_password' => 'required|same:password|max:40',
        ], [
            'email.required' => 'Bạn chưa điền email',
            'email.email' => 'Email không hợp lệ',

            'email.max' => 'Email không được dài quá 30 ký tự',
            'email.unique' => 'Email này đã được đăng ký, Xin hãy đăng nhập.',

            'password.required' => 'Bạn chưa điền mật khẩu',
            'password.max' => 'Mật khẩu không được dài quá 40 ký tự',

            'confirm_password.required' => 'Bạn chưa nhập lại mật khẩu',
            'confirm_password.max' => 'Không được dài quá 40 ký tự',
            'confirm_password.same' => 'Mật khẩu không trùng khớp',


        ]);
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
       
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // $user->level = 1;
        $user->status = $request->status;
        $user->created_at = date('Y-m-d H:i:s');
        $user->created_by =  Auth::guard('admin')->user()->id;
        //upload file

        if ($request->hasFile('image')) {
            $path = 'images/user/';
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $user->username . '.' . $extension;
            $file->move($path, $filename);
            $user->image = $filename;
        } else {
            if ($user->gender) {
                $user->image = 'user_women.png';
            } else {
                $user->image = 'user_men.png';
            }
        }
        // dd($user);
        if ($user->save()) {
            return redirect()->route('customer.index')->with('message', ['type' => 'success', 'msg' => 'Thêm thành công!']);
        }
        return redirect()->route('customer.create')->with('message', ['type' => 'danger', 'msg' => 'Thêm thất bại!!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Thông tin khách hàng';
        $customer = User::find($id);
        return view("backend.customer.show", compact('title', 'customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where([['status', '!=', '0'], ['id', '=', $id], ['roles', '=', '0']])->first();
        if ($user == null) {
            return redirect()->route('customer.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        }
        $title = 'Sửa Thành Viên';
        return view('backend.customer.edit', compact('title', 'user'));
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
        $user = User::find($id);
        $user->name = $request->name;
        $user->username = $request->username;
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->address = $request->address;
        
        // $user->level = 1;
        $user->status = $request->status;
        $user->created_at = date('Y-m-d H:i:s');
        $user->created_by =  Auth::guard('admin')->user()->id;
        //upload file

        if ($request->def_image == 1) {
            if ($user->gender) {
                $user->image = 'user_women.png';
            } else {
                $user->image = 'user_men.png';
            }
        }
        if ($request->hasFile('image')) {
            $path = 'images/user/';
            if (File::exists(public_path($path . $user->image)) && ($user->image != 'user_women.png') && ($user->image != 'user_men.png')) {
                File::delete(public_path($path . $user->image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $user->username . '.' . $extension;
            $file->move($path, $filename);
            $user->image = $filename;
        } else {
            if ($user->gender == 1 && (($user->image == 'user_women.png') || ($user->image == 'user_men.png'))) {
                $user->image = 'user_women.png';
            }
            if ($user->gender == 0 && (($user->image == 'user_women.png') || ($user->image == 'user_men.png'))) {
                $user->image = 'user_men.png';
            }
        }
        if ($user->save()) {
            return redirect()->route('customer.index')->with('message', ['type' => 'success', 'msg' => 'Cập nhật thành công!']);
        }
        return redirect()->route('customer.edit')->with('message', ['type' => 'danger', 'msg' => 'Cập nhật thất bại!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $path_dir = "images/user/";
            $path_image_delete = public_path($path_dir . $user->image);
            if (($user->image <> 'user_men.png') && ($user->image <> 'user_women.png')) {
                if (File::exists($path_image_delete)) {
                    File::delete($path_image_delete);
                }
            }
            if (Auth::guard('admin')->user()->id == $id) {
                return redirect()->route('customer.index')->with('message', ['type' => 'danger', 'msg' => 'Người dùng kgoong thể xóa bản thân !!!']);
            } else {
                $user->delete();
                return redirect()->route('customer.trash')->with('message', ['type' => 'success', 'msg' => 'Xóa sản phẩm thành công']);
            }
        }
    }
    // delete
    public function delete($id, Request $request)
    {
        $customer = User::find($id);
        if ($customer == null) {
            return redirect()->route('customer.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $customer->status = 0;
            $customer->updated_at = date('Y-m-d H:i:s');
            $customer->updated_by =   Auth::guard('admin')->user()->id;
            $customer->save();
            return redirect()->route('customer.index')->with('message', ['type' => 'success', 'msg' => 'Chuyển vào thùng rác thành công']);
        }
    }
    // trash
    public function trash()
    {
        $title = 'Tài khoản bị khóa';
        $list_customer = User::where([['status', '=', '0'], ['roles', '=', '0']])->orderBy('created_at', 'desc')->get();
        return view("backend.customer.trash", compact('list_customer', 'title'));
    }
    // status
    public function status($id, Request $request)
    {
        $customer = User::find($id);
        if ($customer == null) {
            return redirect()->route('customer.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $customer->status = ($customer->status == 1) ? 2 : 1;
            $customer->updated_at = date('Y-m-d H:i:s');
            $customer->updated_by =   Auth::guard('admin')->user()->id;
            $customer->save();
            return redirect()->route('customer.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
        }
    }
    // restore
    public function restore($id, Request $request)
    {
        $customer = User::find($id);
        if ($customer == null) {
            return redirect()->route('customer.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $customer->status = 2;
            $customer->updated_at = date('Y-m-d H:i:s');
            $customer->updated_by =   Auth::guard('admin')->user()->id;
            $customer->save();
            return redirect()->route('customer.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục sản phẩm thành công']);
        }
    }
}

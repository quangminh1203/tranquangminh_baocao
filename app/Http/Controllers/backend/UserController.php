<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả tài khoản ';
        $list_user = User::where([['status', '<>', '0'], ['roles', '!=', '0']])->orderBy('created_at', 'desc')->get();
        return view("backend.user.index", compact('list_user', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm Thành Viên';
        return view("backend.user.create", compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->roles = 1;
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
        if ($user->save()) {
            return redirect()->route('user.index')->with('message', ['type' => 'success', 'msg' => 'Thêm thành công!']);
        }
        return redirect()->route('user.create')->with('message', ['type' => 'danger', 'msg' => 'Thêm thất bại!!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Thông tin thành Viên';
        $user = User::find($id);
        return view("backend.user.show", compact('title', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where([['status', '!=', '0'], ['id', '=', $id], ['roles', '!=', '0']])->first();
        if ($user == null) {
            return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        }
        $title = 'Sửa Thành Viên';
        return view('backend.user.edit', compact('title', 'user'));
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
        $user->roles = 1;
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
            return redirect()->route('user.index')->with('message', ['type' => 'success', 'msg' => 'Cập nhật thành công!']);
        }
        return redirect()->route('user.edit')->with('message', ['type' => 'danger', 'msg' => 'Cập nhật thất bại!!']);
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
                return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Người dùng kgoong thể xóa bản thân !!!']);
            } else {
                $user->delete();
                return redirect()->route('user.trash')->with('message', ['type' => 'success', 'msg' => 'Xóa sản phẩm thành công']);
            }
        }
    }
    // delete
    public function delete($id, Request $request)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            if (Auth::guard('admin')->user()->id == $id) {
                return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Người dùng không thể xóa bản thân !!!']);
            } else {
                $user->status = 0;
                $user->updated_at = date('Y-m-d H:i:s');
                $user->updated_by =   Auth::guard('admin')->user()->id;
                $user->save();
                return redirect()->route('user.index')->with('message', ['type' => 'success', 'msg' => 'Chuyển vào thùng rác thành công']);
            }
        }
    }
    // trash
    public function trash()
    {
        $title = 'Tất cả tài khoản không còn sử dụng';
        $list_user = User::where('status', '=', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.user.trash", compact('list_user', 'title'));
    }
    // status
    public function status($id, Request $request)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            if (Auth::guard('admin')->user()->id == $id) {
                return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Người dùng không thể Thay đổi trạng thái bản thân !!!']);
            } else {
                $user->status = ($user->status == 1) ? 2 : 1;
                $user->updated_at = date('Y-m-d H:i:s');
                $user->updated_by =   Auth::guard('admin')->user()->id;
                $user->save();
                return redirect()->route('user.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
            }
        }
    }
    // restore
    public function restore($id, Request $request)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $user->status = 2;
            $user->updated_at = date('Y-m-d H:i:s');
            $user->updated_by =   Auth::guard('admin')->user()->id;
            $user->save();
            return redirect()->route('user.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục sản phẩm thành công']);
        }
    }

    public function deleteAll(Request $request)
    {

        if (isset($request->checkId)) {
            $list_id = $request->input('checkId');

            $count_max = sizeof($list_id);
            $count = 0;
            foreach ($list_id as $id) {
                $user = User::find($id);
                if ($user == null) {
                    return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                }
                if (Auth::guard('admin')->user()->id == $id) {
                    return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Người dùng không thể xóa bản thân !!!']);
                }
                $user->status = 0;
                $user->updated_at = date('Y-m-d H:i:s');
                $user->updated_by =  Auth::guard('admin')->user()->id;
                $user->save();

                $count++;
            }
            return redirect()->route('user.index')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& Vào thùng rác để xem!!!"]);
        } else {
            return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
        }
    }
    // destroy-multi
    public function TrashAll(Request $request)
    {
        if (isset($request['DELETE_ALL'])) {

            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;
                foreach ($list_id as $list) {
                    $user = User::find($list);
                    if ($user == null) {
                        return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $path_dir = "images/user/";
                    $path_image_delete = public_path($path_dir . $user->image);

                    if ($user->delete()) {

                        if (($user->image <> 'user_men.png') && ($user->image <> 'user_women.png')) {
                            if (File::exists($path_image_delete)) {
                                File::delete($path_image_delete);
                            }
                        }
                    }
                    $count++;
                }
                return redirect()->route('user.trash')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('user.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
        if (isset($request['RESTORE_ALL'])) {
            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;

                foreach ($list_id as $id) {
                    $user = User::find($id);
                    if ($user == null) {
                        return redirect()->route('user.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $user->status = 2;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->updated_by =  Auth::guard('admin')->user()->id;
                    $user->save();
                    $count++;
                }
                return redirect()->route('user.trash')->with('message', ['type' => 'success', 'msg' => "Khôi phục thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('user.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
    }
}

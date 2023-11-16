<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Link;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả giới thiệu';
        $list_page = Post::where([['post.status', '<>', '0'], ['post.type', '=', 'page']])
            ->orderBy('post.created_at', 'desc')->get();

        return view("backend.page.index", compact('list_page', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm giới thiệu';

        return view('backend.page.create', compact('title'));
    }


    public function store(StorePageRequest $request)
    {
        $page = new Post();
        $page->title = $request->title;
        $page->slug = Str::slug($request->title, '-');
        $page->detail = $request->detail;
        $page->metakey = $request->metakey;
        $page->metadesc = $request->metadesc;
        $page->status = $request->status;
        $page->type = 'page';
        $page->created_at = date('Y-m-d H:i:s');
        $page->created_by = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : 1;
        // upload file
        if ($request->has('image')) {
            $path_dir = "images/post/";
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $page->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $page->image = $filename;
        }
        // end
        if ($page->save()) {
            $link = new Link();
            $link->link = $page->slug;
            $link->table_id = $page->id;
            $link->type = 'page';
            $link->save();
            return redirect()->route('page.index')->with('message', ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công']);
        } else {
            return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Thông tin giới thiệu';
        $page = Post::where('post.id', '=', $id)
            ->select(
                "*",
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "post.updated_by")->toSql() . ") as updated_name"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "post.created_by")->toSql() . ") as created_name")
            )
            ->first();
        if ($page == null) {
            return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            return view('backend.page.show', compact('page', 'title'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Post::find($id);
        $title = 'Sửa giới thiệu';
        return view('backend.page.edit', compact('page', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePageRequest $request, $id)
    {
        $page = Post::find($id);
        $request->validate([
            'title' => 'unique:post,title,' . $id . ',id'
        ], [
            'title.unique' => 'Tên đã được sử dụng, vui lòng sử dụng một tên khác',
        ]);
        $page->title = $request->title;
        $page->slug = Str::slug($request->title, '-');
        $page->detail = $request->detail;
        $page->metakey = $request->metakey;
        $page->metadesc = $request->metadesc;
        $page->status = $request->status;
        $page->type = 'page';
        $page->updated_at = date('Y-m-d H:i:s');

        $page->updated_by =   Auth::guard('admin')->user()->id;
        if ($request->has('image')) {
            $path_dir = "images/post/";
            if (File::exists(public_path($path_dir . $page->image))) {
                File::delete(public_path($path_dir . $page->image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $page->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $page->image = $filename;
        }
        // // end
        if ($page->status == 2) {
            $page->menus()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
        }
        if ($page->save()) {
            $link = Link::where([['type', '=', 'page'], ['table_id', '=', $id]])->first();
            $link->link = $page->slug;

            $link->save();
            return redirect()->route('page.index')->with('message', ['type' => 'success', 'msg' => 'Cập nhật sản phẩm thành công']);
        } else {
            return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
        }
        // dd($page);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Post::find($id);
        if ($page == null) {
            return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {

            if ($page->delete()) {
                $path_dir = "images/post/";
                $path_image_delete = public_path($path_dir . $page->image);
                if (File::exists($path_image_delete)) {
                    File::delete($path_image_delete);
                }
                $link = Link::where(
                    [['type', '=', 'page'], ['table_id', '=', $id]]
                )->first();

                $link->delete();
                $page->menus()->delete();
            }
            return redirect()->route('page.trash')->with('message', ['type' => 'success', 'msg' => 'Hủy sản phẩm thành công']);
        }
    }
    #delete
    public function delete($id, Request $request)
    {
        $page = Post::find($id);
        if ($page == null) {
            return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $page->status = 0;
            $page->updated_at = date('Y-m-d H:i:s');
            $page->updated_by =   Auth::guard('admin')->user()->id;
            if ($page->status == 0) {
                $page->menus()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
            }
            $page->save();
            return redirect()->route('page.index')->with('message', ['type' => 'success', 'msg' => 'Chuyển vào thùng rác thành công']);
        }
    }
    #restore
    public function restore($id, Request $request)
    {
        $page = Post::find($id);
        if ($page == null) {
            return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $page->status = 2;
            $page->updated_at = date('Y-m-d H:i:s');
            $page->updated_by =   Auth::guard('admin')->user()->id;
            $page->save();
            return redirect()->route('page.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục sản phẩm thành công']);
        }
    }
    #status
    public function status($id, Request $request)
    {
        $page = Post::find($id);
        if ($page == null) {
            return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $page->status = ($page->status == 1) ? 2 : 1;
            $page->updated_at = date('Y-m-d H:i:s');
            $page->updated_by =   Auth::guard('admin')->user()->id;
            if ($page->status == 2) {
                $page->menus()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
            }
            $page->save();
            return redirect()->route('page.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
        }
    }
    // trash
    public function trash()
    {
        $title = 'Thùng rác ';
        $list_page = Post::where([['post.status', '=', '0'], ['post.type', '=', 'page']])
            ->orderBy('post.created_at', 'desc')->get();
        return view("backend.page.trash", compact('list_page', 'title'));
    }

    // delete-multi
    public function deleteAll(Request $request)
    {

        if (isset($request->checkId)) {
            $list_id = $request->input('checkId');

            $count_max = sizeof($list_id);
            $count = 0;
            foreach ($list_id as $id) {
                $page = Post::find($id);
                if ($page == null) {
                    return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                }
                $page->status = 0;
                if ($page->status == 0) {
                    $page->menus()->update([
                        'status' => 2,
                        'updated_by' =>  Auth::guard('admin')->user()->id
                    ]);
                }
                $page->updated_at = date('Y-m-d H:i:s');
                $page->updated_by =  Auth::guard('admin')->user()->id;
                $page->save();

                $count++;
            }
            return redirect()->route('page.index')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& Vào thùng rác để xem!!!"]);
        } else {
            return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
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
                    $page = Post::find($list);
                    if ($page == null) {
                        return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $path_dir = "images/post/";
                    $path_image_delete = public_path($path_dir . $page->image);
                    if (File::exists($path_image_delete)) {
                        File::delete($path_image_delete);
                    }
                    if ($page->delete()) {
                        $link = Link::where(
                            [['type', '=', 'page'], ['table_id', '=', $list]]
                        )->first();

                        $link->delete();
                        $page->menus()->delete();
                    }
                    $count++;
                }
                return redirect()->route('page.trash')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('page.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
        if (isset($request['RESTORE_ALL'])) {
            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;

                foreach ($list_id as $id) {
                    $page = Post::find($id);
                    if ($page == null) {
                        return redirect()->route('page.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $page->status = 2;
                    $page->updated_at = date('Y-m-d H:i:s');
                    $page->updated_by =  Auth::guard('admin')->user()->id;
                    $page->save();
                    $count++;
                }
                return redirect()->route('page.trash')->with('message', ['type' => 'success', 'msg' => "Khôi phục thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('page.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
    }
}

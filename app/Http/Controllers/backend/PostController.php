<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả bài viết';
        $list_post = Post::where([['post.status', '<>', '0'], ['post.type', '=', 'post']])
            ->join('topic', 'topic.id', 'post.topic_id')
            ->select('post.*', 'topic.name as topic_name')
            ->orderBy('post.created_at', 'desc')->get();



        return view("backend.post.index", compact('list_post', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm bài viết';
        $list_topic = Topic::where('status',  '!=', '0')
            ->get();
        $html_topic_id = "";

        foreach ($list_topic as $topic) {
            if ($topic->id == old('topic_id')) {
                $html_topic_id .= "<option selected value='" . $topic->id . "'>" . $topic->name . "</option>";
            } else {
                $html_topic_id .= "<option value='" . $topic->id . "'>" . $topic->name . "</option>";
            }
        }
        return view('backend.post.create', compact('html_topic_id', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title, '-');
        $post->detail = $request->detail;
        $post->metakey = $request->metakey;
        $post->metadesc = $request->metadesc;
        $post->topic_id = $request->topic_id;
        $post->status = $request->status;
        $post->type = 'post';
        $post->created_at = date('Y-m-d H:i:s');
        $post->created_by = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : 1;
        // upload file
        if ($request->has('image')) {
            $path_dir = "images/post/";
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $post->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $post->image = $filename;
            $post->save();
            return redirect()->route('post.index')->with('message', ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công']);
        }
        // end
        else {
            return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
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
        $title = 'Thông tin bài viết';
        $post = Post::where('post.id', '=', $id)
            ->select(
                "*",
                DB::raw("(" . Topic::select("name")->whereColumn("topic.id", "=", "post.topic_id")->toSql() . ") as topic_name"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "post.updated_by")->toSql() . ") as updated_name"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "post.created_by")->toSql() . ") as created_name")
            )
            ->first();
        if ($post == null) {
            return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            return view('backend.post.show', compact('post', 'title'));
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
        $post = Post::find($id);
        $title = 'Sửa bài viết';
        $list_topic = Topic::where('status',  '!=', '0')
            ->get();
        $html_topic_id = "";

        foreach ($list_topic as $topic) {
            $html_topic_id .= "<option value='" . $topic->id . "'>" . $topic->name . "</option>";
        }
        return view('backend.post.edit', compact('post', 'html_topic_id', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);
        $post->title = $request->title;
        $post->slug = Str::slug($request->title, '-');
        $post->detail = $request->detail;
        $post->metakey = $request->metakey;
        $post->metadesc = $request->metadesc;
        $post->topic_id = $request->topic_id;
        $post->status = $request->status;
        $post->type = 'post';
        $post->updated_at = date('Y-m-d H:i:s');

        $post->updated_by =   Auth::guard('admin')->user()->id;
        // upload file
        if ($request->has('image')) {
            $path_dir = "images/post/";
            if (File::exists(public_path($path_dir . $post->image))) {
                File::delete(public_path($path_dir . $post->image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $post->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $post->image = $filename;
        }
        $post->save();
        return redirect()->route('post.index')->with('message', ['type' => 'success', 'msg' => 'Sửa bài viết thành công']);
        // end

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {

            $path_dir = "images/post/";
            $path_image_delete = public_path($path_dir . $post->image);

            if ($post->delete()) {
                if (File::exists($path_image_delete)) {
                    File::delete($path_image_delete);
                }
            }
            return redirect()->route('post.trash')->with('message', ['type' => 'success', 'msg' => 'Hủy sản phẩm thành công']);
        }
    }
    #delete
    public function delete($id, Request $request)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $post->status = 0;
            $post->updated_at = date('Y-m-d H:i:s');
            $post->updated_by =   Auth::guard('admin')->user()->id;
            $post->save();
            return redirect()->route('post.index')->with('message', ['type' => 'success', 'msg' => 'Chuyển vào thùng rác thành công']);
        }
    }
    #restore
    public function restore($id, Request $request)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $post->status = 2;
            $post->updated_at = date('Y-m-d H:i:s');
            $post->updated_by =   Auth::guard('admin')->user()->id;
            $post->save();
            return redirect()->route('post.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục sản phẩm thành công']);
        }
    }
    // trash
    public function trash()
    {
        $title = 'Thùng rác bài viết';
        $list_post = Post::where([['post.status', '=', '0'], ['post.type', '=', 'post']])
            ->join('topic', 'topic.id', 'post.topic_id')
            ->select('post.*', 'topic.name as topic_name')
            ->orderBy('post.created_at', 'desc')->get();
        return view("backend.post.trash", compact('list_post', 'title'));
    }
    // status
    public function status($id, Request $request)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $post->status = ($post->status == 1) ? 2 : 1;
            $post->updated_at = date('Y-m-d H:i:s');
            $post->updated_by =   Auth::guard('admin')->user()->id;
            $post->save();
            return redirect()->route('post.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
        }
    }
    public function deleteAll(Request $request)
    {

        if (isset($request->checkId)) {
            $list_id = $request->input('checkId');

            $count_max = sizeof($list_id);
            $count = 0;
            foreach ($list_id as $id) {
                $post = Post::find($id);
                if ($post == null) {
                    return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                }
                $post->status = 0;

                $post->updated_at = date('Y-m-d H:i:s');
                $post->updated_by =  Auth::guard('admin')->user()->id;
                $post->save();

                $count++;
            }
            return redirect()->route('post.index')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& Vào thùng rác để xem!!!"]);
        } else {
            return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
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
                    $post = Post::find($list);
                    if ($post == null) {
                        return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $path_dir = "images/post/";
                    $path_image_delete = public_path($path_dir . $post->image);

                    if ($post->delete()) {
                        if (File::exists($path_image_delete)) {
                            File::delete($path_image_delete);
                        }
                    }
                    $count++;
                }
                return redirect()->route('post.trash')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('post.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
        if (isset($request['RESTORE_ALL'])) {
            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;

                foreach ($list_id as $id) {
                    $post = Post::find($id);
                    if ($post == null) {
                        return redirect()->route('post.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $post->status = 2;
                    $post->updated_at = date('Y-m-d H:i:s');
                    $post->updated_by =  Auth::guard('admin')->user()->id;
                    $post->save();
                    $count++;
                }
                return redirect()->route('post.trash')->with('message', ['type' => 'success', 'msg' => "Khôi phục thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('post.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
    }
}

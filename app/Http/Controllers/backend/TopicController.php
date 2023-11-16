<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả đề tài';
        $list_topic = Topic::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.topic.index", compact('list_topic', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm chủ đề bài viết';
        $list_topic = Topic::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        $html_parent_id = "";
        $html_sort_order = "";
        foreach ($list_topic as $topic) {

            if ($topic->id == old('parent_id')) {
                $html_parent_id .= "<option selected value='" . $topic->id . "'>" . $topic->name . "</option>";
            } else {
                $html_parent_id .= "<option  value='" . $topic->id . "'>" . $topic->name . "</option>";
            }
            if ($topic->sort_order == old('sort_order - 1')) {
                $html_sort_order .= "<option selected value='" . ($topic->sort_order + 1) . "'>Sau: " . $topic->name . "</option>";
            } else {
                $html_sort_order .= "<option value='" . ($topic->sort_order + 1) . "'>Sau: " . $topic->name . "</option>";
            }
        }
        return view('backend.topic.create', compact('html_parent_id', 'html_sort_order', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTopicRequest $request)
    {
        $topic = new Topic();
        $topic->name = $request->name;
        $topic->slug = Str::slug($request->name, '-');
        $topic->metakey = $request->metakey;
        $topic->metadesc = $request->metadesc;
        $topic->parent_id = $request->parent_id;
        $topic->sort_order = $request->sort_order;
        $topic->status = $request->status;
        $topic->created_at = date('Y-m-d H:i:s');
        $topic->created_by =  Auth::guard('admin')->user()->id;
        if ($topic->save()) {
            $link = new Link();
            $link->link = $topic->slug;
            $link->table_id = $topic->id;
            $link->type = 'topic';
            $link->save();
            return redirect()->route('topic.index')->with('message', ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công']);
        } else {
            return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
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
        $title = 'Thông tin dề tài';
        $total = Topic::join('post', 'post.topic_id', '=', 'topic.id')
            ->where('topic.id', '=', $id)
            ->distinct()
            ->count();


        $post_topic = Topic::join('post', 'post.topic_id', '=', 'topic.id')
            ->select('post.*', 'post.title as post_name', 'post.id as post_id')
            ->where('topic.id', '=', $id)
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();

        $topic = Topic::where('topic.id', '=', $id)
            ->select(
                "*",
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "topic.updated_by")->toSql() . ") as updated_name"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "topic.created_by")->toSql() . ") as created_name")
            )
            ->first();
        if ($topic == null) {
            return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            return view('backend.topic.show', compact('topic', 'total',  'post_topic', 'title'));
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
        $topic = Topic::find($id);
        $title = 'Sửa chủ đề';
        $list_topic = Topic::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        $html_parent_id = "";
        $html_sort_order = "";
        foreach ($list_topic as $item) {
            if ($topic->id != $item->id) {
                if ($topic->parent_id == $item->id) {
                    $html_parent_id .= "<option selected value='" . $item->id . "'>" . $item->name . "</option>";
                } else {
                    $html_parent_id .= "<option  value='" . $topic->id . "'>" . $topic->name . "</option>";
                }
                if ($topic->sort_order - 1 == $item->sort_order) {
                    $html_sort_order .= "<option selected value='" . ($topic->sort_order + 1) . "'>Sau: " . $topic->name . "</option>";
                } else {
                    $html_sort_order .= "<option value='" . ($topic->sort_order + 1) . "'>Sau: " . $topic->name . "</option>";
                }
            }
        }
        return view('backend.topic.edit', compact('topic', 'html_parent_id', 'html_sort_order', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTopicRequest $request, $id)
    {
        $topic = Topic::find($id);

        $request->validate([
            'name' => 'unique:topic,name,' . $id . ',id'
        ], [
            'name.unique' => 'Tên đã được sử dụng, vui lòng sử dụng một tên khác',
        ]);
        $topic->name = $request->name;
        $topic->slug = Str::slug($request->name, '-');
        $topic->metakey = $request->metakey;
        $topic->metadesc = $request->metadesc;
        $topic->parent_id = $request->parent_id;
        $topic->sort_order = $request->sort_order;
        $topic->status = $request->status;
        $topic->updated_at = date('Y-m-d H:i:s');
        if ($topic->status = 2) {
            $topic->posts()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
            $topic->menus()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
        }
        $topic->updated_by =   Auth::guard('admin')->user()->id;
        if ($topic->save()) {
            $link = Link::where([['type', '=', 'topic'], ['table_id', '=', $id]])->first();
            $link->link = $topic->slug;
            $link->save();
            return redirect()->route('topic.index')->with('message', ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công']);
        } else {
            return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = Topic::find($id);
        if ($topic == null) {
            return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {


            if ($topic->delete()) {
                $link = Link::where(
                    [['type', '=', 'topic'], ['table_id', '=', $id]]
                )->first();
                $topic->menus()->delete();
                $topic->posts()->delete();
                $link->delete();
            }
            return redirect()->route('topic.trash')->with('message', ['type' => 'success', 'msg' => 'Hủy sản phẩm thành công']);
        }
    }
    #delete
    public function delete($id, Request $request)
    {
        $topic = Topic::find($id);
        if ($topic == null) {
            return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $topic->status = 0;
            $topic->posts()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
            $topic->menus()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
            $topic->updated_at = date('Y-m-d H:i:s');
            $topic->updated_by =   Auth::guard('admin')->user()->id;
            $topic->save();
            return redirect()->route('topic.index')->with('message', ['type' => 'success', 'msg' => 'Chuyển vào thùng rác thành công']);
        }
    }
    #restore
    public function restore($id, Request $request)
    {
        $topic = Topic::find($id);
        if ($topic == null) {
            return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $topic->status = 2;
            $topic->updated_at = date('Y-m-d H:i:s');
            $topic->updated_by =   Auth::guard('admin')->user()->id;
            $topic->save();
            return redirect()->route('topic.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục sản phẩm thành công']);
        }
    }
    // trash
    public function trash()
    {
        $title = 'Thùng rác đề tài';
        $list_topic = Topic::where('status', '=', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.topic.trash", compact('list_topic', 'title'));
    }
    // status
    public function status($id, Request $request)
    {
        $topic = Topic::find($id);
        if ($topic == null) {
            return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $topic->status = ($topic->status == 1) ? 2 : 1;
            if ($topic->status = 2) {
                $topic->posts()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $topic->menus()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
            }
            $topic->updated_at = date('Y-m-d H:i:s');
            $topic->updated_by =   Auth::guard('admin')->user()->id;
            $topic->save();
            return redirect()->route('topic.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
        }
    }
    public function deleteAll(Request $request)
    {

        if (isset($request->checkId)) {
            $list_id = $request->input('checkId');

            $count_max = sizeof($list_id);
            $count = 0;
            foreach ($list_id as $id) {
                $topic = Topic::find($id);
                if ($topic == null) {
                    return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                }
                $topic->status = 0;
                $topic->posts()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $topic->menus()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $topic->updated_at = date('Y-m-d H:i:s');
                $topic->updated_by =  Auth::guard('admin')->user()->id;
                $topic->save();
                if ($topic->status == 0) {
                    $topic->posts()->update(['status' => 2]);
                }
                $count++;
            }
            return redirect()->route('topic.index')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& Vào thùng rác để xem!!!"]);
        } else {
            return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
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
                    $topic = Topic::find($list);
                    if ($topic == null) {
                        return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }

                    if ($topic->delete()) {
                        $topic->posts()->delete();
                        $topic->menus()->delete();
                        $link = Link::where(
                            [['type', '=', 'topic'], ['table_id', '=', $list]]
                        )->first();
                        $link->delete();
                    }
                    $count++;
                }
                return redirect()->route('topic.trash')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('topic.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
        if (isset($request['RESTORE_ALL'])) {
            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;

                foreach ($list_id as $id) {
                    $topic = Topic::find($id);
                    if ($topic == null) {
                        return redirect()->route('topic.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $topic->status = 2;
                    $topic->updated_at = date('Y-m-d H:i:s');
                    $topic->updated_by =  Auth::guard('admin')->user()->id;
                    $topic->save();
                    $count++;
                }
                return redirect()->route('topic.trash')->with('message', ['type' => 'success', 'msg' => "Khôi phục thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('topic.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
    }
}

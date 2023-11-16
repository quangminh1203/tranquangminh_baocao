<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả Slider';
        $list_slider = Slider::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.slider.index", compact('list_slider', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm sản phẩm';
        $html_sort_order = "";
        $list_slider = Slider::where('status', '<>', '0')->get();
        foreach ($list_slider as $slider) {
            if ($slider->sort_order == old('sort_order - 1')) {
                $html_sort_order .= "<option selected value='" . ($slider->sort_order + 1) . "'>Sau: " . $slider->name . "</option>";
            } else {
                $html_sort_order .= "<option value='" . ($slider->sort_order + 1) . "'>Sau: " . $slider->name . "</option>";
            }
        }
        return view("backend.slider.create", compact('title', 'html_sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slider = new Slider();
        $slider->name = $request->name;
        $slider->slug = Str::slug($request->name, '-');
        $slider->link = $request->link;
        $slider->position = $request->position;
        $slider->sort_order = $request->sort_order;
        // $slider->level = 1;
        $slider->status = $request->status;
        $slider->created_at = date('Y-m-d H:i:s');
        $slider->created_by =  Auth::guard('admin')->user()->id;
        // upload file
        if ($request->has('image')) {
            $path_dir = "images/slider/";
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $slider->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $slider->image = $filename;
            $slider->save();
            return redirect()->route('slider.index')->with('message', ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công']);
        } else {
            return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
        }
        // end
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Thông tin slider';
        $slider = Slider::where('slider.id', '=', $id)
            ->select(
                "*",
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "slider.updated_by")->toSql() . ") as updated_name"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "slider.created_by")->toSql() . ") as created_name")
            )
            ->first();
        if ($slider == null) {
            return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            return view('backend.slider.show', compact('slider', 'title'));
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
        $slider = Slider::find($id);
        $title = 'Sửa thương hiệu';
        $list_slider = Slider::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();

        $html_sort_order = "";
        foreach ($list_slider as $item) {
            if ($item->sort_order - 1 == $slider->sort_order) {
                $html_sort_order .= "<option selected value='" . ($slider->sort_order + 1) . "'>Sau: " . $slider->name . "</option>";
            } else {
                $html_sort_order .= "<option  value='" . ($slider->sort_order + 1) . "'>Sau: " . $slider->name . "</option>";
            }
        }

        return view('backend.slider.edit', compact('slider',  'html_sort_order', 'title'));
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
        $slider = Slider::find($id);
        $slider->name = $request->name;
        $slider->slug = Str::slug($request->name, '-');
        $slider->link = $request->link;
        $slider->position = $request->position;
        $slider->sort_order = $request->sort_order;
        // $slider->level = 1;
        $slider->status = $request->status;
        $slider->created_at = date('Y-m-d H:i:s');
        $slider->created_by =  Auth::guard('admin')->user()->id;
        // upload file
        if ($request->has('image')) {
            $path_dir = "images/slider/";
            if (File::exists(public_path($path_dir . $slider->image))) {
                File::delete(public_path($path_dir . $slider->image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $slider->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $slider->image = $filename;
        }
        $slider->save();
        return redirect()->route('slider.index')->with('message', ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);
        if ($slider == null) {
            return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $slider->delete();
            return redirect()->route('slider.trash')->with('message', ['type' => 'success', 'msg' => 'Xóa sản phẩm thành công']);
        }
    }
    // delete
    public function delete($id, Request $request)
    {
        $slider = Slider::find($id);
        if ($slider == null) {
            return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $slider->status = 0;
            $slider->updated_at = date('Y-m-d H:i:s');
            $slider->updated_by =   Auth::guard('admin')->user()->id;
            $slider->save();
            return redirect()->route('slider.index')->with('message', ['type' => 'success', 'msg' => 'Chuyển vào thùng rác thành công']);
        }
    }
    // trash
    public function trash()
    {
        $title = 'Thùng rác';
        $list_slider = Slider::where('status', '=', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.slider.trash", compact('list_slider', 'title'));
    }
    // status
    public function status($id, Request $request)
    {
        $slider = Slider::find($id);
        if ($slider == null) {
            return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $slider->status = ($slider->status == 1) ? 2 : 1;
            $slider->updated_at = date('Y-m-d H:i:s');
            $slider->updated_by =   Auth::guard('admin')->user()->id;
            $slider->save();
            return redirect()->route('slider.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
        }
    }
    // restore
    public function restore($id, Request $request)
    {
        $slider = Slider::find($id);
        if ($slider == null) {
            return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $slider->status = 2;
            $slider->updated_at = date('Y-m-d H:i:s');
            $slider->updated_by =   Auth::guard('admin')->user()->id;
            $slider->save();
            return redirect()->route('slider.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục sản phẩm thành công']);
        }
    }
    // delete-multi
    public function deleteAll(Request $request)
    {

        if (isset($request->checkId)) {
            $list_id = $request->input('checkId');

            $count_max = sizeof($list_id);
            $count = 0;
            foreach ($list_id as $id) {
                $slider = Slider::find($id);
                if ($slider == null) {
                    return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                }
                $slider->status = 0;

                $slider->updated_at = date('Y-m-d H:i:s');
                $slider->updated_by =  Auth::guard('admin')->user()->id;
                $slider->save();

                $count++;
            }
            return redirect()->route('slider.index')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& Vào thùng rác để xem!!!"]);
        } else {
            return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
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
                    $slider = Slider::find($list);
                    if ($slider == null) {
                        return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $path_dir = "images/slider/";
                    $path_image_delete = public_path($path_dir . $slider->image);

                    if ($slider->delete()) {
                        if (File::exists($path_image_delete)) {
                            File::delete($path_image_delete);
                        }
                    }
                    $count++;
                }
                return redirect()->route('slider.trash')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('slider.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
        if (isset($request['RESTORE_ALL'])) {
            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;

                foreach ($list_id as $id) {
                    $slider = Slider::find($id);
                    if ($slider == null) {
                        return redirect()->route('slider.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $slider->status = 2;
                    $slider->updated_at = date('Y-m-d H:i:s');
                    $slider->updated_by =  Auth::guard('admin')->user()->id;
                    $slider->save();
                    $count++;
                }
                return redirect()->route('slider.trash')->with('message', ['type' => 'success', 'msg' => "Khôi phục thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('slider.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
    }
}

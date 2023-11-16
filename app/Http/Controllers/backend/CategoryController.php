<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả danh mục';
        $list_category = Category::where('status',  '<>', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.category.index", compact('list_category', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm danh mục';
        $list_category = Category::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        $html_parent_id = "";
        $html_sort_order = "";
        foreach ($list_category as $category) {
            if ($category->id == old('parent_id')) {
                $html_parent_id .= "<option selected value='" . $category->id . "'>" . $category->name . "</option>";
            } else {
                $html_parent_id .= "<option  value='" . $category->id . "'>" . $category->name . "</option>";
            }
            if ($category->sort_order == old('sort_order - 1')) {
                $html_sort_order .= "<option selected value='" . ($category->sort_order + 1) . "'>Sau: " . $category->name . "</option>";
            } else {
                $html_sort_order .= "<option value='" . ($category->sort_order + 1) . "'>Sau: " . $category->name . "</option>";
            }
        }
        // dd($html_parent_id);
        return view('backend.category.create', compact('html_parent_id', 'html_sort_order', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name, '-');
        $category->metakey = $request->metakey;
        $category->metadesc = $request->metadesc;
        $category->parent_id =  $request->parent_id;
        $category->sort_order = $request->sort_order;
        $category->level = $category->level +  1;
        $category->status = $request->status;
        $category->created_at = date('Y-m-d H:i:s');

        $category->created_by =   Auth::guard('admin')->user()->id;

        // upload file
        if ($request->has('image')) {
            $path_dir = "images/category/";
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $category->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $category->image = $filename;
        }
        // end
        if ($category->save()) {
            $link = new Link();
            $link->link = $category->slug;
            $link->table_id = $category->id;
            $link->type = 'category';
            $link->save();
            return redirect()->route('category.index')->with('message', ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công']);
        } else {
            return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
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
        $title = 'Thông tin danh mục';
        $total = category::join('product', 'product.category_id', '=', 'category.id')
            ->where('category.id', '=', $id)
            ->count();
        $total_sale = category::join('product', 'product.category_id', '=', 'category.id')
            // ->where('product.price_sale', '>', '0')
            ->where('category.id', '=', $id)
            ->count();

        $product_category = Category::join('product', 'product.category_id', '=', 'category.id')
            ->join('product_sale', 'product_sale.product_id', '=', 'product.id')
            ->select('product.*', 'product.name as product_name', 'product.id as product_id')
            ->where('category.id', '=', $id)
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();

        // dd($product_category);
        $category = category::where('category.id', '=', $id)
            ->select(
                "*",
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "category.created_by")->toSql() . ") as created_name"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "category.updated_by")->toSql() . ") as updated_name")
            )
            ->first();
        if ($category == null) {
            return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {

            return view('backend.category.show', compact('category', 'total', 'total_sale', 'product_category', 'title'));
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
        $category = Category::find($id);
        $title = 'Sửa danh mục';
        $list_category = Category::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        $html_parent_id = "";
        $html_sort_order = "";
        foreach ($list_category as $item) {
            if ($item->id != $id) {
                if ($category->parent_id == $item->id) {
                    $html_parent_id .= "<option selected value='" . $item->id . "'>" . $item->name . "</option>";
                } else {
                    $html_parent_id .= "<option value='" . $item->id . "'>" . $item->name . "</option>";
                }
                if ($category->sort_order - 1  == $item->sort_order) {
                    $html_sort_order .= "<option selected value='" . ($item->sort_order) . "'>Sau: " . $item->name . "</option>";
                } else {
                    $html_sort_order .= "<option value='" . ($item->sort_order) . "'>Sau: " . $item->name . "</option>";
                }
            }
        }
        return view('backend.category.edit', compact('category', 'html_parent_id', 'html_sort_order', 'title'));
    }

    /**
     * Update the specified resource in storage.

     */
    public function update($id, UpdateCategoryRequest $request)
    {
        $request->validate([
            'name' => 'unique:category,name,' . $id . ',id'
        ], [
            'name.unique' => 'Tên đã được sử dụng, vui lòng sử dụng một tên khác',
        ]);

        $category = Category::find($id);
        $category->name = $request->name;

        $category->slug = Str::slug($request->name, '-');
        $category->metakey = $request->metakey;
        $category->metadesc = $request->metadesc;
        $category->parent_id =  $request->parent_id;
        $category->sort_order = $request->sort_order;
        $category->level = 1;
        $category->status = $request->status;
        $category->updated_at = date('Y-m-d H:i:s');

        $category->updated_by =   Auth::guard('admin')->user()->id;
        if ($category->status == 2) {
            $category->products()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
            $category->menus()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
        }
        // dd($category);
        // upload file
        if ($request->has('image')) {
            $path_dir = "images/category/";
            if (File::exists(public_path($path_dir . $category->image))) {
                File::delete(public_path($path_dir . $category->image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $category->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $category->image = $filename;
        }
        // end
        if ($category->save()) {
            $link = Link::where([['type', '=', 'category'], ['table_id', '=', $id]])->first();
            $link->link = $category->slug;

            $link->save();
            return redirect()->route('category.index')->with('message', ['type' => 'success', 'msg' => 'Cập nhật sản phẩm thành công']);
        } else {
            return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
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
        $category = Category::find($id);
        $path_dir = "images/category/";
        $path_image_delete = public_path($path_dir . $category->image);
        if ($category == null) {
            return redirect()->route('category.trash')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        }
        $category->products()->update([
            'status' => 0,
            'updated_by' =>  Auth::guard('admin')->user()->id
        ]);
        $category->menus()->delete();

        if ($category->delete()) {
            if (File::exists($path_image_delete)) {
                File::delete($path_image_delete);
            }
            $link = Link::where(
                [['type', '=', 'category'], ['table_id', '=', $id]]
            )->first();
            $link->delete();
            return redirect()->route('category.trash')->with('message', ['type' => 'success', 'msg' => 'Xóa sản phẩm thành công']);
        } else {
            return redirect()->route('category.trash')->with('message', ['type' => 'danger', 'msg' => 'Xóa sản phẩm không thành công']);
        }
    }

    #delete
    public function delete($id, Request $request)
    {

        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $category->status = 0;
            $category->products()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
            $category->menus()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
            $category->updated_at = date('Y-m-d H:i:s');
            $category->updated_by =   Auth::guard('admin')->user()->id;
            $category->save();
            return redirect()->route('category.index')->with('message', ['type' => 'success', 'msg' => 'Chuyển vào thùng rác thành công']);
        }
    }
    #restore
    public function restore($id, Request $request)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $category->status = 2;
            $category->updated_at = date('Y-m-d H:i:s');
            $category->updated_by =   Auth::guard('admin')->user()->id;
            $category->save();
            return redirect()->route('category.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục sản phẩm thành công']);
        }
    }
    #status
    public function status($id, Request $request)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $category->status = ($category->status == 1) ? 2 : 1;
            if ($category->status == 2) {
                $category->products()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $category->menus()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
            }
            $category->updated_at = date('Y-m-d H:i:s');
            $category->updated_by =   Auth::guard('admin')->user()->id;
            $category->save();
            return redirect()->route('category.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
        }
    }
    // trash
    public function trash()
    {
        $title = 'Thùng rác danh mục';
        $list_category = Category::where('status',  '=', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.category.trash", compact('list_category', 'title'));
    }
    // delete-multi
    public function deleteAll(Request $request)
    {

        if (isset($request->checkId)) {
            $list_id = $request->input('checkId');

            $count_max = sizeof($list_id);
            $count = 0;
            foreach ($list_id as $id) {
                $category = Category::find($id);
                if ($category == null) {
                    return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                }
                $category->status = 0;
                $category->products()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $category->menus()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $category->updated_at = date('Y-m-d H:i:s');
                $category->updated_by =  Auth::guard('admin')->user()->id;
                $category->save();
                if ($category->status == 0) {
                    $category->products()->update(['status' => 2]);
                }
                $count++;
            }
            return redirect()->route('category.index')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& Vào thùng rác để xem!!!"]);
        } else {
            return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
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
                    $category = Category::find($list);
                    if ($category == null) {
                        return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $path_dir = "images/category/";
                    $path_image_delete = public_path($path_dir . $category->image);
                    $category->products()->update([
                        'status' => 0,
                        'updated_by' =>  Auth::guard('admin')->user()->id
                    ]);
                    $category->menus()->delete();
                    if ($category->delete()) {
                        if (File::exists($path_image_delete)) {
                            File::delete($path_image_delete);
                        }
                        $link = Link::where(
                            [['type', '=', 'category'], ['table_id', '=', $list]]
                        )->first();
                        $link->delete();
                    }
                    $count++;
                }
                return redirect()->route('category.trash')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('category.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
        if (isset($request['RESTORE_ALL'])) {
            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;

                foreach ($list_id as $id) {
                    $category = Category::find($id);
                    if ($category == null) {
                        return redirect()->route('category.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $category->status = 2;
                    $category->updated_at = date('Y-m-d H:i:s');
                    $category->updated_by =  Auth::guard('admin')->user()->id;
                    $category->save();
                    $count++;
                }
                return redirect()->route('category.trash')->with('message', ['type' => 'success', 'msg' => "Khôi phục thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('category.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
    }
}

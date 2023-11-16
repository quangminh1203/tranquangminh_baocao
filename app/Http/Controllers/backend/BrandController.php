<?php

namespace App\Http\Controllers\backend;

use App\Models\Link;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả thương hiệu';
        $list_brand = Brand::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();        
        return view("backend.brand.index", compact('list_brand', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm thương hiệu';
        $list_brand = Brand::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        $html_sort_order = "";
        foreach ($list_brand as $brand) {
            if ($brand->sort_order + 1 == old('sort_order')) {
                $html_sort_order .= "<option selected value='" . ($brand->sort_order + 1) . "'>Sau: " . $brand->name . "</option>";
            } else {
                $html_sort_order .= "<option value='" . ($brand->sort_order + 1) . "'>Sau: " . $brand->name . "</option>";
            }
        }
        return view('backend.brand.create', compact('html_sort_order', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name, '-');
        $brand->metakey = $request->metakey;
        $brand->metadesc = $request->metadesc;

        $brand->sort_order = $request->sort_order;
        $brand->status = $request->status;
        $brand->created_at = date('Y-m-d H:i:s');
        $brand->created_by =  Auth::guard('admin')->user()->id;
        // upload file
        if ($request->has('image')) {
            $path_dir = "images/brand/";
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $brand->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $brand->image = $filename;
        }
        // end
        if ($brand->save()) {
            $link = new Link();
            $link->link = $brand->slug;
            $link->table_id = $brand->id;
            $link->type = 'brand';
            $link->save();
            return redirect()->route('brand.index')->with('message', ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công']);
        } else {
            return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
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
        $title = 'Thông tin thương hiệu';
        $total = Brand::join('product', 'product.brand_id', '=', 'brand.id')
            ->where('brand.id', '=', $id)
            ->distinct()
            ->count();

        $total_sale = Brand::join('product', 'product.brand_id', '=', 'brand.id')
            ->join('product_sale', 'product_sale.product_id', '=', 'product.id')
            ->where('product_sale.price_sale', '>', '0')
            ->where('brand.id', '=', $id)
            ->distinct()
            ->count();
        $product_brand = Brand::join('product', 'product.brand_id', '=', 'brand.id')
            ->join('product_image', 'product_image.product_id', '=', 'product.id')
            ->select('product.*', 'product.name as product_name', 'product.id as product_id')
            ->select('product_image.*', 'product_image.image as image')
            ->where('brand.id', '=', $id)
            ->orderBy('product.created_at', 'desc')
            ->distinct()
            ->get();

        $brand = Brand::where('brand.id', '=', $id)
            ->select(
                "*",
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "brand.updated_by")->toSql() . ") as updated_name"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "brand.created_by")->toSql() . ") as created_name")
            )
            ->first();
        if ($brand == null) {
            return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            return view('backend.brand.show', compact('brand', 'total', 'total_sale', 'product_brand', 'title'));
        }
    }


    public function edit($id)
    {

        $brand = Brand::find($id);
        $title = 'Sửa thương hiệu';
        $list_brand = Brand::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();

        $html_sort_order = "";
        foreach ($list_brand as $item) {
            if ($item->id != $id) {
                if ($item->sort_order + 1 == $brand->sort_order) {
                    $html_sort_order .= "<option selected value='" . ($brand->sort_order + 1) . "'>Sau: " . $brand->name . "</option>";
                } else {
                    $html_sort_order .= "<option value='" . ($brand->sort_order + 1) . "'>Sau: " . $brand->name . "</option>";
                }
            }
        }

        return view('backend.brand.edit', compact('brand',  'html_sort_order', 'title'));
    }


    public function update(UpdateBrandRequest $request, $id)
    {

        $brand = Brand::find($id);
        $request->validate([
            'name' => 'unique:brand,name,' . $id . ',id'
        ], [
            'name.unique' => 'Tên đã được sử dụng, vui lòng sử dụng một tên khác',
        ]);
        $brand->name = $request->name;

        $brand->slug = Str::slug($request->name, '-');
        $brand->metakey = $request->metakey;
        $brand->metadesc = $request->metadesc;

        $brand->sort_order = $request->sort_order;

        $brand->status = $request->status;
        $brand->updated_at = date('Y-m-d H:i:s');

        $brand->updated_by =  Auth::guard('admin')->user()->id;


        // upload file
        if ($brand->status == 2) {
            $brand->products()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
            $brand->menus()->update([
                'status' => 2,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
        }

        if ($request->has('image')) {
            $path_dir = "images/brand/";
            if (File::exists(public_path($path_dir . $brand->image))) {
                File::delete(public_path($path_dir . $brand->image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $brand->slug . '.' . $extension;
            $file->move($path_dir, $filename);
            $brand->image = $filename;
        }
        // end
        if ($brand->save()) {
            $link = Link::where([['type', '=', 'brand'], ['table_id', '=', $id]])->first();
            $link->link = $brand->slug;

            $link->save();
            return redirect()->route('brand.index')->with('message', ['type' => 'success', 'msg' => 'Cập nhật sản phẩm thành công']);
        } else {
            return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => 'Thêm sản phẩm không thành công']);
        }
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);
        $path_dir = "images/brand/";
        $path_image_delete = public_path($path_dir . $brand->image);
        if ($brand == null) {
            return redirect()->route('brand.trash')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        }


        if ($brand->delete()) {
            if (File::exists($path_image_delete)) {
                File::delete($path_image_delete);
            }
            $brand->products()->update([
                'status' => 0,
                'updated_by' =>  Auth::guard('admin')->user()->id
            ]);
            $link = Link::where(
                [['type', '=', 'brand'], ['table_id', '=', $id]]
            )->first();
            $brand->menus()->delete();
            $link->delete();
            return redirect()->route('brand.trash')->with('message', ['type' => 'success', 'msg' => 'Xóa sản phẩm thành công']);
        } else {
            return redirect()->route('brand.trash')->with('message', ['type' => 'danger', 'msg' => 'Xóa sản phẩm không thành công']);
        }
    }
    // delete
    public function delete($id)
    {
        $brand = Brand::find($id);
        if ($brand == null) {
            return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            $brand->status = 0;
            $brand->updated_at = date('Y-m-d H:i:m');
            $brand->updated_by =  Auth::guard('admin')->user()->id;
            $brand->save();
            if ($brand->status == 0) {
                $brand->products()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $brand->menus()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
            }
            return redirect()->route('brand.index')->with('message', ['type' => 'success', 'msg' => 'Xóa sản phẩm thành công']);
        }
    }
    // trash
    public function trash()
    {
        $title = 'Thùng rác thương hiệu';
        $list_brand = Brand::where('status',  '=', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.brand.trash", compact('list_brand', 'title'));
    }
    // status
    public function status($id)
    {
        $brand = Brand::find($id);
        if ($brand == null) {
            return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            $brand->status = ($brand->status == 1) ? 2 : 1;
            $brand->updated_at = date('Y-m-d H:i:m');
            $brand->updated_by =  Auth::guard('admin')->user()->id;;
            $brand->save();
            if ($brand->status == 2) {
                $brand->products()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $brand->menus()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
            }
            return redirect()->route('brand.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
        }
    }
    // restore
    public function restore($id)
    {
        $brand = Brand::find($id);
        if ($brand == null) {
            return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            $brand->status = 2;
            $brand->updated_at = date('Y-m-d H:i:m');
            $brand->updated_by =  Auth::guard('admin')->user()->id;;
            $brand->save();
            return redirect()->route('brand.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục sản phâm thành công']);
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
                $brand = Brand::find($id);
                if ($brand == null) {
                    return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                }
                $brand->status = 0;
                $brand->products()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $brand->menus()->update([
                    'status' => 2,
                    'updated_by' =>  Auth::guard('admin')->user()->id
                ]);
                $brand->updated_at = date('Y-m-d H:i:s');
                $brand->updated_by =  Auth::guard('admin')->user()->id;
                $brand->save();
                if ($brand->status == 0) {
                    $brand->products()->update(['status' => 2]);
                }
                $count++;
            }
            return redirect()->route('brand.index')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& Vào thùng rác để xem!!!"]);
        } else {
            return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
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
                    $brand = Brand::find($list);
                    if ($brand == null) {
                        return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $path_dir = "images/brand/";
                    $path_image_delete = public_path($path_dir . $brand->image);
                    $brand->products()->update([
                        'status' => 0,
                        'updated_by' =>  Auth::guard('admin')->user()->id
                    ]);
                    $brand->menus()->delete();
                    if ($brand->delete()) {
                        if (File::exists($path_image_delete)) {
                            File::delete($path_image_delete);
                        }
                        $link = Link::where(
                            [['type', '=', 'brand'], ['table_id', '=', $list]]
                        )->first();
                        $link->delete();
                    }
                    $count++;
                }
                return redirect()->route('brand.trash')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('brand.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
        if (isset($request['RESTORE_ALL'])) {
            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;

                foreach ($list_id as $id) {
                    $brand = Brand::find($id);
                    if ($brand == null) {
                        return redirect()->route('brand.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                    }
                    $brand->status = 2;
                    $brand->updated_at = date('Y-m-d H:i:s');
                    $brand->updated_by =  Auth::guard('admin')->user()->id;
                    $brand->save();
                    $count++;
                }
                return redirect()->route('brand.trash')->with('message', ['type' => 'success', 'msg' => "Khôi phục thành công $count/$count_max !&& sản phẩm!!!"]);
            } else {
                return redirect()->route('brand.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
    }
}

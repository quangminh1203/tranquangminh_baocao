<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSale;
use App\Models\ProductStore;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả sản phẩm';

        $list_product = Product::with('images')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->join('brand', 'product.brand_id', '=', 'brand.id')
            ->select('product.*', 'category.name as category_name', 'brand.name as brand_name')
            ->where('product.status', '<>', '0')
            ->orderBy('created_at', 'desc')
            ->get();

        return view("backend.product.index", compact('list_product', 'title',));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm sản phẩm';
        $list_category = Category::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        $list_brand = Brand::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        $html_category_id = "";
        $html_brand_id = "";

        foreach ($list_category as $item) {
            if ($item->id == old('category_id')) {
                $html_category_id .= "<option selected  value='" . $item->id . "'>" . $item->name . "</option>";
            } else {
                $html_category_id .= "<option value='" . $item->id . "'>" . $item->name . "</option>";
            }
        }
        foreach ($list_brand as $item) {
            if ($item->id == old('brand_id')) {
                $html_brand_id .= "<option selected  value='" . $item->id . "'>" . $item->name . "</option>";
            } else {
                $html_brand_id .= "<option value='" . $item->id . "'>" . $item->name . "</option>";
            }
        }
        return view('backend.product.create', compact('html_category_id', 'html_brand_id',  'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = new Product();
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name, '-') . '_' .  date('Y-m-d');
        $product->price_buy = $request->price_buy;
        $product->detail = $request->detail;
        $product->metakey = $request->metakey;
        $product->metadesc = $request->metadesc;
        $product->status = $request->status;
        $product->created_at = date('Y-m-d H:i:s');
        $product->created_by =   Auth::guard('admin')->user()->id;
        // dd($product);
        if ($product->save()) {
            // // upload file
            if ($request->has('image')) {
                $count = 1;
                $path_dir = "images/product/";
                $array_file = $request->file('image');
                foreach ($array_file as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $product->slug . '_' . $count . '_' .  $product->id . '.' . $extension;
                    $file->move($path_dir, $filename);
                    $product_image = new ProductImage();
                    $product_image->product_id = $product->id;
                    $product_image->ordinal_number = $count;
                    $product_image->image = $filename;
                    $product_image->save();
                    $count++;
                }
            }
            // sale
            $product_sale = new ProductSale();
            if ($request->filled('price_sale')) {
                $product_sale->price_sale = $request->price_sale;
                $product_sale->date_begin = $request->date_begin;
                $product_sale->date_end = $request->date_end;
            }
            $product->sale()->save($product_sale);
            // store
            if (strlen($request->price) && strlen($request->qty)) {
                $product_store = new ProductStore();

                $product_store->price = $request->price;
                $product_store->qty = $request->qty;
                $product_store->created_at = date('Y-m-d H:i:s');
                $product_store->created_by =   Auth::guard('admin')->user()->id;
            }
            $product->sale()->save($product_store);
        }
        return redirect()->route('product.index')->with('message', ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công']);
    }

    public function show($id)
    {

        $title = 'Chi tiết sản phẩm';
        $product = Product::where('product.id', $id)
            ->join('product_sale', 'product.id', '=', 'product_sale.product_id')
            ->join('product_store', 'product.id', '=', 'product_store.product_id')
            ->select(
                "product.*",
                "product_sale.price_sale",
                "product_sale.date_begin",
                "product_sale.date_end",
                "product_store.price",
                "product_store.qty",
                "product_store.created_at as created_at_ps",
                "product_store.updated_at as updated_at_ps",
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "product.updated_by")->toSql() . ") as updated_name"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "product.created_by")->toSql() . ") as created_name"),
                DB::raw("(" . Brand::select("name")->whereColumn("brand.id", "=", "product.brand_id")->toSql() . ") as brand_name"),
                DB::raw("(" . Category::select("name")->whereColumn("category.id", "=", "product.category_id")->toSql() . ") as category_name"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "product_store.updated_by")->toSql() . ") as updated_name_ps"),
                DB::raw("(" . User::select("name")->whereColumn("user.id", "=", "product_store.created_by")->toSql() . ") as created_name_ps"),
            )

            ->first();
        // dd($product);
        $list_image = ProductImage::where('product_image.product_id', '=', $id)->orderBy('ordinal_number', 'ASC')->get();

        if ($product == null) {
            return redirect()->route('product.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            return view("backend.product.show", compact('product', 'title', 'list_image',));
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
        $product = Product::where('product.id', $id)
            ->join('product_sale', 'product.id', '=', 'product_sale.product_id')
            ->join('product_store', 'product.id', '=', 'product_store.product_id')
            ->select(
                "product.*",
                "product_sale.price_sale",
                "product_sale.date_begin",
                "product_sale.date_end",
                "product_store.price",
                "product_store.qty",
            )

            ->first();


        $title = 'Cập nhật sản phẩm';
        $list_category = Category::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        $list_brand = Brand::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        $html_category_id = "";
        $html_brand_id = "";

        foreach ($list_category as $item) {
            if ($item->id == $product->category_id) {
                $html_category_id .= "<option selected  value='" . $item->id . "'>" . $item->name . "</option>";
            } else {
                $html_category_id .= "<option value='" . $item->id . "'>" . $item->name . "</option>";
            }
        }
        foreach ($list_brand as $item) {
            if ($item->id == $product->brand_id) {
                $html_brand_id .= "<option selected  value='" . $item->id . "'>" . $item->name . "</option>";
            } else {
                $html_brand_id .= "<option value='" . $item->id . "'>" . $item->name . "</option>";
            }
        }
        return view('backend.product.edit', compact('product', 'html_category_id', 'html_brand_id',  'title',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {

        $product = Product::find($id);
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name, '-') . '_' . date('Y-m-d');
        $product->price_buy = $request->price_buy;
        $product->detail = $request->detail;
        $product->metakey = $request->metakey;
        $product->metadesc = $request->metadesc;
        $product->status = $request->status;
        $product->updated_at = date('Y-m-d H:i:s');
        $product->updated_by =   Auth::guard('admin')->user()->id;

        if ($product->save()) {
            // // upload file
            // if ($request->has('image')) {
            //     $array_file = $request->file('image');
            //     $list_images = ProductImage::where('product_image.product_id', '=', $id)->get();
            //     $count = 1;
            //     $path_dir = "images/product/";
            //     $array_file = $request->file('image');
            //     foreach ($list_images as $list_images) {
            //         $list_images->delete();
            //         if (File::exists(public_path($path_dir . $list_images->image))) {
            //             File::delete(public_path($path_dir . $list_images->image));
            //         }
            //     }

            //     foreach ($array_file as $file) {
            //         $extension = $file->getClientOriginalExtension();
            //         $filename = $product->slug . '_' . $count . '_' .  $product->id . '.' . $extension;
            //         $file->move($path_dir, $filename);
            //         $product_image = new ProductImage();
            //         $product_image->product_id = $product->id;
            //         $product_image->ordinal_number = $count;
            //         $product_image->image = $filename;
            //         $product_image->save();
            //         $count++;
            //     }
            // }
            // sale
            if (strlen($request->price_sale) && strlen($request->date_begin) && strlen($request->date_end)) {
                $product_sale = ProductSale::where('product_id', '=', $id)->first();
                $product_sale->price_sale = $request->price_sale;
                $product_sale->date_begin = $request->date_begin;
                $product_sale->date_end = $request->date_end;
                $product_sale->save();
            }
            // store
            if (strlen($request->price) && strlen($request->qty)) {
                $product_store = ProductStore::where('product_id', '=', $id)->first();

                $product_store->price = $request->price;
                $product_store->qty = $request->qty;
                $product_store->updated_at = date('Y-m-d H:i:s');
                $product_store->updated_by =   Auth::guard('admin')->user()->id;
                $product_store->save();
            }
        }
        return redirect()->route('product.index')->with('message', ['type' => 'success', 'msg' => 'Sửa sản phẩm thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product == null) {
            return redirect()->route('product.trash')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        }
        if ($product->delete()) {
            // image
            // $product_images = ProductImage::where('product_id', $id)->get();
            // $path_dir = "images/product/";
            // foreach ($product_images as $product_image) {
            //     $path_image_delete = public_path($path_dir . $product_image->image);
            //     if (File::exists($path_image_delete)) {
            //         File::delete($path_image_delete);
            //     }
            //     $product_image->delete();
            // }
            // sale
            $product_sale = ProductSale::where('product_id', $id)->first();
            if ($product_sale) {
                $product_sale->delete();
            }
            // store
            $product_store = ProductStore::where('product_id', $id)->first();
            if ($product_store) {
                $product_store->delete();
            }
            return redirect()->route('product.trash')->with('message', ['type' => 'success', 'msg' => 'Xóa sản phẩm thành công']);
        } else {
            return redirect()->route('product.trash')->with('message', ['type' => 'danger', 'msg' => 'Xóa sản phẩm không thành công']);
        }
    }
    // delete
    public function delete($id)
    {
        $product = Product::find($id);
        if ($product == null) {
            return redirect()->route('product.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            $product->status = 0;
            $product->updated_at = date('Y-m-d H:i:m');
            $product->updated_by = 1;
            $product->save();
            return redirect()->route('product.index')->with('message', ['type' => 'success', 'msg' => 'Xóa sản phẩm thành công thành công']);
        }
    }
    // restore
    public function restore($id)
    {
        $product = Product::find($id);
        if ($product == null) {
            return redirect()->route('product.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            $product->status = 2;
            $product->updated_at = date('Y-m-d H:i:m');
            $product->updated_by = 1;
            $product->save();
            return redirect()->route('product.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục sản phẩm thành công']);
        }
    }
    // status
    public function status($id)
    {
        $product = Product::find($id);
        if ($product == null) {
            return redirect()->route('product.index')->with('message', ['type' => 'danger', 'msg' => 'Sản phẩm không tồn tại']);
        } else {
            $product->status = ($product->status == 1) ? 2 : 1;
            $product->updated_at = date('Y-m-d H:i:m');
            $product->updated_by = 1;
            $product->save();
            return redirect()->route('product.index')->with('message', ['type' => 'success', 'msg' => 'Thay đổi trạng thái thành công']);
        }
    }

    // trash
    public function trash()
    {
        $title = 'Thùng rác sản phẩm';
        $list_product = Product::with('images')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->join('brand', 'product.brand_id', '=', 'brand.id')
            ->select('product.*', 'category.name as category_name', 'brand.name as brand_name')
            ->where('product.status', '=', '0')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($product) {
                $product->image = $product->images->first()->image;
                return $product;
            });
        return view("backend.product.trash", compact('list_product', 'title'));
    }
    public function deleteAll(Request $request)
    {
        if (isset($request->checkId)) {
            $list_id = $request->input('checkId');
            $count_max = sizeof($list_id);
            $count = 0;
            foreach ($list_id as $list) {
                $product = Product::find($list);
                if ($product == null) {
                    return redirect()->route('product.index')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!Đã xóa $count/$count_max ! "]);
                }
                $product->status = 0;
                $product->updated_at = date('Y-m-d H:i:s');
                $product->updated_by =  Auth::guard('admin')->user()->id;
                $product->save();
                $count++;
            }
            return redirect()->route('product.index')->with('message', ['type' => 'success', 'msg' => "Xóa thành công $count/$count_max !&& Vào thùng rác để xem!!!"]);
        } else {
            return redirect()->route('product.index')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
        }
    }
    public function trashAll(Request $request)
    {
        $path = 'images/product/';

        if (isset($request['DELETE_ALL'])) {
            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;
                foreach ($list_id as $list) {
                    $product = Product::find($list);
                    if ($product == null) {
                        return redirect()->route('product.trash')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!&&Đã xóa $count/$count_max !"]);
                    }
                    if ($product->delete()) {
                        // sale
                        $product_sale = ProductSale::where('product_id', $list)->first();
                        if ($product_sale) {
                            $product_sale->delete();
                        }
                        // store
                        $product_store = ProductStore::where('product_id', $list)->first();
                        if ($product_store) {
                            $product_store->delete();
                        }
                        $count++;
                    }
                }
                return redirect()->route('product.trash')->with('message', ['type' => 'success', 'msg' => "Xóa vĩnh viễn thành công $count/$count_max !"]);
            } else {
                return redirect()->route('product.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
        if (isset($request['RESTORE_ALL'])) {
            if (isset($request->checkId)) {
                $list_id = $request->input('checkId');
                $count_max = sizeof($list_id);
                $count = 0;
                foreach ($list_id as $list) {
                    $product = Product::find($list);
                    if ($product == null) {
                        return redirect()->route('product.trash')->with('message', ['type' => 'danger', 'msg' => "Có mẫu tin không tồn tại!&&Đã phục hồi $count/$count_max !"]);
                    }

                    $product->status = 2;
                    $product->updated_at = date('Y-m-d H:i:s');
                    $product->updated_by =  Auth::guard('admin')->user()->id;
                    $product->save();
                    $count++;
                }
                return redirect()->route('product.index')->with('message', ['type' => 'success', 'msg' => "Phục hồi thành công $count/$count_max !"]);
            } else {
                return redirect()->route('product.trash')->with('message', ['type' => 'danger', 'msg' => 'Chưa chọn mẫu tin!']);
            }
        }
    }

    public function image($id)
    {
        $title = 'Hình ảnh';
        $product = Product::find($id);
        $image = ProductImage::where('product_id', $id)->get();
       
        // dd($image);
        return view('backend.product.image', compact('product', 'image',  'title'));
    }

    public function imageUpload(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|array',
            'image.*' => 'image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ], [
            'image.required' => 'Hình ảnh không được để trống.',
            'image.min' => 'Bạn cần tải lên ít nhất 2 hình ảnh.',
            'image.*.image' => 'Tập tin phải là hình ảnh.',
            'image.*.mimes' => 'Hình ảnh phải có định dạng :mimes.',
            'image.*.max' => 'Kích thước hình ảnh tối đa là 2048KB.',


        ]);
        $product = Product::find($id);
        // dd($product->images);
        $max = ProductImage::where('product_id', $id)->max('ordinal_number');
        // dd($max);
        if ($request->has('image')) {
            $count = $max + 1;
            $path_dir = "images/product/";
            $array_file = $request->file('image');
            foreach ($array_file as $file) {
                $extension = $file->getClientOriginalExtension();
                $filename = $product->slug . '_' . $count . '_' .  $product->id . '.' . $extension;
                $file->move($path_dir, $filename);
                $product_image = new ProductImage();
                $product_image->product_id = $product->id;
                $product_image->ordinal_number = $count;
                $product_image->image = $filename;
                // dd($product_image);
                $product_image->save();
                $count++;
            }
        }
        return redirect()->route('product.index')->with('message', ['type' => 'success', 'msg' => 'Thêm hình ảnh thành công']);
    }
    public function imageDelete(Request $request)
    {

        $path_dir = "images/product/";
        $imageId = $request->input('img_id');
        $prodID = $request->input('prod_id');
        $count = ProductImage::where('product_id', $prodID)->count();

        if ($count >= 2) {
            if (ProductImage::where('id', $imageId)) {
                $imageItem = ProductImage::where('id', $imageId)->first();
                // dd($imageItem);
                $path_image_delete = public_path($path_dir . $imageItem->image);
                File::delete($path_image_delete);
                $imageItem->delete();
                return response()->json(['status' => 'Image delete successfully']);
            } else {
                return response()->json(['status' => ' Không tồn tại hình ảnh']);
            }
        } else {
            return response()->json(['status' => ' Số lượng hình ảnh không thể ít hơn 1']);
        }
    }
}

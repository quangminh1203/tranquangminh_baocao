<?php

namespace App\Http\Controllers\frontend;

use App\Models\Link;
use App\Models\Post;
use App\Models\Brand;
use App\Models\Topic;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;

class SiteController extends Controller
{
    public function index($slug = null)
    {
        if ($slug == null) {
            return  $this->home();
        } else {
            $link = Link::where('link', '=', $slug)->first();
            // var_dump($slug);
            if ($link != null) {
                $type = $link->type;
                switch ($type) {
                    case 'category': {
                            return $this->ProductCategory($slug);
                        }
                    case 'brand': {
                            return $this->ProductBrand($slug);
                        }
                    case 'topic': {
                            return $this->PostTopic($slug);
                        }
                    case 'page': {
                            return $this->PostPage($slug);
                        }
                }
            } else {
                $product = Product::where([['slug', '=', $slug], ['status', '=', 1]])->first();
                if ($product != null) {
                    return $this->ProductDetail($product);
                } else {
                    $post = Post::where([['slug', '=', $slug], ['status', '=', 1], ['type', '=', 'post']])->first();
                    if ($post != null) {
                        return $this->PostDetail($post);
                    } else {
                        return $this->error_404($slug);
                    }
                }
            }
            // return view('frontend.site');
        }
    }
    #home
    private function home()
    {
        $title = 'Trang chủ';
        $list_category = Category::where([
            ['status', '=', '1'],
            ['parent_id', '=', '0']
        ])->orderBy('sort_order', 'desc')->get();
        $min_price = Product::min('price_buy');
        $max_price = Product::max('price_buy');
        $max_price_range = $max_price + 100000;
        return view('frontend.home', compact('list_category', 'title', 'min_price', 'max_price', 'max_price_range'));
    }
    #product - all
    public function product()
    {
        $title = 'Tất cả sản phẩm';
        if (isset($_GET['sort_by'])) {
            $sort_by = $_GET['sort_by'];
            if ($sort_by == 'az') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where('status', '=', '1')->orderBy('name', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'za') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where('status', '=', '1')->orderBy('name', 'DESC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'LowToHigh') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where('status', '=', '1')->orderBy('price_buy', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'HighToLow') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where('status', '=', '1')->orderBy('price_buy', 'DESC')->paginate(12)->appends(request()->query());
            }
        } else if (isset($_GET['start_price']) && $_GET['end_price']) {
            $min_price = $_GET['start_price'];
            $max_price = $_GET['end_price'];
            $list_product = Product::with(['sale' => function ($query) {
                $query->whereRaw('? between date_begin and date_end', [now()]);
            }])->where('status', '=', '1')->whereBetween('price_buy', [$min_price, $max_price])->orderBy('price_buy', 'ASC')->paginate(12)->appends(request()->query());
        } else {
            $list_product = Product::with(['sale' => function ($query) {
                $query->whereRaw('? between date_begin and date_end', [now()]);
            }])->where('status', '=', '1')->orderBy('created_at', 'desc')->paginate(12);
        }
        $min_price = Product::min('price_buy');
        $max_price = Product::max('price_buy');
        $max_price_range = $max_price + 100000;
        return view('frontend.product', compact('title', 'list_product', 'min_price', 'max_price', 'max_price_range'));
    }
    // sale
    public function productSale()
    {
        if (isset($_GET['sort_by'])) {
            $sort_by = $_GET['sort_by'];
            if ($sort_by == 'az') {
                $list_product = Product::with(['sale'])
                    ->whereHas('sale', function ($query) {
                        $query->whereNotNull('price_sale')->whereRaw('? between date_begin and date_end', [now()]);
                    })->where('status', '=', '1')->orderBy('name', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'za') {
                $list_product = Product::with(['sale'])
                    ->whereHas('sale', function ($query) {
                        $query->whereNotNull('price_sale')->whereRaw('? between date_begin and date_end', [now()]);
                    })->where('status', '=', '1')->orderBy('name', 'DESC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'LowToHigh') {
                $list_product = Product::with(['sale'])
                    ->whereHas('sale', function ($query) {
                        $query->whereNotNull('price_sale')->whereRaw('? between date_begin and date_end', [now()]);
                    })->where('status', '=', '1')->orderBy('price_buy', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'HighToLow') {
                $list_product = Product::with(['sale'])
                    ->whereHas('sale', function ($query) {
                        $query->whereNotNull('price_sale')->whereRaw('? between date_begin and date_end', [now()]);
                    })->where('status', '=', '1')->paginate(12)->appends(request()->query());
            }
        } else if (isset($_GET['start_price']) && $_GET['end_price']) {
            $min_price = $_GET['start_price'];
            $max_price = $_GET['end_price'];
            $list_product = Product::with(['sale'])
                ->whereHas('sale', function ($query) {
                    $query->whereNotNull('price_sale')->whereRaw('? between date_begin and date_end', [now()]);
                })
                ->where('status', '=', '1')->orderBy('created_at', 'desc')
                ->paginate(12);
        } else {
            $list_product = Product::with(['sale'])
                ->whereHas('sale', function ($query) {
                    $query->whereNotNull('price_sale')->whereRaw('? between date_begin and date_end', [now()]);
                })
                ->where('status', '=', '1')->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        $title = 'Sản phẩm khuyến mãi';

        $min_price = ProductSale::min('price_sale');
        $max_price = ProductSale::max('price_sale');
        $max_price_range = $max_price + 100000;
        return view('frontend.product-sale', compact('title', 'list_product', 'min_price', 'max_price', 'max_price_range'));
    }

    #category
    private function ProductCategory($slug)
    {

        $cat = Category::where([['slug', '=', $slug], ['status', '=', '1']])->first();

        $listcatid = array();
        array_push($listcatid, $cat->id);
        $list_category1 = Category::where([['parent_id', '=', $cat->id], ['status', '=', '1']])->orderBy('sort_order', 'asc')->get();
        if (count($list_category1) != 0) {
            foreach ($list_category1 as $cat1) {
                array_push($listcatid, $cat1->id);
                $list_category2 = Category::where([['parent_id', '=', $cat1->id], ['status', '=', '1']])->orderBy('sort_order', 'asc')->get();
                if (count($list_category2) != 0) {
                    foreach ($list_category2 as $cat2) {
                        array_push($listcatid, $cat2->id);
                    }
                }
            }
        }
        if (isset($_GET['sort_by'])) {
            $sort_by = $_GET['sort_by'];
            if ($sort_by == 'az') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where('status', '=', '1')->whereIn('category_id', $listcatid)->orderBy('name', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'za') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where('status', '=', '1')->whereIn('category_id', $listcatid)->orderBy('name', 'DESC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'LowToHigh') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where('status', '=', '1')->whereIn('category_id', $listcatid)->orderBy('price_buy', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'HighToLow') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where('status', '=', '1')->whereIn('category_id', $listcatid)->orderBy('price_buy', 'DESC')->paginate(12)->appends(request()->query());
            }
        } else if (isset($_GET['start_price']) && $_GET['end_price']) {
            $min_price = $_GET['start_price'];
            $max_price = $_GET['end_price'];
            $list_product = Product::with(['sale' => function ($query) {
                $query->whereRaw('? between date_begin and date_end', [now()]);
            }])->where('status', '=', '1')->whereIn('category_id', $listcatid)->whereBetween('price_buy', [$min_price, $max_price])->orderBy('price_buy', 'ASC')->paginate(12)->appends(request()->query());
        } else {
            $list_product = Product::with(['sale' => function ($query) {
                $query->whereRaw('? between date_begin and date_end', [now()]);
            }])->where('status', '=', '1')->whereIn('category_id', $listcatid)->orderBy('created_at', 'desc')->paginate(12);
        }
        $min_price = Product::min('price_buy');
        $max_price = Product::max('price_buy');
        $max_price_range = $max_price + 100000;
        return view('frontend.product-category', compact('cat', 'list_product', 'min_price', 'max_price', 'max_price_range'));
    }
    #Detail
    private function ProductDetail($product)
    {
        // dd($product->images->toArray());
        $cat = $product->category_id;

        $listcatid = array();
        array_push($listcatid,  $cat);
        $list_category1 = Category::where([['parent_id', '=',  $cat], ['status', '=', '1']])->orderBy('sort_order', 'asc')->get();
        if (count($list_category1) != 0) {
            foreach ($list_category1 as $cat1) {
                array_push($listcatid, $cat1->id);
                $list_category2 = Category::where([['parent_id', '=', $cat1->id], ['status', '=', '1']])->orderBy('sort_order', 'asc')->get();
                if (count($list_category2) != 0) {
                    foreach ($list_category2 as $cat2) {
                        array_push($listcatid, $cat2->id);
                    }
                }
            }
        }

        $list_product = Product::with(['sale' => function ($query) {
            $query->whereRaw('? between date_begin and date_end', [now()]);
        }])->where([['status', '=', '1'], ['id', '<>', $product->id]])->whereIn('category_id', $listcatid)->orderBy('created_at', 'desc')->paginate(5);

        $product_sale = Product::with(['sale' => function ($query) {
            $query->whereRaw('? between date_begin and date_end', [now()]);
        }])->find($product->id);


        $type = $product->type;

        $list_comment = Comment::with(['product', 'user'])
            ->where('type', '=', 'product')
            ->where('table_id', '=', $product->id)
            ->where('parent_id', '=', 0)
            ->orderBy('created_at', 'DESC')
            ->paginate(7);
        $list_image = ProductImage::where('product_id', $product->id)->get();
        // dd($list_image);
        return view('frontend.product-detail', compact('list_comment',  'product', 'list_product', 'product_sale', 'type'));
    }
    #Brand
    private function ProductBrand($slug)
    {
        $brand = Brand::where([['slug', '=', $slug], ['status', '=', '1']])->first();
        if (isset($_GET['sort_by'])) {
            $sort_by = $_GET['sort_by'];
            if ($sort_by == 'az') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where([['status', '=', '1'], ['brand_id', '=', $brand->id]])->orderBy('name', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'za') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where([['status', '=', '1'], ['brand_id', '=', $brand->id]])->orderBy('name', 'DESC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'LowToHigh') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where([['status', '=', '1'], ['brand_id', '=', $brand->id]])->orderBy('price_buy', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'HighToLow') {
                $list_product = Product::with(['sale' => function ($query) {
                    $query->whereRaw('? between date_begin and date_end', [now()]);
                }])->where([['status', '=', '1'], ['brand_id', '=', $brand->id]])->orderBy('price_buy', 'DESC')->paginate(12)->appends(request()->query());
            }
        } else if (isset($_GET['start_price']) && $_GET['end_price']) {
            $min_price = $_GET['start_price'];
            $max_price = $_GET['end_price'];
            $max_price_range = $max_price + 100000;
            $list_product = Product::with(['sale' => function ($query) {
                $query->whereRaw('? between date_begin and date_end', [now()]);
            }])->where([['status', '=', '1'], ['brand_id', '=', $brand->id]])->whereBetween('price_buy', [$min_price, $max_price])->orderBy('price_buy', 'ASC')->paginate(12)->appends(request()->query());
        } else {
            $list_product = Product::with(['sale' => function ($query) {
                $query->whereRaw('? between date_begin and date_end', [now()]);
            }])->where([['status', '=', '1'], ['brand_id', '=', $brand->id]])->orderBy('created_at', 'desc')->paginate(12);
        }
        $min_price = Product::min('price_buy');
        $max_price = Product::max('price_buy');
        $max_price_range = $max_price + 100000;
        return view('frontend.product-brand', compact('brand', 'list_product', 'min_price', 'max_price', 'max_price_range'));
    }

    #pqge - all
    public function page()
    {
        $title = 'Tất cả bài viết';
        $list = Post::where([['status', '=', '1'], ['type', 'page']])->get();
        // dd($list->toArray());
        return view('frontend.page', compact('list', 'title'));
    }
    #post - all
    public function post()
    {
        $title = 'Tất cả bài viết';
        if (isset($_GET['sort_by'])) {
            $sort_by = $_GET['sort_by'];
            if ($sort_by == 'az') {
                $list = Post::where([['status', '=', '1'], ['type', 'post']])->orderBy('title', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'za') {
                $list = Post::where([['status', '=', '1'], ['type', 'post']])->orderBy('title', 'DESC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'OldToNew') {
                $list = Post::where([['status', '=', '1'], ['type', 'post']])->orderBy('created_at', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'NewToOld') {
                $list = Post::where([['status', '=', '1'], ['type', 'post']])->orderBy('created_at', 'DESC')->paginate(12)->appends(request()->query());
            }
        } else {
            $list = Post::where([['status', '=', '1'], ['type', 'post']])->orderBy('created_at', 'DESC')->paginate(12);
        }
        // dd($list->toArray());
        return view('frontend.post', compact('list', 'title'));
    }
    #Topic
    private function PostTopic($slug)
    {
        $topic = Topic::where([['slug', '=', $slug], ['status', '=', '1']])->first();

        $listtopic = array();
        array_push($listtopic, $topic->id);
        $list_topic1 = Topic::where([['parent_id', '=', $topic->id], ['status', '=', '1']])->orderBy('sort_order', 'asc')->get();
        if (count($list_topic1) != 0) {
            foreach ($list_topic1 as $topic1) {
                array_push($listtopic, $topic1->id);
                $list_topic2 = Topic::where([['parent_id', '=', $topic1->id], ['status', '=', '1']])->orderBy('sort_order', 'asc')->get();
                if (count($list_topic2) != 0) {
                    foreach ($list_topic2 as $topic2) {
                        array_push($listtopic, $topic2->id);
                    }
                }
            }
        }
        if (isset($_GET['sort_by'])) {
            $sort_by = $_GET['sort_by'];
            if ($sort_by == 'az') {
                $list = Post::where([['status', '=', '1'], ['type', 'post']])->whereIn('topic_id', $listtopic)->orderBy('title', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'za') {
                $list = Post::where([['status', '=', '1'], ['type', 'post']])->whereIn('topic_id', $listtopic)->orderBy('title', 'DESC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'OldToNew') {
                $list = Post::where([['status', '=', '1'], ['type', 'post']])->whereIn('topic_id', $listtopic)->orderBy('created_at', 'ASC')->paginate(12)->appends(request()->query());
            }
            if ($sort_by == 'NewToOld') {
                $list = Post::where([['status', '=', '1'], ['type', 'post']])->whereIn('topic_id', $listtopic)->orderBy('created_at', 'DESC')->paginate(12)->appends(request()->query());
            }
        } else {
            $list = Post::where([['status', '=', '1'], ['type', 'post']])->whereIn('topic_id', $listtopic)->orderBy('created_at', 'DESC')->paginate(12);
        }
        return view('frontend.post-topic', compact('list', 'topic'));
    }
    #page
    private function PostPage($slug)
    {

        return view('frontend.post-page');
    }
    #Detail
    private function PostDetail($post)
    {
        // $post = Post::where('id',$slug->id)->first();
        // dd($post->toArray());
        $list = Post::where([['status', 1], ['id', '<>', $post->id], ['topic_id', '=', $post->topic_id]])->take(6)->get();
        // dd($list);
        $list_comment = Comment::with(['post', 'user'])
            ->where('type', '=', 'post')
            ->where('table_id', '=', $post->id)
            ->where('parent_id', '=', 0)
            ->orderBy('created_at', 'DESC')
            ->paginate(7);
        $type = $post->type;
        return view('frontend.post-detail', compact('post', 'type', 'list_comment', 'list'));
    }
    #Error_404
    private function Error_404($slug)
    {
        return view('frontend.404');
    }
}

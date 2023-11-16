<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Product;
use App\Models\Category;

class ProductHome extends Component
{
    public $row_cate;

    public function __construct($rowcate)
    {
        $this->row_cate = $rowcate;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $cat = $this->row_cate;
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
        $count = Product::where('status', '=', '1')
            ->whereIn('category_id', $listcatid)->orderBy('created_at', 'desc')
            ->count();

        $list_product = Product::with(['sale' => function ($query) {
            $query->whereRaw('? between date_begin and date_end', [now()]);
        }])->where('status', '=', '1')->whereIn('category_id', $listcatid)->orderBy('created_at', 'desc')->take(12)->get();


        return view('components.product-home', compact('cat', 'list_product', 'count'));
    }
}

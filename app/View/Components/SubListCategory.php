<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;
class SubListCategory extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $row_cate;
    public function __construct($category)
    {
        $this->row_cate = $category;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
       
        $row_cate = $this->row_cate;
        $list2 = Category::where([['status', '=', '1'], ['parent_id', '=', $row_cate->id]])->orderBy('sort_order', 'desc')->get();
       
        return view('components.sub-list-category', compact('row_cate',  'list2'));
    }
}

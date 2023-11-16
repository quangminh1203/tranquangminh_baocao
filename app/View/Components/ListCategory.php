<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;

class ListCategory extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $title = 'Danh mục';
        $list = Category::where([['status', '=', '1'], ['parent_id', '=', '0']])->orderBy('sort_order', 'desc')->get();
        
       
        return view('components.list-category', compact('list', 'title'));
    }
}

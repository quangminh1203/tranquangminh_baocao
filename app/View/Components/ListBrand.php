<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Brand;
class ListBrand extends Component
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
        $title = 'Thương hiệu';
        $list = Brand::where([ ['status', '=', '1']])->orderBy('sort_order', 'desc')->get();
        return view('components.list-brand', compact('list', 'title'));

    }
}

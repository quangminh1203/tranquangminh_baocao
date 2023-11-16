<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\View\Component;

class NewProductHome extends Component
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
        $list_product = Product::with(['sale' => function ($query) {
            $query->whereRaw('? between date_begin and date_end', [now()]);
        }])->where('status', '=', '1')->orderBy('created_at', 'desc')
            ->take(12)->get();
        return view('components.new-product-home', compact('list_product'));
    }
}

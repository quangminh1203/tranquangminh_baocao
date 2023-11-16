<?php

namespace App\View\Components;

use App\Models\Product;
use App\Models\ProductSale;
use Illuminate\View\Component;

class SaleHome extends Component
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
        $count = Product::whereHas('sale', function ($query) {
            $query->where('price_sale', '<>', 'null')->whereRaw('? between date_begin and date_end', [now()]);
        })->where('status', '=', '1')
            ->count();
        $list_product = Product::whereHas('sale', function ($query) {
            $query->where('price_sale', '<>', 'null')->whereRaw('? between date_begin and date_end', [now()]);
        })->where('status', '=', '1')
        ->take(12)->get();
           
        return view('components.sale-home',compact('count', 'list_product'));
    }
}

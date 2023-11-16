<?php

namespace App\View\Components;

use App\Models\Menu;
use Illuminate\View\Component;

class FooterMenu extends Component
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
        $args = [
            ['position', '=', 'footermenu'],
            ['status', '=', '1'],
            ['parent_id', '=', '0']
        ];
        $list_menu = Menu::where($args)->orderBy('sort_order', 'ASC')->get();
        return view('components.footer-menu', compact('list_menu'));
    }
}

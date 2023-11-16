<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Menu;

class SubMainMenu extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $row_menu;
    public function __construct($rowmenu)
    {
        $this->row_menu = $rowmenu;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $row_menu = $this->row_menu;
        $args = [
            ['position', '=', 'mainmenu'],
            ['status', '=', '1'],
            ['parent_id', '=', $row_menu->id]
        ];
        $list_menu1 = Menu::where($args)->orderBy('sort_order', 'ASC')->get();
        $checksub = false;
        if (count($list_menu1) != 0)
            $checksub = true;
        return view('components.sub-main-menu', compact('row_menu', 'checksub', 'list_menu1'));
    }
}

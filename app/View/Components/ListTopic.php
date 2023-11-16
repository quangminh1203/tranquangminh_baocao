<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Topic;
class ListTopic extends Component
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
        $title = 'Chủ đề bài viết';
        $list = Topic::where([['parent_id', '=', '0'], ['status', '=', '1']])->orderBy('sort_order', 'desc')->get();
        return view('components.list-topic', compact('list', 'title'));
    }
}

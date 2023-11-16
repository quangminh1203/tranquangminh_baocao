<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;

class ListPostTopic extends Component
{
    public $list;

    public function __construct($rowpost)
    {
        $this->list = $rowpost;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $title = "Bài viết liên quan";
        $list = $this->list;  
        // dd($list->toArray());  
        return view('components.list-post-topic', compact('list', 'title'));
    }
}

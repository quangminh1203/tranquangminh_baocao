<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Topic;
class SubListTopic extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $row_topic;
    public function __construct($topic)
    {
        $this->row_topic = $topic;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        $row_topic = $this->row_topic;
        $list2 = Topic::where([['status', '=', '1'], ['parent_id', '=', $row_topic->id]])->orderBy('sort_order', 'desc')->get();
        return view('components.sub-list-topic', compact('list2', 'row_topic'));
    }
}

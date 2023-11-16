<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Comment;
class MainComment extends Component
{
    public $item;
    public function __construct($comment)
    {
        $this->item = $comment;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $item = $this->item;
        $args = [
           ['parent_id','=', $item->id],['type', '=', $item->type], ['table_id', '=', $item->table_id]
        ];
        $list_comment_1 = Comment::with(['product', 'user', 'post'])
            ->where($args)
            ->orderBy('created_at', 'ASC')
            ->paginate(7);
        $checksub = false;
        if (count($list_comment_1) != 0)
            $checksub = true;
        return view('components.main-comment', compact('list_comment_1', 'checksub'));
    }
}

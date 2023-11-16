<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $table_id = $request->input('table_id');
        $type = $request->input('type');
        if (Auth::guard('users')->check()) {
            if (isset($request['NEW'])) {
                $request->validate([
                    'body' => 'min:10'
                ], [
                    'body.min' => 'Nội dung gửi ít nhất :min ký tự',
                ]);
                $comment = new Comment();
                $comment->user_id =  Auth::guard('users')->user()->id;

                $comment->table_id = $table_id;
                $comment->body = $request->body;
                $comment->parent_id = 0;
                $comment->type = $type;
                $comment->created_at = Carbon::now()->format('H:i:s d-m-Y');
                $comment->save();
                return redirect()->back();
            } else {
                return redirect()->route('site.getlogin');
            }
        }
    }
    public function reply(Request $request)
    {
        if (Auth::guard('users')->check()) {
            $request->validate([
                'body1' => 'min:10'
            ], [
                'body1.min' => 'Nội dung gửi ít nhất :min ký tự',
            ]);
            $product_id = $request->input('product_id');
            $parent_id = $request->input('parent_id');
            $reply_id = $request->input('reply_id');
            $type = $request->input('type');

            $comment = new Comment();
            $comment->user_id = Auth::guard('users')->user()->id;
            $comment->table_id = $product_id;
            $comment->body = $request->body1;
            $comment->parent_id = $parent_id;
            $comment->reply_id = $reply_id;
            $comment->type = $type;
            $comment->created_at = date('Y-m-d H:i:s');
            $comment->save();
            return redirect()->back();
        } else {
            return redirect()->route('site.getlogin');
        }
    }
    public function replys(Request $request)
    {
        if (Auth::guard('users')->check()) {
            $product_id = $request->input('product_id');
            $parent_id = $request->input('parent_id');
            $reply_id = $request->input('reply_id');
            $type = $request->input('type');
            $request->validate([
                'body_1' => 'min:10'
            ], [
                'body_1.min' => 'Nội dung gửi ít nhất :min ký tự',
            ]);
            $comment = new Comment();
            $comment->user_id = Auth::guard('users')->user()->id;
            $comment->table_id = $product_id;
            $comment->body = $request->body_1;
            $comment->parent_id = $parent_id;
            $comment->reply_id = $reply_id;
            $comment->type = $type;
            $comment->created_at = date('Y-m-d H:i:s');
            $comment->save();
            return redirect()->back();
        } else {
            return redirect()->route('site.getlogin');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

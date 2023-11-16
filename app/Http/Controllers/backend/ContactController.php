<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tất cả liên hệ';
        $list_contact = Contact::where('status', '<>', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.contact.index", compact('list_contact', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);
        $title = 'Read Mail';
        return view("backend.contact.show", compact('contact', 'title'));
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
        $title = 'Reply Mail';
        $contact = Contact::find($id);
        return view("backend.contact.edit", compact('contact', 'title'));
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
        $contact = Contact::find($id);
        $request->validate([
            'reply_content' => ['required', 'not_in:null,false,0,'],
        ], [
            'reply_content.not_in' => 'Trường :attribute không được phép có giá trị :values.',
        ]);
        if ($contact == null) {
            return redirect()->route('contact.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $contact->replay_id = Auth::guard('admin')->user()->id;
            $contact->reply_content = $request->reply_content;
            $contact->status = 1;
            $contact->updated_by = $contact->replay_id;
            $contact->updated_at = date('Y-m-d H:i:s');
            $contact->save();
            return redirect()->route('contact.index')->with('message', ['type' => 'success', 'msg' => 'Tin nhắn đã được gửi thành công.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        if ($contact == null) {
            return redirect()->route('contact.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $contact->delete();
            return redirect()->route('contact.trash')->with('message', ['type' => 'success', 'msg' => 'Xóa tin nhắn thành công']);
        }
    }
    // delete
    public function delete($id, Request $request)
    {
        $contact = Contact::find($id);
        if ($contact == null) {
            return redirect()->route('contact.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $contact->status = 0;
            $contact->updated_at = date('Y-m-d H:i:s');
            $contact->updated_by =   Auth::guard('admin')->user()->id;
            $contact->save();
            return redirect()->route('contact.index')->with('message', ['type' => 'success', 'msg' => 'Xóa tin nhắn thành công']);
        }
    }
    // restore
    public function restore($id, Request $request)
    {
        $contact = Contact::find($id);
        if ($contact == null) {
            return redirect()->route('contact.index')->with('message', ['type' => 'danger', 'msg' => 'Mẫu tin không tồn tại']);
        } else {
            $contact->status = 2;
            $contact->updated_at = date('Y-m-d H:i:s');
            $contact->updated_by =   Auth::guard('admin')->user()->id;
            $contact->save();
            return redirect()->route('contact.trash')->with('message', ['type' => 'success', 'msg' => 'Khôi phục tin nhắn thành công']);
        }
    }
    // trash
    public function trash()
    {
        $title = 'Thùng rác liên hệ';
        $list_contact = Contact::where('status', '=', '0')->orderBy('created_at', 'desc')->get();
        return view("backend.contact.trash", compact('list_contact', 'title'));
    }
}

<?php

namespace App\Http\Controllers\frontend;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SiteContactController extends Controller
{
    public function sitecontact()
    {
        $title = 'Liên hệ';
        return view('frontend.contact', compact('title'));
    }

    public function contactadmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'email' => 'required|email|max:30',
            'phone' => 'required|min:10|max:12',
            'title' => 'required|max:255',
            'content' => 'required|max:255',
        ], [
            'name.required' => 'Tên là trường bắt buộc',
            'email.required' => 'Bạn chưa điền email',
            'email.email' => 'Email không hợp lệ',

            'name.max' => 'Tên không được dài quá :max ký tự',
            'email.max' => 'Email không được dài quá :max ký tự',
            'phone.required' => 'Số điện thoại là trường bắt buộc',
            'phone.min' => 'Số điện thoại phải có ít nhất :min chữ số',
            'phone.max' => 'Số điện thoại không được vượt quá :max chữ số',
            'title.max' => 'Số điện thoại không được vượt quá :max chữ số',
            'content.max' => 'Số điện thoại không được vượt quá :max chữ số',
            'title.required' => 'Tiêu đề là trường bắt buộc',
            'content.required' => 'Nội dung là trường bắt buộc',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            if (Auth::guard('users')->check()) {
                $contact = new Contact();
                $contact->user_id = Auth::guard('users')->user()->id;
                $contact->name = $request->name;
                $contact->email = $request->email;
                $contact->phone = $request->phone;
                $contact->title = $request->title;
                $contact->content = $request->content;
                $contact->created_at =  date('Y-m-d H:i:s');
                $contact->save();
                
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => ' Login to Continue',]);
            }
        }
    }
}

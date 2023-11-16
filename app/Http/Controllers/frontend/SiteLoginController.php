<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class SiteLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getlogin()
    {
        return view('frontend.login');
    }
    public function register(Request $request)
    {

        Validator::extend('gmail', function ($attribute, $value, $parameters, $validator) {
            return Str::endsWith($value, '@gmail.com');
        });
        $request->validate([
            'username' => 'required|unique:user|max:30|min:5|string',
            'email' => 'required|unique:user,email|email|gmail|min:10',
            'password' => 'required|max:40|min:6',
            'confirm_password' => 'required|same:password|max:40',
        ], [
            'username.required' => 'Bạn chưa điền username',
            'username.min' => 'username không được ít hơn :min ký tự',
            'username.max' => 'username không được dài quá 30 ký tự',
            'username.unique' => 'username này đã được đăng ký, Xin hãy đăng nhập.',

            'email.required' => 'Bạn chưa điền email',
            'email.email' => 'Email không hợp lệ',
            'email.gmail' => 'Gmail không hợp lệ "@gmail.com"',
            'email.min' => 'Email không được ít hơn 10 ký tự',
            'email.unique' => 'Email này đã được đăng ký, Xin hãy đăng nhập.',

            'password.required' => 'Bạn chưa điền mật khẩu',
            'password.max' => 'Mật khẩu không được dài quá 40 ký tự',
            'password.min' => 'Mật khẩu không được ít hơn :min ký tự',

            'confirm_password.required' => 'Bạn chưa nhập lại mật khẩu',
            'confirm_password.max' => 'Không được dài quá 40 ký tự',
            'confirm_password.same' => 'Mật khẩu không trùng khớp',


        ]);
        $actived_token = strtoupper(Str::random(6));
        $user = new User();

        $user->email = $request->email;
        $user->actived_token = $actived_token;
        $expiresAt = now()->addMinutes(15);
        $user->expires_at = $expiresAt;
        $parts = explode('@', $request->email);
        $user->username = $request->username;
        // $user->username = $parts[0];
        $user->name = $request->username;
        $user->image = 'user_men.png';
        $user->password = bcrypt($request->password);

        if ($user->save()) {
            try {
                Mail::send(
                    'emails.actived_send',
                    compact('user'),
                    function ($send_email) use ($user) {
                        $send_email->subject('ToyStore - Xác nhận tài khoản');
                        $send_email->to($user->email, $user->name);
                    }
                );
                return redirect()->route('site.getlogin')->with('message', ['type' => 'success', 'msg' => 'Đăng ký thành công vui lòng vào Email để xác nhận!!']);
            } catch (\Exception $e) {
                // Lấy thông điệp lỗi từ đối tượng ngoại lệ
                $error_message = $e->getMessage();
                return redirect()->route('site.getlogin')->with('message', ['type' => 'danger', 'msg' => $error_message]);
            }
        }
    }
    public function actived_again($user)
    {
        $user = User::find($user);
        $user->actived_token = strtoupper(Str::random(6));
        $expiresAt = now()->addMinutes(15);
        $user->expires_at = $expiresAt;
        if ($user->save()) {
            try {
                Mail::send(
                    'emails.actived_send_again',
                    compact('user'),
                    function ($send_email) use ($user) {
                        $send_email->subject('ToyStore - Xác nhận tài khoản');
                        $send_email->to($user->email, $user->name);
                    }
                );
                return redirect()->route('site.getlogin')->with('message', ['type' => 'success', 'msg' => 'Đăng ký thành công vui lòng vào Email để xác nhận!!']);
            } catch (\Exception $e) {
                // Lấy thông điệp lỗi từ đối tượng ngoại lệ
                $error_message = $e->getMessage();
                return redirect()->route('site.getlogin')->with('message', ['type' => 'danger', 'msg' => $error_message]);
            }
        }
    }

    public function actived($user, $actived_token)
    {
        $user = User::find($user);

        if ($user->actived_token === $actived_token && $user->expires_at > now()) {
            $user->status = 1;
            $user->actived_token = null;
            $user->expires_at = null;
            $user->save();
            return redirect()->route('site.getlogin')->with('message', ['type' => 'success', 'msg' => 'Xác nhận thành công !! Vui lòng đăng nhập!!']);
        } else {
            return redirect()->route('site.getlogin')->with('message', ['type' => 'danger', 'msg' => 'Mã xác nhận đã bị chỉnh sửa Hoặc đã hết hạn!!']);
        }
    }
    public function forget_password()
    {
        return view('frontend.forget_password');
    }
    public function postforget_password(Request $request)
    {
        Validator::extend('gmail', function ($attribute, $value, $parameters, $validator) {
            return Str::endsWith($value, '@gmail.com');
        });
        $request->validate([
            'email' => 'required|exists:user,email|email|gmail|max:30',
        ], [
            'email.required' => 'Bạn chưa điền email',
            'email.email' => 'Email không hợp lệ',
            'email.gmail' => 'Gmail không hợp lệ "@gmail.com"',
            'email.max' => 'Email không được dài quá 30 ký tự',
            'email.exists' => 'Email này không tồn tại trong hệ thống'
        ]);
        $user = User::where('email', $request->email)->first();
        $user->actived_token = strtoupper(Str::random(6));
        $expiresAt = now()->addMinutes(15);
        $user->expires_at = $expiresAt;
        if ($user->save()) {
            try {
                Mail::send(
                    'emails.forget_password_send',
                    compact('user'),
                    function ($send_email) use ($user) {
                        $send_email->subject('ShopTK - Đặt lại mật khẩu');
                        $send_email->to($user->email, $user->name);
                    }
                );
                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Vui lòng vào Email để xác nhận thay đổi mật khẩu!!']);
            } catch (\Exception $e) {
                // Lấy thông điệp lỗi từ đối tượng ngoại lệ
                $error_message = $e->getMessage();
                return redirect()->back()->with('message', ['type' => 'danger', 'msg' => $error_message]);
            }
        }
    }
    public function get_password($user, $actived_token)
    {

        $user = User::find($user);
        $id = $user->id;
        if ($user->actived_token === $actived_token && $user->expires_at > now()) {
            return view('frontend.get_password', compact('id'));
        }
        $slug = "Có thể liên kết đã hết hiệu lực hoặc gặp sự cố mạng";
        return view('errors.404', compact('slug'));
    }
    public function postget_password($user, Request $request)
    {
        $request->validate([
            'password' => 'required|max:40|min:6',
            'new_password' => 'required|max:40',
            'confirm_password' => 'required|same:new_password|max:40',
        ], [

            'password.required' => 'Bạn chưa điền mật khẩu',
            'password.max' => 'Mật khẩu không được dài quá 40 ký tự',
            'password.max' => 'Mật khẩu không được ít hơn :min ký tự',
            'new_password.required' => 'Bạn chưa điền mật khẩu',
            'new_password.max' => 'Mật khẩu không được dài quá 40 ký tự',

            'confirm_password.required' => 'Bạn chưa nhập lại mật khẩu',
            'confirm_password.max' => 'Không được dài quá 40 ký tự',
            'confirm_password.same' => 'Mật khẩu không trùng khớp',


        ]);
        $user = User::find($user);
        $user->password = bcrypt($request->new_password);
        $user->actived_token = null;
        if ($user->save()) {
            return redirect()->route('site.getlogin')->with('message', ['type' => 'success', 'msg' => 'Đổi mật khẩu thành công !! Vui lòng đăng nhập!!']);
        }
        $slug = "Có thể liên kết đã hết hiệu lực hoặc gặp sự cố mạng";
        return view('errors.404', compact('slug'));
    }
    public function postlogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:31|min:5',
            'password' => 'required|max:40|min:6'
        ], [
            'username.required' => 'Bạn chưa điền tên tài khoản hoặc email',
            'username.max' => 'Không được dài quá 30 ký tự',
            'username.min' => 'Không được ít hon :min ký tự',
            'password.required' => 'Bạn chưa điền mật khẩu',
            'password.max' => 'Mật khẩu không được dài quá 40 ký tự',
            'password.min' => 'Mật khẩu không được ít hơn :min ký tự',



        ]);
        $username = $request->username;
        $password = $request->password;
        $data = array('password' => $password);
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $data['email'] = $username;
        } else {
            $data['username'] = $username;
        }
        $remember = $request->has('remember');
        if (Auth::guard('users')->attempt($data, $remember)) {
            $request->session()->regenerate();
            /*====================================*/
            if (Auth::guard('users')->user()->status == 2) {
                $url = route('site.actived_again', ['id' => Auth::guard('users')->user()->id]);
                $html = 'Bạn chưa xác minh tài khoản && <a style="font-size: medium; color:white;" href="' . $url . '">click vào đây để xác minh</a>';
                Auth::guard('users')->logout();
                return redirect()->route('site.getlogin')->with('message', ['type' => 'danger', 'msg' => $html]);
            }
            if (Auth::guard('users')->user()->status == 0) {
                Auth::guard('users')->logout();
                return redirect()->route('site.getlogin')->with('message', ['type' => 'danger', 'msg' => 'Tài khoản của bạn đã bị xóa']);
            }
            return redirect()->route('site.home');
        }
        $error = "Thông tin đăng nhập không chính xác";
        return view('frontend.login', compact('error'));
        // return response()->json(['status' => 'Quality > 0']);
    }
    public function logout(Request $request)
    {
        Auth::guard('users')->logout();
        return redirect()->route('site.home');
    }

    public function profile()
    {
        $profile = User::where('id', Auth::guard('users')->user()->id)->first();

        return view('frontend.profile.profile', compact('profile'));
    }
}

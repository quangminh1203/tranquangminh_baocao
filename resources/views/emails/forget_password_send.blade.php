<div style="width: 600px; margin: 0px auto">
    <div style="text-align: center">
        <h2>Xin chào {{ $user->name }}</h2>
        <P>Để đặt lại mật khẩu của bạn vui lòng nhấn vào nút kích hoạt bên dưới.</P>
        <p style="color: red;">Lưu ý:Liên kết này chỉ có hiệu lực trong 15 phút</p>
        <a  href="{{ route('site.get_password',['id'=>$user->id,'actived_token'=>$user->actived_token]) }}" style="font-size: medium; display: inline-block; background-color: green; color: white; padding: 7px 25px; font-weight: bold;">Đặt lại mật khẩu</a>

    </div>
</div>
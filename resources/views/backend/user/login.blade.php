<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <script>
        const togglePassword = (e) => {
            if (e) {
                const password = e.parentElement.querySelector('input');
                if (password) {
                    if (password.type === "password") {
                        password.type = "text";
                    } else {
                        password.type = "password";
                    }
                }
            }
        }
    </script>
</head>

<body class="body-login">

    <div class="containers">
        <div class="screen">
            <div class="screen__content">
                <form class="login" action="{{ route('admin.postlogin') }}" method="post">
                    @csrf
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" name="username" class="login__input" placeholder="User name / Email">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" name="password" class="login__input" placeholder="Password">
                    </div>
                    <div class="login__field remember">
                        <input id="login-sign-up" name="remember" type="checkbox" class="login__input--checkbox" />
                        <p>Remember password</p>
                    </div>

                    @if (isset($error))
                       
                            <div class="error-login ">
                                {{ $error }}
                            </div>
                        
                    @endif

                    <button class="button login__submit">
                        <span class="button__text-">Log In </span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                </form>
              
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
    {{-- <script>
        var card = document.getElementById("cards");

        function openRegister() {
            card.style.transform = "rotateY(-180deg)";
        }

        function openLogin() {
            card.style.transform = "rotateY(0deg)";
        }
    </script> --}}
</body>

</html>

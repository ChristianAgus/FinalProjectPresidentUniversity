<!DOCTYPE html>

<html
  lang="en"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Login | Haldinfoods</title>

    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/adminnew/css/codebase.min.css') }}" >

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
  </head>

  <body>
    <div id="page-container" class="main-content-boxed">

      <!-- Main Container -->
      <main id="main-container">
        <!-- Page Content -->
        <div class="bg-body-dark">
          <div class="row mx-0 justify-content-center">
            <div class="hero-static col-lg-6 col-xl-4">
              <div class="content content-full overflow-hidden">
                <!-- Header -->
                <div class="py-4 text-center">
                  <a class="link-fx fw-bold" href="{{ route('home') }}">
                    <i class="fa-solid fa-seedling"></i>
                    <span class="fs-4" style="color: #3F9CE8;">Haldin</span><span class="fs-4" style="color: #3F9CE8;">Foods</span>
                  </a>
                  <h1 class="h3 fw-bold mt-4 mb-2">Welcome to Admin Panel HaldinFoods</h1>
                  <h2 class="h5 fw-medium text-muted mb-0">It’s a great day today!</h2>
                </div>
              <form id="formAuthentication" class="js-validation-signin" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="block block-themed block-rounded block-fx-shadow">
                  <div class="block-header bg-gd-dusk">
                    <h3 class="block-title">Please Sign In</h3>
                  </div>
                  <div class="block-content">
                    <div class="form-floating mb-4">
                      <input
                        type="text"
                        class="form-control"
                        name="username"
                        placeholder="Enter your username"
                        autofocus
                      />
                      <label class="form-label" for="login-username">Username</label>
                    </div>
                    <div class="form-floating mb-4">
                      <input
                        type="password"
                        class="form-control"
                        name="password"
                        id="password-field"
                        placeholder="Enter your Password"
                        aria-describedby="password"
                      />
                      <label class="form-label" for="login-password">Password</label>
                    </div>
                    <div class="row">
                      <div class="col-sm-6 d-sm-flex align-items-center push">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="login-remember-me" name="login-remember-me">
                          <label class="form-check-label" for="login-remember-me">Remember Me</label>
                        </div>
                      </div>
                      <div class="col-sm-6 text-sm-end push">
                        <button type="submit" class="btn btn-lg btn-alt-primary fw-medium">
                          Sign In
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const passwordField = document.getElementById("password-field");
        const togglePassword = document.getElementById("toggle-password");
    
        togglePassword.addEventListener("click", function () {
          if (passwordField.type === "password") {
            passwordField.type = "text";
            togglePassword.innerHTML = '<i class="bx bx-show"></i>';
          } else {
            passwordField.type = "password";
            togglePassword.innerHTML = '<i class="bx bx-hide"></i>';
          }
        });
      });
    </script>
    <script data-pagespeed-no-defer src="{{ asset('assets/adminnew/js/codebase.app.min.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/adminnew/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script data-pagespeed-no-defer src="{{ asset('assets/adminnew/js/lib/jquery.min.js') }}"></script>

 
  </body>
</html>

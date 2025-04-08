<?php

session_set_cookie_params(60 * 60 * 8);
session_start();

if (isset($_SESSION['user'])) {
    header("location:../index.php");
} else {
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Nsonic - Member Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="../assets/images/favi/favi.png" />
        <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
        <link rel="stylesheet" type="text/css" href="css/util.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>

    <body>

        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100 py-5 p-sm-5">

                    <div class="row">
                        <div class="col-12 col-md-6 m-auto px-4 m-0 p-md-5 text-center d-flex">
                            <div class="login100-pic js-tilt py-5" data-tilt>
                                <img src="../assets/images/logo/logo.png" alt="Nsonic" class="">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 d-flex p-md-5">
                            <div class="login100-form validate-form my-auto mb-5 mb-md-0 text-center">

                                <div class="login100-pic js-tilt d-block m-0 p-0 mx-auto d-md-none mb-3" data-tilt>
                                    <img src="../assets/images/logo/logo.png" alt="Nsonic" class="w-50">
                                </div>

                                <span class="login100-form-title">
                                    Member Login
                                </span>

                                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                                    <input class="input100" onkeyup="nextPassword(event);" type="text" id="username" placeholder="Username">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </span>
                                </div>

                                <div class="wrap-input100 validate-input" data-validate="Password is required">
                                    <input class="input100" onkeyup="submit(event);" type="password" id="password" placeholder="Password">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                    </span>
                                </div>

                                <div class="container-login100-form-btn">
                                    <button class="login100-form-btn" onclick="login();">
                                        Login
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>
            window.addEventListener('online', () => {
                Swal.fire({
                    position: 'bottom',
                    icon: 'success',
                    title: 'Online Back',
                    showConfirmButton: false,
                    timer: 2500,
                    toast: "position",
                    width: "250px"
                })
            });
            window.addEventListener('offline', () => {
                Swal.fire({
                    position: 'bottom',
                    icon: 'error',
                    title: 'No Internet',
                    showConfirmButton: false,
                    timer: 2500,
                    toast: "position",
                    width: "250px"
                })
            });

            function nextPassword(event) {
                if (event.keyCode === 13) {
                    document.getElementById("password").focus();
                }
            }

            function submit(event) {
                if (event.keyCode === 13) {
                    login();
                }
            }

            function login() {

                let username = document.getElementById("username").value;
                let password = document.getElementById("password").value;

                let form = new FormData();
                form.append("username", username);
                form.append("password", password);


                fetch("controller/loginProcess.php", {
                        method: "POST",
                        body: form,
                    }).then(response => response.text())
                    .then(text => {
                        document.getElementById('username').value = '';
                        document.getElementById('password').value = '';
                        if (text == "success") {
                            window.location = "../";
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: text,
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            })
                        }
                    }).catch(error => {
                        console.error('Fetch error:', error);
                    })

            }
        </script>


        <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
        <script src="vendor/bootstrap/js/popper.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="vendor/tilt/tilt.jquery.min.js"></script>
        <script>
            $('.js-tilt').tilt({
                scale: 1.1
            })
        </script>
        <script src="js/main.js"></script>
        <script src="js/sweetalert2.min.js"></script>

    </body>

    </html>

<?php
}



?>
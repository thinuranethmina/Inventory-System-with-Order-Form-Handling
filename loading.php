<!DOCTYPE html>
<html>

<head>
    <style>
        .loader2 {
            height: 160px;
            background-image: url("assets/images/loading/loading.gif");
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
            margin-top: 40vh !important;
        }

        .load {
            display: block;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100vh;
            overflow: auto;
            background-color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>

<body class="d-flex">

    <!-- loading -->
    <div id="loader" class="load justify-content-between">
        <div class="loader2 justify-content-center text-center d-flex">
            <img src="assets/images/favi/favi.png" style="height: 30px;" class="my-auto" alt="">
        </div>
    </div>
    <!-- loading -->

</body>

</html>
<!doctype html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script
        async
        src="https://www.googletagmanager.com/gtag/js?id=G-1752YPLP7H"
    ></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());

        gtag("config", "G-1752YPLP7H");
    </script>

    <title>404</title>
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/404.css')}}" />
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/tsParticles-64.png')}}" />
</head>

<body class="permission_denied">
<div id="tsparticles"></div>
<div class="denied__wrapper">
    <h1>404</h1>
    <h3>
        LOST IN <span>SPACE</span> {{config()->get('app.name')}}? Hmm, looks like that page doesn't
        exist.
    </h3>
    <img id="astronaut" src="{{asset('assets/images/astronaut.svg')}}" />
    <img id="planet" src="{{asset('assets/images/planet.svg')}}" />
    <a href="{{route('home')}}"><button class="denied__link">Go Home</button></a>
</div>

<script
    type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"
></script>
<script type="text/javascript" src="{{asset('assets/js/404.js')}}"></script>
</body>
</html>

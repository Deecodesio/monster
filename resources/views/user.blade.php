<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{$title}}</title>

  <meta name="description" content="{{ $meta['description'] }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ $meta['url'] }}">
  <meta property="og:title" content="{{ $meta['title'] }}">
  <meta property="og:description" content="{{ $meta['description'] }}">
  <meta property="og:image" content="{{ $meta['image'] }}">

  <link rel="stylesheet" type="text/css" href="{{ asset(mix('css/loader.css')) }}" />
  <link rel="stylesheet" href="{{ asset(mix('css/core.css')) }}">
  <link rel="shortcut icon" href="{{ $site_favicon }}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
  <script src="https://maps.googleapis.com/maps/api/js?key={{$google_key}}&libraries=geometry,places,drawing,places&callback=Function.prototype" async defer></script>
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

</head>

<body>
  <noscript>
    <strong>We're sorry but {{$app_name}} doesn't work properly without
      JavaScript enabled. Please enable it to continue.</strong>
  </noscript>
  <div id="loading-bg">
    {{-- <div class="loading-logo">
      <img src="{{ asset('logo.png') }}" alt="Logo" />
  </div> --}}
  <div class="loading">
    <div class="effect-1 effects"></div>
    <div class="effect-2 effects"></div>
    <div class="effect-3 effects"></div>
  </div>
  </div>
  <div id="app">

  </div>

  <script src="{{ asset(mix('js/user_app.js')) }}"></script>

</body>

</html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <title>Club plein air altitude | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">
    <link
        href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600|Roboto+Condensed:300|Open+Sans:700,300,600,400"
        rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/9814c398a0.js"></script>

</head>

<body>

{{--@section('top-nav')--}}
{{--    @include('includes.top-nav')--}}
{{--@show--}}

{{--<!-- START CONTAINER -->--}}
{{--<div class="page-container row-fluid">--}}
{{--    <!-- leftbar -->--}}
{{--@section('side-nav')--}}
{{--    @include('includes.side-nav')--}}
{{--@show--}}
<!-- START CONTAINER -->
<div class="container-fluid" id="app">
    <!-- START CONTENT -->
    <section id="main-content">
        <div class="pull-left mobile-pagetitle"><h1 class="">Club de plein air altitude</h1></div>
        <section class="wrapper main-wrapper">
            <div class="content-wrapper">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @include('includes.errors')
                </div>

                @yield('content')
            </div>
            <!-- END CONTAINER -->
        </section>
    </section>
    <script src="{{  mix('/js/app.js') }}" async></script>
</div>
</body>
</html>

<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <title>Club plein air altitude | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9814c398a0.js"></script>

</head>

<body>

<!-- START CONTAINER -->
<v-app id="app">
    <navigation></navigation>
    <v-content>
        <v-container
        class="fill-height">
            <v-row
                justify="center">
                @yield('content')
            </v-row>
        </v-container>
    </v-content>
</v-app>

<script src="{{  mix('/js/app.js') }}" async></script>
</body>
</html>

<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>

        <link href="../css/app.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div id="app" class="container">
            @yield('content')
        </div>
    </body>

    <script>
        window.Laravel = { 'csrfToken': '{{ csrf_token() }}' }
    </script>
    <script src="https://js.braintreegateway.com/web/3.9.0/js/client.min.js"></script>
    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/vue.resource/1.2.1/vue-resource.min.js"></script>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vue Todo</title>
    <link rel="stylesheet" type="text/css" href="css/app.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = { csrfToken: '{{ csrf_token() }}' }
    </script>
</head>
<body>
    <!-- <nav class="navbar">
        <div class="navbar-brand">
            <div class="navbar-item" href="{{ url('/test') }}">
                <img src="images/logo.png" alt="ToDo application" width="40" height="40">
                Vueue Todoo
            </div>
        </div>
    </nav> -->

    <div id='app'>
        <task-list></task-list>
    </div>

    <script src="js/app.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>
      @hasSection ('title')
          @yield('title') - {{env('APP_NAME')}}
      @else
          {{ env('APP_NAME') }}
      @endif
    </title>
    <link media="all" rel="stylesheet" href="{{$themeAssets}}/bootstrap.min.css" media="screen">
    <link media="all" rel="stylesheet" href="{{$assets}}/plugins/font-awesome/css/font-awesome.min.css">
    <link media="all" rel="stylesheet" href="{{$themeAssets}}/custom.min.css">
    <link media="all" rel="stylesheet" href="{{$assets}}/custom.css">

    @yield('custom-style')

  </head>
  <body>
    <div class="container">
      @yield('content')
    </div>

    <script>
      window.print();
    </script>

  </body>
</html>

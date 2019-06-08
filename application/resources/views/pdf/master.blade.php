<!DOCTYPE html>
<html lang="en">
  <head>
    <title>
      @hasSection ('title')
          @yield('title') - {{env('APP_NAME')}}
      @else
          {{ env('APP_NAME') }}
      @endif
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
  </body>
</html>

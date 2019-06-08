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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="{{$themeAssets}}/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="{{$assets}}/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{$assets}}/plugins/toaster/jquery.toast.css">
    <link rel="stylesheet" href="{{$assets}}/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="{{$assets}}/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="{{$assets}}/plugins/chosen/chosen.css">
    <link rel="stylesheet" href="{{$themeAssets}}/custom.min.css">

    @yield('custom-style')
    <link rel="stylesheet" href="{{$assets}}/custom.css">
  </head>
  <body>

  <!-- BEGAIN AJAXLOADER -->
  <div id="ajaxloader" class="hide">
      <div id="status">&nbsp;</div>
  </div>
  <!-- END AJAXLOADER -->

    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="{{url('home')}}" class="navbar-brand">{{env('APP_NAME')}}</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          @if (Auth::check())
          <ul class="nav navbar-nav">
            <li><a href="{{url('home')}}">Dashboard</a></li>
            <li><a href="{{url('students/list')}}">Students</a></li>
            <li><a href="{{url('attendances/list')}}">Attendances</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    Report <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{url('reports/daily-attendance')}}">Daily Attendance</a></li>
                  <li><a href="{{url('reports/sectionwise-attendance')}}">Sectionwise Attendance</a></li>
                </ul>
            </li>
          </ul>
          @endif

          <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} ({{ Auth::user()->role }}) <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                      <li class="hide"><a href="{{ url('/users/profile') }}"><i class="fa fa-btn fa-user"></i> Profile</a></li>
                      
                      <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
                    </ul>
                </li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endif
          </ul>
        </div>
      </div>
    </div>


    <div class="container">
        
        @yield('content')

        @include('global_modal')
        <footer>
            <div class="row">
              <div class="col-lg-12">
                <ul class="list-unstyled">
                  <li class="pull-right">&copy; {{Carbon::now()->format('Y')}} {{env('APP_NAME')}}. All rights reserved.</li>
                </ul>
                <p>Made with <a href="#" rel="nofollow"><i style="color:red;" class="fa fa-heart-o"></i></a> | <small>{{app_build_info()}}</small></p>
              </div>
            </div>
        </footer>
    </div>
    <script src="{{$themeAssets}}/jquery.min.js"></script>
    <script src="{{$themeAssets}}/bootstrap.min.js"></script>
    <script src="{{$assets}}/plugins/toaster/jquery.toast.js"></script>
    <script src="{{$assets}}/plugins/daterangepicker/moment.js"></script>
    <script src="{{$assets}}/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="{{$assets}}/plugins/chosen/chosen.jquery.min.js"></script>
    <script src="{{$assets}}/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- Theme's Custom Script-->
    <script src="{{$themeAssets}}/custom.js"></script>

    <!-- Custom Script-->
    <script src="{{$assets}}/custom.js"></script>

    @yield('custom-script')

    @if(session()->has('toast'))
        <?php
        $toast = session()->get('toast');
        $message = $toast['message'];
        $type = $toast['type'];
        ?>
        <script>
            toastMsg("{!! $message !!}","{{ $type }}");
        </script>
    @endif
  </body>
</html>
 
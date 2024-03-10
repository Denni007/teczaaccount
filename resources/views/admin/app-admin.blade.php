<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <!-- Styles -->
        <!-- <link href="{{ Helper::assets('css/app.css') }}" rel="stylesheet"> -->

        <link rel="shortcut icon" href="{{ Helper::assets('images/favicon.ico') }}">

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ Helper::assets('plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{ Helper::assets('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ Helper::assets('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{ Helper::assets('plugins/jqvmap/jqvmap.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ Helper::assets('css/adminlte.min.css') }}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ Helper::assets('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <!-- Date picker -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ Helper::assets('plugins/daterangepicker/daterangepicker.css') }}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{ Helper::assets('plugins/summernote/summernote-bs4.min.css') }}">
        
        <link rel="stylesheet" src="{{ Helper::assets('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"></link>
        <link rel="stylesheet" src="{{ Helper::assets('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"></link>
        <!-- <link rel="stylesheet" src="{{ Helper::assets('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}"></link>  -->
        <link rel="stylesheet" src="{{ Helper::assets('plugins/sweetalert2/sweetalert2.min.css') }}s" />
        <!-- /core JS files -->
        <style>
            .select2-selection {
                height: 38px !important;
            }
            .select2-selection__arrow {
                top: 5px !important;
            }
        </style>

        
        <!-- Theme JS files -->
        <script src="{{ Helper::assets('js/app.js') }}"></script>
        <!-- /theme JS files -->
        <!-- jQuery -->
        <script src="{{ Helper::assets('plugins/jquery/jquery.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ Helper::assets('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
        $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{ Helper::assets('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- ChartJS -->
        <script src="{{ Helper::assets('plugins/chart.js/Chart.min.js') }}"></script>
        <!-- Sparkline -->
        <script src="{{ Helper::assets('plugins/sparklines/sparkline.js') }}"></script>
        <!-- JQVMap -->
        <script src="{{ Helper::assets('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
        <script src="{{ Helper::assets('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{ Helper::assets('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
        <!-- Date Picker -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
        <!-- daterangepicker -->
        <script src="{{ Helper::assets('plugins/moment/moment.min.js') }}"></script>
        <script src="{{ Helper::assets('plugins/daterangepicker/daterangepicker.js') }}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <!-- <script src="{{ Helper::assets('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script> -->
        <!-- Summernote -->
        <script src="{{ Helper::assets('plugins/summernote/summernote-bs4.min.js') }}"></script>
        <!-- overlayScrollbars -->
        <script src="{{ Helper::assets('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ Helper::assets('js/adminlte.js') }}"></script>

        <script type="text/javascript" src="{{ Helper::assets('plugins/forms/validation/validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ Helper::assets('plugins/forms/validation/additional_methods.min.js') }}"></script>
        <script type="text/javascript" src="{{ Helper::assets('plugins/forms/styling/uniform.min.js') }}"></script>
        <script type="text/javascript" src="{{ Helper::assets('plugins/forms/selects/select2.min.js') }}"></script>
        <script type="text/javascript" src="{{ Helper::assets('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
        
        <!--Datatable js-->
        <script type="text/javascript" src="{{ Helper::assets('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script type="text/javascript" src="{{ Helper::assets('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script type="text/javascript" src="{{ Helper::assets('plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
        <script type="text/javascript" src="{{ Helper::assets('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script type="text/javascript" src="{{ Helper::assets('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script type="text/javascript" src="{{ Helper::assets('plugins/forms/selects/select2.min.js') }}"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        @yield('header_content')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed @if(in_array(Route::currentRouteName(),['admin','admin.login'])) login-page @endif">
        @if(!in_array(Route::currentRouteName(),['admin','admin.login']))
            <div class="wrapper">
        @endif
         
            @if(Auth::check())
                @include('layouts.partial.admin_main_navbar')
                @include('layouts.partial.admin_sidebar')
            @endif

            <!-- Content area -->
            @if(!in_array(Route::currentRouteName(),['admin','admin.login']))
                <div class="content-wrapper">
            @endif
            @include('layouts.flash-message')
            @yield('content')
            
            <!-- /content area -->

        @if(!in_array(Route::currentRouteName(),['admin','admin.login']))
            </div>
            @include('layouts.partial.admin_footer')
            </div>
        @endif        

        @yield('footer_content')

        <script type="text/javascript">
            var companyUrl  = '{{ route("post.getCompany") }}';
            var csrfToken = '{{ csrf_token() }}';
            $("#ddlCompany").on("change", function(){
                var company = $(this).val();
                var r = confirm("Are you sure you want to change Company? Changes you made cannot saved!");
                if (r == true) {
                    jQuery.ajax({
                        url:  companyUrl,
                        type: "POST",
                        data: {
                            '_token' : csrfToken,
                            'company': company
                        },
                        success: function(response)
                        {
                            if(response)
                            {
                                url = $(location).attr("href");
                                urlparam = url.split('/');
                                if(urlparam[urlparam.length - 1] == 'add')
                                {
                                    newUrl = '{{url("/")}}/'+urlparam[urlparam.length - 3]+'/'+urlparam[urlparam.length - 2];
                                }
                                else if(urlparam[urlparam.length - 2] == 'edit')
                                {
                                    newUrl = '{{url("/")}}/'+urlparam[urlparam.length - 4]+'/'+urlparam[urlparam.length - 3];
                                }
                                else if(urlparam[urlparam.length - 2] == 'view')
                                {
                                    newUrl = '{{url("/")}}/'+urlparam[urlparam.length - 4]+'/'+urlparam[urlparam.length - 3];
                                }
                                else
                                {
                                    newUrl = $(location).attr("href");
                                }
                                //alert(newUrl);
                                //alert('{{url("/")}}/'+urlparam[urlparam.length - 3]);
                                //url = '{{route("admin.index")}}';
                                window.location.href = newUrl;
                            }
                        }
                    });
                }
                else
                {
                    window.location.reload();
                }
                
            });

           
                var tday = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    var tmonth = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

    function GetClock() {
        var d = new Date();
        var nday = d.getDay(), nmonth = d.getMonth(), ndate = d.getDate();
        var nhour = d.getHours(), nmin = d.getMinutes(), nsec = d.getSeconds(), ap;

        if (nhour == 0) {
            ap = " AM";
            nhour = 12;
        } else if (nhour < 12) {
            ap = " AM";
        } else if (nhour == 12) {
            ap = " PM";
        } else if (nhour > 12) {
            ap = " PM";
            nhour -= 12;
        }

        if (nmin <= 9) nmin = "0" + nmin;
        if (nsec <= 9) nsec = "0" + nsec;

        document.getElementById('clockbox').innerHTML = "<li> <strong>" + nhour + ":" + nmin + ":" + nsec + ap + "</strong><li>" + tday[nday] + ", " + tmonth[nmonth] + " " + ndate + " </li>";
    }

    window.onload = function () {
        GetClock();
        setInterval(GetClock, 1000);

        document.getElementById("clockbox").style.color = "#000000";

    }

    $('.select2').select2();
          
        </script>
        
    </body>
</html>

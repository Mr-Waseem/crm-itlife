<!DOCTYPE html>
<html lang="en">

<head>
    @yield('head')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ URL::asset('dashboard/images/favicon.ico') }}">

    <title>{{ SettingsFacade::data()->system_name }}</title>

    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Bootstrap 4.0-->
    <link rel="stylesheet"
        href="{{ URL::asset('dashboard/assets/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
    <!-- Bootstrap 4.1-->
    <link rel="stylesheet"
        href="{{ URL::asset('dashboard/assets/vendor_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Bootstrap extend-->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/bootstrap-extend.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet"
        href="{{ URL::asset('dashboard/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
        href="{{ URL::asset('dashboard/assets/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- fullCalendar -->
    <link rel="stylesheet"
        href="{{ URL::asset('dashboard/assets/vendor_components/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet"
        href="{{ URL::asset('dashboard/assets/vendor_components/fullcalendar/fullcalendar.print.min.css') }}"
        media="print">

    <!-- Bootstrap extend-->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/bootstrap-extend.css') }}">

    <!-- theme style -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/master_style.css') }}">

    <!-- horizontal menu style -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/horizontal_menu_style.css') }}">

    <!-- SoftMaterial admin skins -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/skins/_all-skins.css') }}">

    <!-- Bootstrap switch-->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/assets/vendor_components/bootstrap-switch/switch.css') }}">

    <!-- Morris charts -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/assets/vendor_components/morris.js/morris.css') }}">
    <!-- Bootstrap select -->
    <link rel="stylesheet"
        href="{{ URL::asset('dashboard/assets/vendor_components/bootstrap-select/dist/css/bootstrap-select.css') }}">
    <!-- Font Awesome Offline -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('dashboard/fontawesome/css/fontawesome.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/assets/vendor_plugins/iCheck/all.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/custom.css') }}">
    
    <style>
        .fstlist {
            /* position: absolute; */
            z-index: 1 !important;
        }

        .select2>.selection>.select2-selection>.select2-selection__rendered {
            position: relative;
            top: 5px;
        }

        .mt-1 {
            margin-top: 10px !important;
        }

        .mt-2 {
            margin-top: 20px !important;
        }

        .mt-3 {
            margin-top: 30px !important;
        }

        .mt-4 {
            margin-top: 40px !important;
        }

        .mt-5 {
            margin-top: 50px !important;
        }

        .note {
            margin-bottom: 15px;
            padding: 10px;
        }

        .note-danger {
            background-color: #ffdddd;
            border-left: 6px solid #f44336;
        }

        .note-success {
            background-color: #ddffdd;
            border-left: 6px solid #04AA6D;
        }

        .note-info {
            background-color: #e7f3fe;
            border-left: 6px solid #2196F3;
        }

        .note-warning {
            background-color: #ffffcc;
            border-left: 6px solid #ffeb3b;
        }

        .table-bordered {
            border: 1px solid rgb(174, 170, 170) !important;
        }

        .table-bordered thead tr th {
            border: 1px solid rgb(174, 170, 170) !important;
        }

        .table-bordered tbody tr td {
            border: 1px solid rgb(174, 170, 170) !important;
        }

        .table-bordered tfoot tr td {
            border: 1px solid rgb(174, 170, 170) !important;
        }

        .table-bordered thead tr th,
        .table-bordered tbody tr td,
        .table-bordered tfoot tr td {
            color: black !important;
        }

        .icon-bar {
            position: fixed;
            top: 40%;
            z-index: 100;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            background-color: white;
            border: 2px solid #03A9F4;
        }

        .icon-bar .fa-angle-left {
            border-bottom: 2px solid #03A9F4;
            display: block;
            text-align: center;
            transition: all 0.3s ease;
            color: #03A9F4;
            font-size: 20px;
            padding-top: 10px;
            padding-bottom: 7px;
            padding-right: 15px;
            padding-left: 10px;
            position: relative;
            top: -2px;
        }

        .icon-bar .fa-repeat {
            border-bottom: 2px solid #03A9F4;
            display: block;
            text-align: center;
            transition: all 0.3s ease;
            color: #03A9F4;
            font-size: 18px;
            padding-top: 8px;
            padding-bottom: 8px;
            padding-right: 15px;
            padding-left: 10px;
            position: relative;
            top: -2px;
        }

        .icon-bar .fa-angle-right {
            border-bottom: 2px solid #03A9F4;
            display: block;
            text-align: center;
            transition: all 0.3s ease;
            color: #03A9F4;
            font-size: 20px;
            padding-top: 10px;
            padding-bottom: 7px;
            padding-right: 15px;
            padding-left: 10px;
            position: relative;
            top: -3px;
        }

        .icon-bar .fa-trash-o {
            border-bottom: 2px solid #03A9F4;
            display: block;
            text-align: center;
            transition: all 0.3s ease;
            color: #f45b03;
            font-size: 20px;
            padding-top: 6px;
            padding-bottom: 6px;
            padding-right: 15px;
            padding-left: 10px;
            position: relative;
            top: -2px;
        }

        .icon-bar .fa-print {
            display: block;
            text-align: center;
            transition: all 0.3s ease;
            color: #03A9F4;
            font-size: 20px;
            padding-top: 6px;
            padding-bottom: 6px;
            padding-right: 15px;
            padding-left: 10px;
            position: relative;
            top: -2px;
        }

        .text-black {
            color: black !important;
        }

        .colored-toast.swal2-icon-success {
            background-color: #2AA198 !important;
            font-size: 12px;
        }

        .colored-toast.swal2-icon-error {
            background-color: #CC3035 !important;
            font-size: 12px;
        }

        .colored-toast.swal2-icon-warning {
            background-color: #f8bb86 !important;
            font-size: 12px;
        }

        .colored-toast.swal2-icon-info {
            background-color: #3fc3ee !important;
            font-size: 12px;
        }

        .colored-toast.swal2-icon-question {
            background-color: #87adbd !important;
            font-size: 12px;
        }

        .colored-toast .swal2-title {
            color: white;
        }

        .colored-toast .swal2-close {
            color: white;
        }

        .colored-toast .swal2-html-container {
            color: white;
        }

        .modal.show .modal-dialog {
            max-width: 80% !important;
        }

        .list-group {
            border-radius: 0px !important;
        }

        .datatable-tr-settings {
            line-height: 3px !important;
             font-size: 14px;
        }
    </style>
</head>
<!-- <body> -->
<body class="hold-transition skin-green layout-top-nav"  onunload="myFunction()">
    <div class="wrapper">

        <header class="main-header">
            <div class="inside-header">
                <!-- Logo -->
                <a href="{{ URL::to('/home') }}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <b class="logo-mini">
                        <h4>{{ SettingsFacade::data()->title }}</h4>
                    </b>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">
                    </span>
                </a>
                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="{{ URL::to('home') }}" class="sidebar-toggle d-block d-lg-none" data-toggle="push-menu"
                        role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <ul class="navbar-nav mr-auto mt-md-0"></ul>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            <li class="search-box" style="display: none;">
                                <a class="nav-link hidden-sm-down" href="javascript:void(0)"><i
                                        class="fa fa-search"></i></a>
                                <form class="app-search" style="display: none;">
                                    <input type="text" class="form-control" placeholder="Search &amp; enter"> <a
                                        class="srh-btn"><i class="ti-close"></i></a>
                                </form>
                            </li>

                            <!-- Messages -->
                            <li class="dropdown messages-menu" style="display: none;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope faa-horizontal animated"></i>
                                </a>
                                <ul class="dropdown-menu scale-up">
                                    <li class="header">You have 5 messages</li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu inner-content-div">
                                            <li>
                                                <!-- start message -->
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="{{ URL::asset('dashboard/images/user2-160x160.jpg') }}"
                                                            class="rounded-circle" alt="User Image">
                                                    </div>
                                                    <div class="mail-contnet">
                                                        <h4>
                                                            Lorem Ipsum
                                                            <small><i class="fa fa-clock-o"></i> 15 mins</small>
                                                        </h4>
                                                        <span>Lorem ipsum dolor sit amet, consectetur adipiscing
                                                            elit.</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end message -->
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="{{ URL::asset('dashboard/images/user3-128x128.jpg') }}"
                                                            class="rounded-circle" alt="User Image">
                                                    </div>
                                                    <div class="mail-contnet">
                                                        <h4>
                                                            Nullam tempor
                                                            <small><i class="fa fa-clock-o"></i> 4 hours</small>
                                                        </h4>
                                                        <span>Curabitur facilisis erat quis metus congue viverra.</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="{{ URL::asset('dashboard/images/user4-128x128.jpg') }}"
                                                            class="rounded-circle" alt="User Image">
                                                    </div>
                                                    <div class="mail-contnet">
                                                        <h4>
                                                            Proin venenatis
                                                            <small><i class="fa fa-clock-o"></i> Today</small>
                                                        </h4>
                                                        <span>Vestibulum nec ligula nec quam sodales rutrum sed
                                                            luctus.</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="{{ URL::asset('dashboard/images/user3-128x128.jpg') }}"
                                                            class="rounded-circle" alt="User Image">
                                                    </div>
                                                    <div class="mail-contnet">
                                                        <h4>
                                                            Praesent suscipit
                                                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                                        </h4>
                                                        <span>Curabitur quis risus aliquet, luctus arcu nec, venenatis
                                                            neque.</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="{{ URL::asset('dashboard/images/user4-128x128.jpg') }}"
                                                            class="rounded-circle" alt="User Image">
                                                    </div>
                                                    <div class="mail-contnet">
                                                        <h4>
                                                            Donec tempor
                                                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                                                        </h4>
                                                        <span>Praesent vitae tellus eget nibh lacinia pretium.</span>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">See all e-Mails</a></li>
                                </ul>
                            </li>
                            <!-- Notifications -->
                            <li class="dropdown notifications-menu" style="display: none;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell faa-ring animated"></i>
                                </a>
                                <ul class="dropdown-menu scale-up">
                                    <li class="header">You have 7 notifications</li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu inner-content-div">
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-users text-success"></i> Curabitur id eros quis
                                                    nunc suscipit blandit.
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-warning text-warning"></i> Duis malesuada justo eu
                                                    sapien elementum, in semper diam posuere.
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-users text-red"></i> Donec at nisi sit amet tortor
                                                    commodo porttitor pretium a erat.
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-shopping-cart text-success"></i> In gravida mauris
                                                    et nisi
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-user text-danger"></i> Praesent eu lacus in libero
                                                    dictum fermentum.
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-user text-danger"></i> Nunc fringilla lorem
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-user text-danger"></i> Nullam euismod dolor ut quam
                                                    interdum, at scelerisque ipsum imperdiet.
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">View all</a></li>
                                </ul>
                            </li>
                            <!-- Tasks -->
                            <li class="dropdown tasks-menu" style="display: none;">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-comment faa-vertical animated"></i>
                                </a>
                                <ul class="dropdown-menu scale-up">
                                    <li class="header">You have 6 tasks</li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu inner-content-div">
                                            <li>
                                                <!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Lorem ipsum dolor sit amet
                                                        <small class="pull-right">30%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-aqua" style="width: 30%"
                                                            role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            <span class="sr-only">30% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                            <li>
                                                <!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Vestibulum nec ligula
                                                        <small class="pull-right">20%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-danger"
                                                            style="width: 20%" role="progressbar" aria-valuenow="20"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">20% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                            <li>
                                                <!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Donec id leo ut ipsum
                                                        <small class="pull-right">70%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-light-blue"
                                                            style="width: 70%" role="progressbar" aria-valuenow="20"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">70% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                            <li>
                                                <!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Praesent vitae tellus
                                                        <small class="pull-right">40%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-yellow"
                                                            style="width: 40%" role="progressbar" aria-valuenow="20"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">40% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                            <li>
                                                <!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Nam varius sapien
                                                        <small class="pull-right">80%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-red" style="width: 80%"
                                                            role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            <span class="sr-only">80% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                            <li>
                                                <!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Nunc fringilla
                                                        <small class="pull-right">90%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-primary"
                                                            style="width: 90%" role="progressbar" aria-valuenow="20"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">90% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                        </ul>
                                    </li>
                                    <li class="footer">
                                        <a href="#">View all tasks</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- User Account -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ URL::asset('dashboard/user5-128x128.jpg') }}"
                                        class="user-image rounded-circle" alt="User Image">
                                </a>
                                <ul class="dropdown-menu scale-up">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="{{ URL::asset('dashboard/user5-128x128.jpg') }}"
                                            class="float-left rounded-circle" alt="User Image">

                                        <p>
                                            {{ Auth::User()->name }}
                                            <small class="mb-5">{{ SettingsFacade::data()->email }}</small>
                                            <a href="#" class="btn btn-danger btn-sm btn-rounded">View
                                                Profile</a>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body">
                                        <div class="row no-gutters">
                                            <div class="col-12 text-left">
                                                <a href="#"><i class="ion ion-person"></i> My Profile</a>
                                            </div>
                                            <div class="col-12 text-left">
                                                <a href="#"><i class="ion ion-email-unread"></i> Inbox</a>
                                            </div>
                                            <div class="col-12 text-left">
                                                <a href="#"><i class="ion ion-settings"></i> Setting</a>
                                            </div>
                                            <div role="separator" class="divider col-12"></div>
                                            <div class="col-12 text-left">
                                                <a href="#"><i class="ti-settings"></i> Account Setting</a>
                                            </div>
                                            <div role="separator" class="divider col-12"></div>
                                            <div class="col-12 text-left">
                                                <a href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                                        class="fa fa-power-off"></i> Logout</a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </div>
                                        </div>
                                        <!-- /.row -->
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <li style="display: none;">
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-cog fa-spin"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Navbar -->
        <div class="main-nav">
            <nav class="navbar navbar-expand-lg">
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    @include('navbar.almajeed')
                </div>
            </nav>
        </div>

        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right d-none d-sm-inline-block"></div>
            &copy; <span id="current-year"></span> {{ SettingsFacade::data()->system_name }}. Developed By <a
                href="https://www.itlifee.net" target="_blank">{{ SettingsFacade::data()->title }}</a>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-light">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="nav-item"><a href="#control-sidebar-home-tab" data-toggle="tab"><i
                            class="fa fa-home"></i></a></li>
                <li class="nav-item"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i
                            class="fa fa-cog fa-spin"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-birthday-cake bg-danger"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Admin Birthday</h4>

                                    <p>Will be July 24th</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-user bg-warning"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Jhone Updated His Profile</h4>

                                    <p>New Email : jhone_doe@demo.com</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-envelope-o bg-info"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Disha Joined Mailing List</h4>

                                    <p>disha@demo.com</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-file-code-o bg-success"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Code Change</h4>

                                    <p>Execution time 15 Days</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Web Design
                                    <span class="label label-danger pull-right">40%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 40%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Update Data
                                    <span class="label label-success pull-right">75%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-success" style="width: 75%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Order Process
                                    <span class="label label-warning pull-right">89%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-warning" style="width: 89%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Development
                                    <span class="label label-primary pull-right">72%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-primary" style="width: 72%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <input type="checkbox" id="report_panel" class="chk-col-grey">
                            <label for="report_panel" class="control-sidebar-subheading ">Report panel usage</label>

                            <p>
                                general settings information
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <input type="checkbox" id="allow_mail" class="chk-col-grey">
                            <label for="allow_mail" class="control-sidebar-subheading ">Mail redirect</label>

                            <p>
                                Other sets of options are available
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <input type="checkbox" id="expose_author" class="chk-col-grey">
                            <label for="expose_author" class="control-sidebar-subheading ">Expose author
                                name</label>

                            <p>
                                Allow the user to show his name in blog posts
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <h3 class="control-sidebar-heading">Chat Settings</h3>

                        <div class="form-group">
                            <input type="checkbox" id="show_me_online" class="chk-col-grey">
                            <label for="show_me_online" class="control-sidebar-subheading ">Show me as
                                online</label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <input type="checkbox" id="off_notifications" class="chk-col-grey">
                            <label for="off_notifications" class="control-sidebar-subheading ">Turn off
                                notifications</label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                <a href="javascript:void(0)" class="text-red margin-r-5"><i
                                        class="fa fa-trash-o"></i></a>
                                Delete chat history
                            </label>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>

    </div>
    <!-- ./wrapper -->



    <!-- jQuery 3 -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/jquery-3.3.1/jquery-3.3.1.js') }}"></script>

    <!-- jQuery UI 1.11.4 -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/jquery-ui/jquery-ui.js') }}"></script>

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>

    <!-- popper -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/popper/dist/popper.min.js') }}"></script>

    <!-- Bootstrap 4.0-->
    {{-- <script src="{{ URL::asset('dashboard/assets/vendor_components/bootstrap/dist/js/bootstrap.js') }}"></script>
    --}}
    <!-- Bootstrap 4.1-->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Slimscroll -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- FastClick -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/fastclick/lib/fastclick.js') }}"></script>

    <!-- fullCalendar -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/vendor_components/fullcalendar/fullcalendar.js') }}"></script>

    <!-- Sparkline -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}">
    </script>

    <!-- Morris.js charts -->
    <script src="{{ URL::asset('dashboard/assets/vendor_components/raphael/raphael.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/assets/vendor_components/morris.js/morris.min.js') }}"></script>

    <!-- SoftMaterial admin App -->
    <script src="{{ URL::asset('dashboard/js/template.js') }}"></script>

    <!-- SoftMaterial admin dashboard demo (This is only for demo purposes) -->
    <script src="{{ URL::asset('dashboard/js/pages/dashboard.js') }}"></script>

    <!-- SoftMaterial admin for demo purposes -->
    <script src="{{ URL::asset('dashboard/js/demo.js') }}"></script>

    <!-- SoftMaterial admin horizontal-layout -->
    <script src="{{ URL::asset('dashboard/js/horizontal-layout.js') }}"></script>

    <!-- Lion_admin for calendar -->
    <script src="{{ URL::asset('dashboard/js/pages/calendar.js') }}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{ URL::asset('dashboard/assets/vendor_plugins/iCheck/icheck.min.js') }}"></script>
    <!-- Custom HoverMenus -->
    <script src="{{ URL::asset('dashboard/js/custom.js') }}"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    	$(window).on("unload", function(e) {
   		$.ajax({
     		type: 'POST',
     		async: false,
     		url: 'logout'
  	 	});
	});
    </script>

    <!-- Key Up Validations -->
    <script>
        function myFunction(){
            alert("unloaded");
        }
        function onlyNumberKey(evt) {
              // Only ASCII character in that range allowed
              var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
          }

          function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode == 46) {
                // Check if decimal point already exists in the input
                if (evt.target.value.indexOf('.') !== -1)
                return false;
                else
                return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        function isNumberKeyNoPoint(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode == 46) {
                // Check if decimal point already exists in the input
                // if (evt.target.value.indexOf('.') !== -1)
                // return false;
                // else
                // return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        
    </script>
 
    <!-- End Key Up Validations -->
    @yield('scripts')
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
</body>

</html>

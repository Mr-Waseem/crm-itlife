@extends('app')
@section('head')

    <title>Dashboard</title>
@stop
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header text-center">
            <h1>
                Welcome : {{ Auth::User()->name }} || Department : {{ $user_department->name }}
                {{-- <small>{{ Auth::User()->name }}</small> --}}
            </h1>
            {{-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol> --}}
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box">
                        <div class="flexbox flex-justified text-center bg-primary rounded">
                            <div class="no-shrink py-30">
                                <span class="fa fa-wheelchair font-size-50"></span>
                            </div>

                            <div class="py-30 bg-white text-dark">
                                <div class="font-size-30 countnm">4587</div>
                                <span>Sales</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box">
                        <div class="flexbox flex-justified text-center bg-info rounded">
                            <div class="no-shrink py-30">
                                <span class="fa fa-file font-size-50"></span>
                            </div>

                            <div class="py-30 bg-white text-dark">
                                <div class="font-size-30 countnm">12458</div>
                                <span>Sales</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box">
                        <div class="flexbox flex-justified text-center bg-danger rounded">
                            <div class="no-shrink py-30">
                                <span class="fa fa-calendar font-size-50"></span>
                            </div>

                            <div class="py-30 bg-white text-dark">
                                <div class="font-size-30 countnm">102</div>
                                <span>Sales</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box">
                        <div class="flexbox flex-justified text-center bg-warning rounded">
                            <div class="no-shrink py-30">
                                <span class="fa fa-heartbeat font-size-50"></span>
                            </div>

                            <div class="py-30 bg-white text-dark">
                                <div class="font-size-30 countnm">14458</div>
                                <span>Sales</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->

            </div>

            {{-- <div class="row">

                <div class="col-12 col-lg-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Patients In</h4>
                        </div>
                        <div class="box-body">
                            <div class="flexbox justify-content-end">
                                <div><i class="fa fa-circle mr-5 text-primary"></i>OPD</div>
                                <div><i class="fa fa-circle mr-5 text-danger"></i>ICU</div>
                            </div>
                            <div id="morris-area-chart1" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <!-- AREA CHART -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Total Sales</h4>

                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-close" href="#"></a></li>
                                <li><a class="box-btn-slide" href="#"></a></li>
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <div class="box-body chart-responsive">
                            <div class="flexbox justify-content-end">
                                <div><span class="mr-5"><span
                                            class="badge badge-ring badge-primary mr-5"></span>OPD</span></div>
                                <div><span class="mr-5"><span
                                            class="badge badge-ring badge-info mr-5"></span>Operation</span></div>
                                <div><span class="mr-5"><span class="badge badge-ring badge-danger mr-5"></span>Lab</span>
                                </div>
                                <div><span class="mr-5"><span
                                            class="badge badge-ring badge-warning mr-5"></span>Medicine</span>
                                </div>
                            </div>

                            <div class="chart" id="morris_extra_line_chart" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>

                <div class="col-lg-4 col-12">
                    <div class="box h-500">
                        <div class="box-header with-border">
                            <h4 class="box-title">Appointment</h4>

                            <ul class="box-controls pull-right">
                                <li class="dropdown">
                                    <a data-toggle="dropdown" href="#" aria-expanded="false"><i
                                            class="ti-more-alt rotate-90"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                        style="position: absolute; transform: translate3d(-114px, 21px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="dropdown-item" href="#"><i class="ti-import"></i>
                                            Import</a>
                                        <a class="dropdown-item" href="#"><i class="ti-export"></i>
                                            Export</a>
                                        <a class="dropdown-item" href="#"><i class="ti-printer"></i>
                                            Print</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#"><i class="ti-settings"></i>
                                            Settings</a>
                                    </div>
                                </li>
                                <li><a class="box-btn-close" href="#"></a></li>
                                <li><a class="box-btn-slide" href="#"></a></li>
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <div class="box-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar" class="dask"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-12 col-lg-8">
                    <div class="box h-500">
                        <div class="box-header with-border">
                            <h4 class="box-title">Radiology</h4>

                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-close" href="#"></a></li>
                                <li><a class="box-btn-slide" href="#"></a></li>
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="bb-2">No.</th>
                                            <th class="bb-2">Test Name</th>
                                            <th class="bb-2">Handling Lab</th>
                                            <th class="bb-2">Priority</th>
                                            <th class="bb-2">Cost</th>
                                            <th class="bb-2">Handling</th>
                                            <th class="bb-2">Collect By</th>
                                            <th class="bb-2">Status</th>
                                            <th class="bb-2">Result</th>
                                            <th class="bb-2">Signed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Blood Count</td>
                                            <td>Microbiology</td>
                                            <td><span class="badge badge-warning">Law</span></td>
                                            <td>N500</td>
                                            <td>Coker Mi</td>
                                            <td>5.45pm 11/05</td>
                                            <td><span class="badge badge-success">Result Added</span></td>
                                            <td><a href="#" data-toggle="modal" data-target="#result"
                                                    class="text-info">Result </a>
                                                <a href="#">LMIS </a>
                                                <a href="#" data-toggle="modal" data-target="#comment-dialog"
                                                    class="text-info">Comment </a>
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-toggle" data-toggle="button"
                                                    aria-pressed="false" autocomplete="off">
                                                    <div class="handle"></div>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Blood Count</td>
                                            <td>Microbiology</td>
                                            <td><span class="badge badge-warning">Law</span></td>
                                            <td>N500</td>
                                            <td>Coker Mi</td>
                                            <td>5.45pm 11/05</td>
                                            <td><span class="badge badge-success">Result Added</span></td>
                                            <td><a href="#" data-toggle="modal" data-target="#result"
                                                    class="text-info">Result </a>
                                                <a href="#">LMIS </a>
                                                <a href="#" data-toggle="modal" data-target="#comment-dialog"
                                                    class="text-info">Comment </a>
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-toggle" data-toggle="button"
                                                    aria-pressed="false" autocomplete="off">
                                                    <div class="handle"></div>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Blood Count</td>
                                            <td>Microbiology</td>
                                            <td><span class="badge badge-warning">Law</span></td>
                                            <td>N500</td>
                                            <td>Coker Mi</td>
                                            <td>5.45pm 11/05</td>
                                            <td><span class="badge badge-success">Result Added</span></td>
                                            <td><a href="#" data-toggle="modal" data-target="#result"
                                                    class="text-info">Result </a>
                                                <a href="#">LMIS </a>
                                                <a href="#" data-toggle="modal" data-target="#comment-dialog"
                                                    class="text-info">Comment </a>
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-toggle" data-toggle="button"
                                                    aria-pressed="false" autocomplete="off">
                                                    <div class="handle"></div>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Blood Count</td>
                                            <td>Microbiology</td>
                                            <td><span class="badge badge-warning">Law</span></td>
                                            <td>N500</td>
                                            <td>Coker Mi</td>
                                            <td>5.45pm 11/05</td>
                                            <td><span class="badge badge-success">Result Added</span></td>
                                            <td><a href="#" data-toggle="modal" data-target="#result"
                                                    class="text-info">Result </a>
                                                <a href="#">LMIS </a>
                                                <a href="#" data-toggle="modal" data-target="#comment-dialog"
                                                    class="text-info">Comment </a>
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-toggle" data-toggle="button"
                                                    aria-pressed="false" autocomplete="off">
                                                    <div class="handle"></div>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Blood Count</td>
                                            <td>Microbiology</td>
                                            <td><span class="badge badge-warning">Law</span></td>
                                            <td>N500</td>
                                            <td>Coker Mi</td>
                                            <td>5.45pm 11/05</td>
                                            <td><span class="badge badge-success">Result Added</span></td>
                                            <td><a href="#" data-toggle="modal" data-target="#result"
                                                    class="text-info">Result </a>
                                                <a href="#">LMIS </a>
                                                <a href="#" data-toggle="modal" data-target="#comment-dialog"
                                                    class="text-info">Comment </a>
                                            </td>
                                            <td><button type="button" class="btn btn-sm btn-toggle" data-toggle="button"
                                                    aria-pressed="false" autocomplete="off">
                                                    <div class="handle"></div>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- result modal content -->
                            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="result-popup"
                                aria-hidden="true" style="display: none;" id="result">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="result-popup">Radiology Investigations -
                                                Result</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row justify-content-between">
                                                <div class="col-md-7 col-12">
                                                    <h4>Test Name - Full Neck Scan</h4>
                                                </div>
                                                <div class="col-md-5 col-12">
                                                    <h4 class="text-right">Lab Order Id : L0000002821</h4>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="bg-secondary">
                                                        <tr>
                                                            <th scope="col">Test</th>
                                                            <th scope="col">Result</th>
                                                            <th scope="col">Range</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Swelling Diameter</td>
                                                            <td>45 - 1000</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="comment">
                                                <p><span class="font-weight-600">Comment</span> : <span
                                                        class="comment-here text-light">Lorem ipsum dolor sit amet,
                                                        consectetur adipisicing elit. </span></p>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th colspan="2" class="b-0">Test By</th>
                                                            <th colspan="2" class="b-0">Signed By</th>
                                                        </tr>
                                                        <tr class="bg-secondary">
                                                            <td>Donald jr</td>
                                                            <td>Time : 11-8-2017 04:22</td>
                                                            <td>Lous Clark</td>
                                                            <td>Time : 11-8-2017 04:22</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger pull-right"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-info pull-right">Print</button>
                                            <button type="button" class="btn btn-success pull-right">Save</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->


                            <!-- comment modal content -->
                            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="comment-popup"
                                aria-hidden="true" style="display: none;" id="comment-dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="comment-popup">Radiology Investigations -
                                                Comment</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row justify-content-between">
                                                <div class="col-12">
                                                    <h4>Comment</h4>
                                                </div>
                                            </div>
                                            <form>
                                                <div class="form-group">
                                                    <textarea class="form-control" id="comment-area" rows="3"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger pull-right"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-success pull-right mr-10">Save</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-lg-4 col-12">

                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title pt-5">Current Vitals</h4>
                            <div class="box-controls pull-right">
                                <div class="lookup lookup-circle lookup-right">
                                    <input type="text" name="s" placeholder="Patients id">
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="flexbox bb-1 mb-15">
                                <div>
                                    <p><span class="text-light">Patient Name:</span> <strong>Jonsahn</strong></p>
                                </div>
                                <div>
                                    <p><span class="text-light">Patient Id:</span> <strong>1254896</strong></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row bb-1 pb-10">
                                        <div class="col-4">
                                            <img class="img-fluid float-left w-30 mt-10 mr-10" src="../images/weight.png"
                                                alt="">
                                            <div>
                                                <p class="mb-0"><small>Weight</small></p>
                                                <h5 class="text-black mb-0"><strong>230 ibs</strong></h5>
                                            </div>
                                        </div>
                                        <div class="col-4 bl-1">
                                            <img class="img-fluid float-left w-30 mt-10 mr-10" src="../images/human.png"
                                                alt="">
                                            <div>
                                                <p class="mb-0"><small>Height</small></p>
                                                <h5 class="text-black mb-0"><strong>6’1</strong></h5>
                                            </div>
                                        </div>
                                        <div class="col-4 bl-1">
                                            <img class="img-fluid float-left w-30 mt-10 mr-10" src="../images/bmi.png"
                                                alt="">
                                            <div>
                                                <p class="mb-0"><small>BMI</small></p>
                                                <h5 class="text-black mb-0"><strong>30.34</strong></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row pt-5">
                                        <div class="col-12">
                                            <span class="text-danger">Blood Pressure</span>
                                        </div>
                                        <div class="col-6">
                                            <div class="progress progress-xs mb-0 mt-5 w-40">
                                                <div class="progress-bar progress-bar-success progress-bar-striped"
                                                    role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                    aria-valuemax="100" style="width: 100%">
                                                </div>
                                            </div>
                                            <h2 class="float-left mt-0 mr-10"><strong>150</strong></h2>
                                            <div>
                                                <p class="mb-0"><small>Systolic</small></p>
                                                <p class="text-black mb-0 mt-0"><small
                                                        class="vertical-align-super">mmHg</small></p>
                                            </div>
                                        </div>
                                        <div class="col-6 bl-1">
                                            <div class="progress progress-xs mb-0 mt-5 w-40">
                                                <div class="progress-bar progress-bar-success progress-bar-striped"
                                                    role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                    aria-valuemax="100" style="width: 100%">
                                                </div>
                                            </div>
                                            <h2 class="float-left mt-0 mr-10"><strong>90</strong></h2>
                                            <div>
                                                <p class="mb-0"><small>Diastolic</small></p>
                                                <p class="text-black mb-0 mt-0"><small
                                                        class="vertical-align-super">mmHg</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body pt-0">
                            <p><small>Recorded on 25/05/2018</small></p>
                        </div>
                        <div class="box-body bg-danger">
                            <img src="{{ URL::asset('dashboard/images/smoking.png') }}" alt=""
                                class="float-left mr-10">
                            <p>Smoking Status : current every day smoker</p>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">New Patient List</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Username</th>
                                            <th>Diseases</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Johen</td>
                                            <td>Doe</td>
                                            <td>@Mickl</td>
                                            <td><span class="label label-danger">Fever</span> </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Johen</td>
                                            <td>Doe</td>
                                            <td>@Mickl</td>
                                            <td><span class="label label-info">Cancer</span> </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Johen</td>
                                            <td>Doe</td>
                                            <td>@Mickl</td>
                                            <td><span class="label label-warning">Lakva</span> </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Johen</td>
                                            <td>Doe</td>
                                            <td>@Mickl</td>
                                            <td><span class="label label-success">Dental</span> </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Johen</td>
                                            <td>Doe</td>
                                            <td>@Mickl</td>
                                            <td><span class="label label-info">Cancer</span> </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Johen</td>
                                            <td>Doe</td>
                                            <td>@Mickl</td>
                                            <td><span class="label label-success">Dental</span> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <!-- TO DO List -->
                    <div class="box box-solid box-warning">
                        <div class="box-header with-border">
                            <i class="ion ion-clipboard"></i>
                            <h4 class="box-title">To Do List</h4>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-close" href="#"></a></li>
                                <li><a class="box-btn-slide" href="#"></a></li>
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <ul class="todo-list">
                                <li>
                                    <!-- drag handle -->
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <!-- checkbox -->
                                    <input type="checkbox" id="basic_checkbox_1" class="filled-in">
                                    <label for="basic_checkbox_1" class="mb-0 h-15 ml-15"></label>
                                    <!-- todo text -->
                                    <span class="text-line">Nulla vitae purus</span>
                                    <!-- Emphasis label -->
                                    <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                                    <!-- General tools such as edit or delete-->
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <!-- checkbox -->
                                    <input type="checkbox" id="basic_checkbox_2" class="filled-in">
                                    <label for="basic_checkbox_2" class="mb-0 h-15 ml-15"></label>
                                    <span class="text-line">Phasellus interdum</span>
                                    <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <!-- checkbox -->
                                    <input type="checkbox" id="basic_checkbox_3" class="filled-in">
                                    <label for="basic_checkbox_3" class="mb-0 h-15 ml-15"></label>
                                    <span class="text-line">Quisque sodales</span>
                                    <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <!-- checkbox -->
                                    <input type="checkbox" id="basic_checkbox_4" class="filled-in">
                                    <label for="basic_checkbox_4" class="mb-0 h-15 ml-15"></label>
                                    <span class="text-line">Proin nec mi porta</span>
                                    <small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <!-- checkbox -->
                                    <input type="checkbox" id="basic_checkbox_5" class="filled-in">
                                    <label for="basic_checkbox_5" class="mb-0 h-15 ml-15"></label>
                                    <span class="text-line">Maecenas scelerisque</span>
                                    <small class="badge bg-primary"><i class="fa fa-clock-o"></i> 1 week</small>
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <!-- checkbox -->
                                    <input type="checkbox" id="basic_checkbox_6" class="filled-in">
                                    <label for="basic_checkbox_6" class="mb-0 h-15 ml-15"></label>
                                    <span class="text-line">Vivamus nec orci</span>
                                    <small class="badge bg-info"><i class="fa fa-clock-o"></i> 1 month</small>
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <!-- /.box -->
                    </div>

                </div>
            </div> --}}
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@stop
@section('scripts')
    @if (Session::has('access_granted'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Sorry...',
                text: "{{ Session::get('access_granted') }}"
            })
        </script>
    @endif
@endsection

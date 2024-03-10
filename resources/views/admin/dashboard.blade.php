@extends('admin.app-admin')
@section('title') Dashboard @endsection
@section('page-header')
@php
    
@endphp

@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

      <div class="card card-info collapsed-card">
        <div class="card-header" data-card-widget="collapse">
          <h3 class="card-title">Data Filter</h3>
          <div class="card-tools" style="margin-top: -3px">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <form class="form-horizontal">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Select Financial Year: </label>
                  <select name="financial_year" id="financial_year" class="form-control select2" onchange="changeFinancialYear()">
                    <option value="" disabled selected>Select Financial Year</option>
                    @foreach($financial_years as $key => $year)
                      <option value="{{ $year }}" data-financial-year="{{ $key }}" @if($key == 0) selected @endif>{{ $year }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Select Duration: </label>
                  <input type="text" id="durationTime" class="form-control float-right">
                </div>
              </div>
            </div>
          </form>
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-right">
          <button type="button" class="btn btn-default mr-2" onclick="resetFilter()">Cancel</button>
          <button type="button" class="btn btn-info" onclick="applyFilter()">Filter</button>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="total-income"></h3>

                <p>Total Income</p>
              </div>
              <div class="icon">
                <i class="fas fa-rupee-sign"></i>
              </div>
              <a href="{{ route('admin.invoice') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="total-expense"></h3>

                <p>Total Expense</p>
              </div>
              <div class="icon">
                <i class="far fa-money-bill-alt"></i>
              </div>
              <a href="{{ route('admin.expense') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner" style="padding-bottom: 40px">
                <h3 id="total-balance"></h3>

                <p>Total Balance</p>
              </div>
              <div class="icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <!-- <a href="{{ route('admin.invoice') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
           
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>65</h3>

                <p>Unique Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div> 
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
               {{--  <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Sales
                </h3>--}}
               {{--  <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#curve_chart" data-toggle="tab">Area</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li>
                  </ul>
                </div> --}}
              </div>
            <div class="card-body">
                <div class="tab-content p-0">
                 
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; height: 300px;">
                        <!--Load the AJAX API-->
                      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                      {{-- 
					  <script type="text/javascript">
                         google.charts.load('current', {'packages':['bar']});
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {
                              var data = google.visualization.arrayToDataTable([
                                ['Year', 'Sales'],
                                @foreach($data as $item)
                     [ '{{$item->Year}}', '{{ $item->TotalSales}}' ],
                                
                               
                                @endforeach
                              ]);

                              var options = {
                                vAxes: {
                                        0: {baseline: 0},
                                      },
                                chart: {
                                  title: 'Company Performance',
                                  subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                                },
                                bars: 'vertical',
                                vAxis: {format: 'decimal'},
                                height: 300,
                                colors: ['#1b9e77', '#d95f02', '#7570b3']
                              };

                              var chart = new google.charts.Bar(document.getElementById('chart_div'));

                              chart.draw(data, google.charts.Bar.convertOptions(options));

                        
                            }
                        
                       /*  google.charts.load('current', {'packages':['bar']});
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {
                              var data = google.visualization.arrayToDataTable([
                                ['Year', 'Sales', 'Expenses', 'Profit'],
                                  @foreach($data as $item)
                                      ['{{ $item->Year}}', '{{ $item->TotalSales}}', '{{ $item->BasicAmount}}', '{{ $item->Gst}}'],
                                    @endforeach
                               
                              ]);

                              var options = {
                                chart: {
                                  title: 'Company Performance',
                                  subtitle: 'Sales, BasicAmount, and GST',
                                }
                              };

                              var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                              chart.draw(data, google.charts.Bar.convertOptions(options));
                            } */
                      </script> 
					  --}}
                      <div id="chart_div" ></div>
                        

                          
                            
                      {{-- <script type="text/javascript">
                            google.charts.load('current', {'packages':['corechart']});
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() {
                                  var data = google.visualization.arrayToDataTable([
                                    ['Month','Sales','Year'],

                                    {{$chartData}}
                                  
                                  ]);

                                  var options = {
                                    title: 'Company Performance',
                                    curveType: 'function',
                                    legend: { position: 'bottom' }
                                  };

                                  var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                                  chart.draw(data, options);
                                }
                      </script>
                       <div id="curve_chart" style="width: auto; height: 300px;"></div> --}}





                         {{-- <div id="piechart" style="width: auto; height: 300px;"></div> --}}
                      {{-- <div id="curve_chart" style="width: auto; height: 300px"></div> --}}
                     {{--   <canvas id="curve_chart" height="300" style="height: 300px;"></canvas> --}}
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    {{-- <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas> --}}
                  </div>
                </div>
              </div>
            </div>
           

            <!-- DIRECT CHAT -->
            <!--<div class="card direct-chat direct-chat-primary">-->
			<!--
             <div class="card-header">
                <h3 class="card-title">Direct Chat</h3>

                <div class="card-tools">
                  <span title="3 New Messages" class="badge badge-primary">3</span>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                    <i class="fas fa-comments"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div> 
			  -->
              <!-- /.card-header -->
			  <!--
               <div class="card-body"> 
                Conversations are loaded here -->
                <!--<div class="direct-chat-messages"> -->
                  <!-- Message. Default to the left -->
				  <!--
                   <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left">Alexander Pierce</span>
                      <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                    </div>
                    
                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                    
                    <div class="direct-chat-text">
                      Is this template really for free? That's unbelievable!
                    </div>
                    
                  </div> 
				  -->
                  <!-- /.direct-chat-msg -->

                  <!-- Message to the right -->
				  <!--
                   <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-right">Sarah Bullock</span>
                      <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                    </div>
                   
                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                    
                    <div class="direct-chat-text">
                      You better believe it!
                    </div>
                    
                  </div> 
				  -->
                  <!-- /.direct-chat-msg -->

                  <!-- Message. Default to the left -->
				  <!--
                <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left">Alexander Pierce</span>
                      <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                    </div>
                    
                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                   
                    <div class="direct-chat-text">
                      Working with AdminLTE on a great new app! Wanna join?
                    </div>
                    
                  </div>
				  -->
                  <!-- /.direct-chat-msg -->

                  <!-- Message to the right -->
				  <!--
                  <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-right">Sarah Bullock</span>
                      <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                    </div>
                    
                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                    
                    <div class="direct-chat-text">
                      I would love to.
                    </div>
                   
                  </div> 
				  -->
                  <!-- /.direct-chat-msg -->

               <!-- </div>-->
                <!--/.direct-chat-messages-->

                <!-- Contacts are loaded here -->
				<!--
                 <div class="direct-chat-contacts"> 
                   <ul class="contacts-list">
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Avatar">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Count Dracula
                            <small class="contacts-list-date float-right">2/28/2015</small>
                          </span>
                          <span class="contacts-list-msg">How have you been? I was...</span>
                        </div>
                        
                      </a>
                    </li>
                    
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="dist/img/user7-128x128.jpg" alt="User Avatar">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Sarah Doe
                            <small class="contacts-list-date float-right">2/23/2015</small>
                          </span>
                          <span class="contacts-list-msg">I will be waiting for...</span>
                        </div>
                        
                      </a>
                    </li>
                    
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="dist/img/user3-128x128.jpg" alt="User Avatar">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Nadia Jolie
                            <small class="contacts-list-date float-right">2/20/2015</small>
                          </span>
                          <span class="contacts-list-msg">I'll call you back at...</span>
                        </div>
                        
                      </a>
                    </li>
                    
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="dist/img/user5-128x128.jpg" alt="User Avatar">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Nora S. Vans
                            <small class="contacts-list-date float-right">2/10/2015</small>
                          </span>
                          <span class="contacts-list-msg">Where is your new...</span>
                        </div>
                        
                      </a>
                    </li>
                    
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="dist/img/user6-128x128.jpg" alt="User Avatar">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            John K.
                            <small class="contacts-list-date float-right">1/27/2015</small>
                          </span>
                          <span class="contacts-list-msg">Can I take a look at...</span>
                        </div>
                       
                      </a>
                    </li>
                    
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="dist/img/user8-128x128.jpg" alt="User Avatar">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Kenneth M.
                            <small class="contacts-list-date float-right">1/4/2015</small>
                          </span>
                          <span class="contacts-list-msg">Never mind I found...</span>
                        </div>
                        
                      </a>
                    </li>
                    
                  </ul>
                  
               </div>
                -->
              <!--</div>-->
              <!--
             <div class="card-footer">
                <form action="#" method="post">
                  <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                      <button type="button" class="btn btn-primary">Send</button>
                    </span>
                  </div>
                </form>
              </div> 
			  -->
              <!-- /.card-footer-->
           <!-- </div>-->
            <!--/.direct-chat -->

            <!-- TO DO List -->
           <!-- <div class="card">-->
			<!--
              <div class="card-header">
                <h3 class="card-title">
                  <i class="ion ion-clipboard mr-1"></i>
                  To Do List
                </h3>

                <div class="card-tools">
                  <ul class="pagination pagination-sm">
                    <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                  </ul>
                </div>
              </div> 
			  -->
              <!-- /.card-header -->
			  <!--
               <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                  <li>
                    
                    <span class="handle">
                      <i class="fas fa-ellipsis-v"></i>
                      <i class="fas fa-ellipsis-v"></i>
                    </span>
                    
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo1" id="todoCheck1">
                      <label for="todoCheck1"></label>
                    </div>
                    
                    <span class="text">Design a nice theme</span>
                    
                    <small class="badge badge-danger"><i class="far fa-clock"></i> 2 mins</small>
                    
                    <div class="tools">
                      <i class="fas fa-edit"></i>
                      <i class="fas fa-trash-o"></i>
                    </div>
                  </li>
                  <li>
                    <span class="handle">
                      <i class="fas fa-ellipsis-v"></i>
                      <i class="fas fa-ellipsis-v"></i>
                    </span>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo2" id="todoCheck2" checked>
                      <label for="todoCheck2"></label>
                    </div>
                    <span class="text">Make the theme responsive</span>
                    <small class="badge badge-info"><i class="far fa-clock"></i> 4 hours</small>
                    <div class="tools">
                      <i class="fas fa-edit"></i>
                      <i class="fas fa-trash-o"></i>
                    </div>
                  </li>
                  <li>
                    <span class="handle">
                      <i class="fas fa-ellipsis-v"></i>
                      <i class="fas fa-ellipsis-v"></i>
                    </span>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo3" id="todoCheck3">
                      <label for="todoCheck3"></label>
                    </div>
                    <span class="text">Let theme shine like a star</span>
                    <small class="badge badge-warning"><i class="far fa-clock"></i> 1 day</small>
                    <div class="tools">
                      <i class="fas fa-edit"></i>
                      <i class="fas fa-trash-o"></i>
                    </div>
                  </li>
                  <li>
                    <span class="handle">
                      <i class="fas fa-ellipsis-v"></i>
                      <i class="fas fa-ellipsis-v"></i>
                    </span>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo4" id="todoCheck4">
                      <label for="todoCheck4"></label>
                    </div>
                    <span class="text">Let theme shine like a star</span>
                    <small class="badge badge-success"><i class="far fa-clock"></i> 3 days</small>
                    <div class="tools">
                      <i class="fas fa-edit"></i>
                      <i class="fas fa-trash-o"></i>
                    </div>
                  </li>
                  <li>
                    <span class="handle">
                      <i class="fas fa-ellipsis-v"></i>
                      <i class="fas fa-ellipsis-v"></i>
                    </span>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo5" id="todoCheck5">
                      <label for="todoCheck5"></label>
                    </div>
                    <span class="text">Check your messages and notifications</span>
                    <small class="badge badge-primary"><i class="far fa-clock"></i> 1 week</small>
                    <div class="tools">
                      <i class="fas fa-edit"></i>
                      <i class="fas fa-trash-o"></i>
                    </div>
                  </li>
                  <li>
                    <span class="handle">
                      <i class="fas fa-ellipsis-v"></i>
                      <i class="fas fa-ellipsis-v"></i>
                    </span>
                    <div  class="icheck-primary d-inline ml-2">
                      <input type="checkbox" value="" name="todo6" id="todoCheck6">
                      <label for="todoCheck6"></label>
                    </div>
                    <span class="text">Let theme shine like a star</span>
                    <small class="badge badge-secondary"><i class="far fa-clock"></i> 1 month</small>
                    <div class="tools">
                      <i class="fas fa-edit"></i>
                      <i class="fas fa-trash-o"></i>
                    </div>
                  </li>
                </ul>
              </div> 
			  -->
              <!-- /.card-body -->
              <!-- <div class="card-footer clearfix">
                <button type="button" class="btn btn-info float-right"><i class="fas fa-plus"></i> Add item</button>
              </div> -->
            <!--</div>-->
            <!-- /.card -->
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
         <!-- <section class="col-lg-5 connectedSortable">-->

            <!--
            <div class="card bg-gradient-primary">
             <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-map-marker-alt mr-1"></i>
                  Visitors
                </h3>
                
                <div class="card-tools">
                  <button type="button" class="btn btn-primary btn-sm daterange" title="Date range">
                    <i class="far fa-calendar-alt"></i>
                  </button>
                  <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
               
              </div> 
              <<div class="card-body">
                <div id="world-map" style="height: 250px; width: 100%;"></div>
              </div> 
              
              <div class="card-footer bg-transparent">
                <div class="row">
                  <div class="col-4 text-center">
                    <div id="sparkline-1"></div>
                    <div class="text-white">Visitors</div>
                  </div>
                  
                  <div class="col-4 text-center">
                    <div id="sparkline-2"></div>
                    <div class="text-white">Online</div>
                  </div>
                  
                  <div class="col-4 text-center">
                    <div id="sparkline-3"></div>
                    <div class="text-white">Sales</div>
                  </div>
                  
                </div>
                
              </div> 
            </div>  
			-->
            <!-- solid sales graph -->
           <!-- <div class="card bg-gradient-info">-->
             <!--<div class="card-header border-0">-->
                <!--<h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Sales Graph
                </h3>
				-->
                <!--<div class="card-tools">-->
                 <!-- <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>-->
                <!--</div>-->
              <!--</div> -->
              <!--<div class="card-body">-->
               <!-- <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>-->
              <!--</div> -->
              <!-- /.card-body -->
              <!--<div class="card-footer bg-transparent">-->
               <!-- <div class="row"> -->
                  <!--<div class="col-4 text-center">-->
                    <!--<input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60" data-fgColor="#39CCCC"/>-->

                    <!--<div class="text-white">Mail-Orders</div>-->
                  <!--</div> -->
                  <!-- ./col -->
                 <!--<div class="col-4 text-center">
                    <!--<input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#39CCCC"/>-->

                    <!--<div class="text-white">Online</div>-->
                  <!--</div> -->
                  <!-- ./col -->
                  <!-- <div class="col-4 text-center"> -->
                   <!-- <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgColor="#39CCCC"/>-->

                   <!-- <div class="text-white">In-Store</div>-->
                  <!--</div>  -->
                  <!-- ./col -->
                 <!--</div> -->
                <!-- /.row -->
             <!-- </div> -->
              <!-- /.card-footer -->
           <!-- </div>-->
            <!-- /.card -->

            <!-- Calendar -->
           <!-- <div class="card bg-gradient-success">  -->
              <!-- <div class="card-header border-0"> -->
				<!--
              <h3 class="card-title">
                  <i class="far fa-calendar-alt"></i>
                  Calendar
                </h3>
                -->
              <!-- <div class="card-tools">  -->
                  
                  <!-- <div class="btn-group"> -->
                    <!--<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                      <i class="fas fa-bars"></i>
                    </button>-->
                    <!--<div class="dropdown-menu" role="menu"> -->
						<!--
                      <a href="#" class="dropdown-item">Add new event</a>
                      <a href="#" class="dropdown-item">Clear events</a>
                      <div class="dropdown-divider"></div>
                      <a href="#" class="dropdown-item">View calendar</a>
					  -->
                   <!-- </div> -->
                  <!--</div>  -->
                   <!--<button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>-->
               <!-- </div>  -->
                <!-- /. tools -->
             <!-- </div> -->
              <!-- /.card-header -->
				<!--<div class="card-body pt-0"> -->
                <!--The calendar -->
                <!--<div id="calendar" style="width: 100%"></div> -->
             <!-- </div> -->
              <!-- /.card-body -->
           <!--</div> -->
            <!-- /.card -->
          <!--</section>-->
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
   {{--  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  // Load the Visualization API and the corechart package.
  google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Topping');
  data.addColumn('number', 'Slices');
  data.addRows([
    ['Mushrooms', 3],
    ['Onions', 1],
    ['Olives', 1],
    ['Zucchini', 1],
    ['Pepperoni', 2]
  ]);

  // Set chart options
  var options = {'title':'How Much Pizza I Ate Last Night',
                 'width':400,
                 'height':300};

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}
</script>
    <div id="chart_div" style="width: auto; height: 150px"></div> --}}
    <div class="card bg-gradient-info">
     {{--  {{$chartData}} <br> --}}
       {{--  @foreach ($data as $item) 

          {{ $item->TotalSales}}
          {{ $item->Year}}
          {{ $item->Month}}

       @endforeach --}}
    </div>
@endsection
@section('footer_script')
<!-- AdminLTE for demo purposes -->
<script src="{{ Helper::assets('js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ Helper::assets('js/pages/dashboard.js') }}"></script>
@endsection
@section('footer_content')
  <script>
    $('#durationTime').daterangepicker({ 
      locale: {
        format: 'DD/MM/YYYY'
      } 
    });

    changeFinancialYear();

    function changeFinancialYear() {
      let financialYear = $('#financial_year').val();
      let financialYears = financialYear.split('-');
      $('#durationTime').data('daterangepicker').setStartDate(`01/04/${financialYears[0]}`);
      $('#durationTime').data('daterangepicker').setEndDate(`31/03/${financialYears[0].substring(0, 2)}${financialYears[1]}`);
    }

    applyFilter();

    function applyFilter() {
      const payload = {
        duration: $('#durationTime').val(),
        _token: "{{csrf_token()}}"
      }
      $.ajax({
        type: "post",
        url: "{{ route('admin.filter') }}",
        data: {...payload},
        success: function(data) {
          
          const response = JSON.parse(data);
          
          $('#total-income').text(response.total_income);
          $('#total-expense').text(response.total_expense);
          $('#total-balance').text(response.total_income - response.total_expense);
        }
      });
    }

    function resetFilter() {
      $('#financial_year').val($('option[data-financial-year="0"]').val()).change();
      applyFilter();
    }

    
  </script>
@endsection

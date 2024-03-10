<!--Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <?php 
  $companyList = \App\Models\Company::where('deleted_at',null)->select('id','company_name')->get();
  $walletbalnce = Helper::walletBalance(\Session::get('company'));
  ?>
  <ul class="navbar-nav d-flex align-items-center">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li>
      <b> Welcome, {{Auth::User()->name}} </b>
    </li>
    
  </ul>

  <!-- Right navbar links -->

  <ul class="navbar-nav ml-auto d-flex align-items-center">
    <!-- <li class="nav-item">
      <span class="hidden-xs">Wallet Amount : ₹ 100000</span>
    </li> -->
    <li class="nav-item">
    <b> {{\App\Models\Company::where('id',\Session::get('company'))->where('deleted_at',null)->value('company_name')}}</b>
    </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <!-- <i class="fa fa-wallet fa-3x"></i> -->Wallet Amount: ₹ {{$walletbalnce}}
          <!-- <span class="badge badge-danger navbar-badge">₹ 100000</span> -->
        </a>
      </li>
    @if(Auth::user()->type == 1 )
      <li class="nav-item">
        <select class="form-control select2" name="ddlCompany" id="ddlCompany">
          @foreach($companyList as $cl)
          <option value="{{$cl->id}}" @if($cl->id == \Session::get('company')) selected @endif>{{$cl->company_name}}</option>
          @endforeach
        </select> 
      </li>
    @endif
    <li class="nav-item ml-3" id="clockbox">
    
    </li>
    <li class="dropdown user user-menu" style="margin-top: -15px;">
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;" autocomplete="off">@csrf</form>
          <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link d-inline-block">
            <img src="{{ asset('img/log-out-icon.png') }}" alt="Sign Out" class="img-size-50 img-circle">
          </a>
      <!-- <ul class="dropdown-menu">
        
        <li class="user-header">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

          <p>
            {{ Auth::user()->name ?? '' }}
          </p>
        </li>
        
        <li class="user-body">
          <div class="row">
            <div class="col-xs-4 text-center">
            <a href="#">Followers</a>
            </div>
            <div class="col-xs-4 text-center">
            <a href="#">Sales</a>
            </div>
            <div class="col-xs-4 text-center">
            <a href="#">Friends</a>
            </div>
          </div>
        
        </li>
        
        <li class="user-footer">
          <div class="pull-left">
            <a href="#" class="btn btn-default btn-flat">Profile</a>
          </div>
          <div class="pull-right">
            <a class="btn btn-default btn-flat" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="icon-switch2"></i>Logout</a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;" autocomplete="off">@csrf</form>
          </div>
        </li>
      </ul> -->
    </li>
  </ul>
</nav>
<!-- /.navbar

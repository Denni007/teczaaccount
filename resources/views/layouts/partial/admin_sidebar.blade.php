<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('admin.index') }}" class="brand-link">
    <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    @php
      $words = preg_split("/[\s,_-]+/", \App\Models\Company::where('id',\Session::get('company'))->where('deleted_at',null)->value('company_name'));
      $company_name = '';
      foreach ($words as $w) {
        $company_name .= $w[0];
      }
    @endphp
    <span class="brand-text font-weight-light" title="{{\App\Models\Company::where('id',\Session::get('company'))->where('deleted_at',null)->value('company_name')}}">{{ str_word_count(\App\Models\Company::where('id',\Session::get('company'))->where('deleted_at',null)->value('company_name')) > 2 ? $company_name : \App\Models\Company::where('id',\Session::get('company'))->where('deleted_at',null)->value('company_name') }}</span>
  </a>
  <?php
  if (Auth::check()) {
    $permissions = Helper::permissions();
    //dd($permissions); 
  } else {
    $permissions = [];
  }
  ?>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- dashboard -->
        <li class="nav-item">
          <a href="{{ route('admin.index') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.index'])) ? 'active' : '' ) }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- accpimt management -->
        <li class="nav-item {{ ((in_array(Route::currentRouteName(),['admin.receipt', 'admin.receipt.add', 'admin.receipt.view', 'admin.payment', 'admin.payment.add', 'admin.payment.view', 'admin.expense', 'admin.expense.add', 'admin.expense.view'])) ? 'menu-open' : '' ) }}">
          <a href="#" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.receipt', 'admin.receipt.add', 'admin.receipt.view', 'admin.payment', 'admin.payment.add', 'admin.payment.view', 'admin.expense', 'admin.expense.add', 'admin.expense.view'])) ? 'active' : '' ) }}">
            <i class="nav-icon fas fa-user-alt nav-icon"></i>
            <p>
              Financial Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(empty($permissions))
            <li class="nav-item">
              <a href="{{ route('admin.receipt') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.receipt', 'admin.receipt.add', 'admin.receipt.view'])) ? 'active' : '' ) }}">
                <i class="far fa-money-bill-alt nav-icon"></i>
                <p>Receipt</p>
              </a>
            </li>
            @elseif(!empty($permissions) && in_array('receipt_list',$permissions))
            <li class="nav-item">
              <a href="{{ route('admin.receipt') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.receipt', 'admin.receipt.add', 'admin.receipt.view'])) ? 'active' : '' ) }}">
                <i class="far fa-money-bill-alt nav-icon"></i>
                <p>Receipt</p>
              </a>
            </li>
            @endif

            @if(empty($permissions))
            <li class="nav-item">
              <a href="{{ route('admin.payment') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.payment', 'admin.payment.add', 'admin.payment.view'])) ? 'active' : '' ) }}">
                <i class="far fa-credit-card nav-icon"></i>
                <p>Payment</p>
              </a>
            </li>
            @elseif(!empty($permissions) && in_array('payment_list',$permissions))
            <li class="nav-item">
              <a href="{{ route('admin.payment') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.payment', 'admin.payment.add', 'admin.payment.view'])) ? 'active' : '' ) }}">
                <i class="far fa-credit-card nav-icon"></i>
                <p>Payment</p>
              </a>
            </li>
            @endif

            @if(empty($permissions))
            <li class="nav-item">
              <a href="{{ route('admin.expense') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.expense', 'admin.expense.add', 'admin.expense.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-money-check nav-icon"></i>
                <p>Expense</p>
              </a>
            </li>
            @elseif(!empty($permissions) && in_array('payment_list',$permissions))
            <li class="nav-item">
              <a href="{{ route('admin.expense') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.expense', 'admin.expense.add', 'admin.expense.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-money-check nav-icon"></i>
                <p>Expense</p>
              </a>
            </li>
            @endif
          </ul>
        </li>

        <!-- invoice management -->
        @if(empty($permissions))
        <li class="nav-item {{ ((in_array(Route::currentRouteName(),['admin.invoice', 'admin.invoice.add', 'admin.invoice.view', 'admin.perfoma-invoice', 'admin.perfoma-invoice.add', 'admin.perfoma-invoice.view'])) ? 'menu-open' : '' ) }}">
          <a href="#" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.invoice', 'admin.invoice.add', 'admin.invoice.view', 'admin.perfoma-invoice', 'admin.perfoma-invoice.add', 'admin.perfoma-invoice.view'])) ? 'active' : '' ) }}">
            <i class="nav-icon far fa-file-pdf nav-icon"></i>
            <p>
              Sales Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.invoice') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.invoice', 'admin.invoice.add', 'admin.invoice.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-file-invoice nav-icon"></i>
                <p>Sales Invoice</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.perfoma-invoice') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.perfoma-invoice', 'admin.perfoma-invoice.add', 'admin.perfoma-invoice.view'])) ? 'active' : '' ) }}">
                <i class="far fa-file-alt nav-icon"></i>
                <p>Perfoma Invoice</p>
              </a>
            </li>
          </ul>
        </li>
        @elseif(!empty($permissions) && in_array('invoice_list',$permissions))
        <li class="nav-item {{ ((in_array(Route::currentRouteName(),['admin.invoice', 'admin.invoice.add', 'admin.invoice.view', 'admin.perfoma-invoice', 'admin.perfoma-invoice.add', 'admin.perfoma-invoice.view'])) ? 'menu-open' : '' ) }}">
          <a href="#" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.invoice', 'admin.invoice.add', 'admin.invoice.view', 'admin.perfoma-invoice', 'admin.perfoma-invoice.add', 'admin.perfoma-invoice.view'])) ? 'active' : '' ) }}">
            <i class="nav-icon far fa-file-pdf nav-icon"></i>
            <p>
              Sales Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.invoice') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.invoice', 'admin.invoice.add', 'admin.invoice.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-file-invoice nav-icon"></i>
                <p>Invoice</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.perfoma-invoice') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.perfoma-invoice', 'admin.perfoma-invoice.add', 'admin.perfoma-invoice.view'])) ? 'active' : '' ) }}">
                <i class="far fa-file-alt nav-icon"></i>
                <p>Perfoma Invoice</p>
              </a>
            </li>
          </ul>
        </li>
        @endif

        <!-- purchase management -->
        <li class="nav-item {{ ((in_array(Route::currentRouteName(),['admin.purchase', 'admin.purchase.add', 'admin.purchase.view', 'admin.purchase-order', 'admin.purchase-order.add', 'admin.purchase-order.view'])) ? 'menu-open' : '' ) }}">
          <a href="#" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.purchase', 'admin.purchase.add', 'admin.purchase.view', 'admin.purchase-order', 'admin.purchase-order.add', 'admin.purchase-order.view'])) ? 'active' : '' ) }}">
            <i class="nav-icon fas fa-shopping-cart nav-icon"></i>
            <p>
              Purchase Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(empty($permissions))
            <li class="nav-item">
              <a href="{{ route('admin.purchase') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.purchase', 'admin.purchase.add', 'admin.purchase.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-shopping-basket nav-icon"></i>
                <p>Purchase</p>
              </a>
            </li>
            @elseif(!empty($permissions) && in_array('invoice_list',$permissions))
            <li class="nav-item">
              <a href="{{ route('admin.purchase') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.purchase', 'admin.purchase.add', 'admin.purchase.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-shopping-basket nav-icon"></i>
                <p>Purchase</p>
              </a>
            </li>
            @endif

            @if(empty($permissions))
            <li class="nav-item">
              <a href="{{ route('admin.purchase-order') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.purchase-order', 'admin.purchase-order.add', 'admin.purchase-order.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-server nav-icon"></i>
                <p>Purchase Order</p>
              </a>
            </li>
            @elseif(!empty($permissions) && in_array('invoice_list',$permissions))
            <li class="nav-item">
              <a href="{{ route('admin.purchase-order') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.purchase-order', 'admin.purchase-order.add', 'admin.purchase-order.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-server nav-icon"></i>
                <p>Purchase Order</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        
        <!-- product management -->
        <li class="nav-item {{ ((in_array(Route::currentRouteName(),['admin.production', 'admin.production.add', 'admin.production.view', 'admin.product', 'admin.product.add', 'admin.product.view', 'raw-material.index', 'admin.product_type','admin.product_type.add','admin.product_type.view','raw-material.create','raw-material.edit'])) ? 'menu-open' : '' ) }}">
          <a href="#" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.production', 'admin.production.add', 'admin.production.view', 'admin.product', 'admin.product.add', 'admin.product.view', 'raw-material.index', 'admin.product_type','admin.product_type.add','admin.product_type.view','raw-material.create','raw-material.edit'])) ? 'active' : '' ) }}">
            <i class="nav-icon fas fa-chart-bar nav-icon"></i>
            <p style="font-size: 15px;">
              Production Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(empty($permissions))
            <li class="nav-item">
              <a href="{{ route('admin.production') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.production', 'admin.production.add', 'admin.production.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-chart-line nav-icon"></i>
                <p>Production</p>
              </a>
            </li>
            @elseif(!empty($permissions) && in_array('invoice_list',$permissions))
            <li class="nav-item">
              <a href="{{ route('admin.production') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.production', 'admin.production.add', 'admin.production.view'])) ? 'active' : '' ) }}">
              <i class="fas fa-chart-line nav-icon"></i>
                <p>Production</p>
              </a>
            </li>
            @endif

            @if(empty($permissions))
            <li class="nav-item">
              <a href="{{ route('admin.product') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.product', 'admin.product.add', 'admin.product.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-box-open nav-icon"></i>
                <p>Product</p>
              </a>
            </li>
            @elseif(!empty($permissions) && in_array('product_list',$permissions))
            <li class="nav-item">
              <a href="{{ route('admin.product') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.product', 'admin.product.add', 'admin.product.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-box-open nav-icon"></i>
                <p>Product</p>
              </a>
            </li>
            @endif

            @if(empty($permissions))
            <li class="nav-item">
              <a href="{{ route('raw-material.index') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['raw-material.index', 'raw-material.create','raw-material.edit']))) ? 'active' : '' }}">
                <i class="fab fa-accusoft nav-icon"></i>
                <p>Raw Material</p>
              </a>
            </li>
            @elseif(!empty($permissions) && in_array('product_list',$permissions))
            <li class="nav-item">
              <a href="{{ route('raw-material.index') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['raw-material.index', 'raw-material.create','raw-material.edit']))) ? 'active' : '' }}">
                <i class="fab fa-accusoft nav-icon"></i>
                <p>Raw Material</p>
              </a>
            </li>
            @endif

            <li class="nav-item">
              <a href="{{ route('admin.product_type') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.product_type','admin.product_type.add','admin.product_type.view']))) ? 'active' : '' }}">
                <i class="fas fa-sitemap nav-icon"></i>
                <p>Product Types</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- company management -->
        @if(Auth::user()->type == 1)
        <li class="nav-item {{ ((in_array(Route::currentRouteName(),['admin.company', 'admin.company.add', 'admin.company.view','admin.user', 'admin.user.add', 'admin.user.view'])) ? 'menu-open' : '' ) }}">
          <a href="#" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.company', 'admin.company.add', 'admin.company.view','admin.user', 'admin.user.add', 'admin.user.view'])) ? 'active' : '' ) }}">
            <i class="nav-icon fas fa-university nav-icon"></i>
            <p>
              Company Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.company') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.company', 'admin.company.add', 'admin.company.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-building nav-icon"></i>
                <p>Company</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.user') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.user', 'admin.user.add', 'admin.user.view'])) ? 'active' : '' ) }}">
                <i class="far fa-user nav-icon"></i>
                <p>User</p>
              </a>
            </li>
          </ul>
        </li>
        @endif
       
        <!-- vendor management -->
        <li class="nav-item {{ ((in_array(Route::currentRouteName(),['admin.vendor', 'admin.vendor.add', 'admin.vendor.view'])) ? 'menu-open' : '' ) }}">
          <a href="#" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.vendor', 'admin.vendor.add', 'admin.vendor.view'])) ? 'active' : '' ) }}">
            <i class="nav-icon fas fa-user-tie nav-icon"></i>
            <p>
              Vendor Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(empty($permissions))
            <li class="nav-item">
              <a href="{{ route('admin.vendor') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.vendor', 'admin.vendor.add', 'admin.vendor.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-user-tie nav-icon"></i>
                <p>Vendor</p>
              </a>
            </li>
            @elseif(!empty($permissions) && in_array('vendor_list',$permissions))
            <li class="nav-item">
              <a href="{{ route('admin.vendor') }}" class="nav-link {{ ((in_array(Route::currentRouteName(),['admin.vendor', 'admin.vendor.add', 'admin.vendor.view'])) ? 'active' : '' ) }}">
                <i class="fas fa-user-tie nav-icon"></i>
                <p>Vendor</p>
              </a>
            </li>
            @endif
          </ul>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
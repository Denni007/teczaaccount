<!-- /.content-wrapper -->
<footer class="main-footer">
   <strong>Copyright &copy; 2021-2022  {{\App\Models\Company::where('id',\Session::get('company'))->where('deleted_at',null)->value('company_name')}}.</strong> All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <!-- <b>Version</b> 3.1.0-rc -->
    </div>
</footer>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
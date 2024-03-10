@extends('admin.app-admin')
@section('title') Payment @endsection
@section('page-header')
@php

@endphp
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Payment </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Payment </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    
    <div class="card card-info collapsed-card mx-2 mb-3">
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

    <div class="card m-2">
        <div class="card-header header-elements-inline">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Payment List</h5>
                <?php 
                    if(Auth::check())
                    {
                        $permissions = Helper::permissions();
                        //dd($permissions);
                    }
                    else
                    {
                        $permissions = [];
                    }
                ?>   
                @if(empty($permissions))
                    <a href="{{ route('admin.payment.add') }}" class="btn btn-primary float-right"> Make Payment</a>
                @elseif(!empty($permissions) && in_array('payment_add',$permissions))
                    <a href="{{ route('admin.payment.add') }}" class="btn btn-primary float-right"> Make Payment</a>
                @endif
            </div>
        </div>
        <!-- <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 mb-2 mb-md-2 mb-lg-0">
                    <div class="controls">
                        <input type="text" name="keyword" placeholder="Search Company Name" class="form-control"
                            id="b_keyword">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-2 mb-md-2 mb-lg-0 padding-top-class">
                    <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                    <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
                </div>
            </div>
        </div> -->
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="datatable">
                <thead>
                    <tr class="text-center">
                        {{--  <th>Id</th>  --}}
                        <th>Invoice No.</th>
                        <th>Payment Method</th>
                        <th>Reference</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th width="15%">Status</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('footer_content')
<script>
    const delete_url = "{{ route('admin.payment.delete') }}";
    const approve_url = "{{ route('admin.payment.approve') }}";
    const reject_url = "{{ route('admin.payment.reject') }}";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var companyTable = "";
    $(document).ready( function () {
        loadData();

        $(document).on('click','.payment-delete', function() {
            var id= $(this).data('id');
            var companyTable_row = $(this).closest("tr");
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                //icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                // confirmButtonClass: 'btn btn-success',
                // cancelButtonClass: 'btn btn-danger',
                // buttonsStyling: false,
                // allowOutsideClick: false,
            }).then(function (confirm) {
                if(confirm.value !== "undefined" && confirm.value){
                    $.ajax({
                        url: delete_url,
                        type: 'POST',
                        data: { id : id, _token: CSRF_TOKEN },
                        beforeSend: function(){
                            // blockBody();
                        },
                        success: function(response) {
                            if(response.status == 200){
                                Swal.fire({
                                    title: response.msg_success,
                                    confirmButtonColor: "#66BB6A",
                                    type: "success",
                                    confirmButtonText: 'OK',
                                    confirmButtonClass: 'btn btn-success',
                                }).then(function (){
                                    companyTable.row(companyTable_row).remove().draw(false);
                                });
                            }else if(response.status == 201){
                                Swal.fire({
                                    title: response.msg_success,
                                    confirmButtonColor: "#FF7043",
                                    confirmButtonClass: 'btn btn-warning',
                                    type: "warning",
                                    confirmButtonText: 'OK',
                                });
                            }else{
                                Swal.fire({
                                    title: response.msg_fail,
                                    confirmButtonColor: "#EF5350",
                                    confirmButtonClass: 'btn btn-danger',
                                    type: "error",
                                    confirmButtonText: 'OK',
                                });
                            }
                        },
                        complete: function(){
                            // unBlockBody();
                        }
                    });
                }
            }, function (dismiss) {
            });
        });
        $(document).on('click','.payment-approve', function() {
            var id= $(this).data('id');
            var companyTable_row = $(this).closest("tr");
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve this payment ?",
                type: 'warning',
                //icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve it!',
                cancelButtonText: 'No, cancel!',
                // confirmButtonClass: 'btn btn-success',
                // cancelButtonClass: 'btn btn-danger',
                // buttonsStyling: false,
                // allowOutsideClick: false,
            }).then(function (confirm) {
                //console.log(confirm.value);
                if(confirm.value !== "undefined" && confirm.value){
                    $.ajax({
                        url: approve_url,
                        type: 'POST',
                        data: { id : id, _token: CSRF_TOKEN },
                        beforeSend: function(){
                            // blockBody();
                        },
                        success: function(response) {
                            if(response.status == 200){
                                Swal.fire({
                                    title: response.msg_success,
                                    confirmButtonColor: "#66BB6A",
                                    type: "success",
                                    confirmButtonText: 'OK',
                                    confirmButtonClass: 'btn btn-success',
                                }).then(function (){
                                    companyTable.columns.adjust().draw();
                                });
                            }else if(response.status == 201){
                                Swal.fire({
                                    title: response.msg_success,
                                    confirmButtonColor: "#FF7043",
                                    confirmButtonClass: 'btn btn-warning',
                                    type: "warning",
                                    confirmButtonText: 'OK',
                                });
                            }else{
                                Swal.fire({
                                    title: response.msg_fail,
                                    confirmButtonColor: "#EF5350",
                                    confirmButtonClass: 'btn btn-danger',
                                    type: "error",
                                    confirmButtonText: 'OK',
                                });
                            }
                        },
                        complete: function(){
                            // unBlockBody();
                        }
                    });
                }
            }, function (dismiss) {
            });
        });
        $(document).on('click','.payment-reject', function() {
            var id= $(this).data('id');
            var companyTable_row = $(this).closest("tr");
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject this payment?",
                type: 'warning',
                //icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                // confirmButtonClass: 'btn btn-success',
                // cancelButtonClass: 'btn btn-danger',
                // buttonsStyling: false,
                // allowOutsideClick: false,
            }).then(function (confirm) {
                if(confirm.value !== "undefined" && confirm.value){
                    $.ajax({
                        url: reject_url,
                        type: 'POST',
                        data: { id : id, _token: CSRF_TOKEN },
                        beforeSend: function(){
                            // blockBody();
                        },
                        success: function(response) {
                            if(response.status == 200){
                                Swal.fire({
                                    title: response.msg_success,
                                    confirmButtonColor: "#66BB6A",
                                    type: "success",
                                    confirmButtonText: 'OK',
                                    confirmButtonClass: 'btn btn-success',
                                }).then(function (){
                                companyTable.columns.adjust().draw();
                                });
                            }else if(response.status == 201){
                                Swal.fire({
                                    title: response.msg_success,
                                    confirmButtonColor: "#FF7043",
                                    confirmButtonClass: 'btn btn-warning',
                                    type: "warning",
                                    confirmButtonText: 'OK',
                                });
                            }else{
                                Swal.fire({
                                    title: response.msg_fail,
                                    confirmButtonColor: "#EF5350",
                                    confirmButtonClass: 'btn btn-danger',
                                    type: "error",
                                    confirmButtonText: 'OK',
                                });
                            }
                        },
                        complete: function(){
                            // unBlockBody();
                        }
                    });
                }
            }, function (dismiss) {
            });
        });

        companyTable.columns.adjust().draw();
    });

    function loadData() {
        $('#datatable').dataTable().fnDestroy();
        companyTable = $('#datatable').DataTable({
            "responsive": true,
            serverSide: true,
            bFilter:false,
            bSort:false,
            ajax: {
                url: "{{ route('admin.payment.filter') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                beforeSend: function(){
                    // blockBody();
                },
                data: {
                    duration: $('#durationTime').val(),
                    // d.status = $('#b_status').val();
                    //d.keyword = $('#b_keyword').val();
                },
                complete: function(){
                    // unBlockBody();
                },
                error : function(e){
                    // unBlockBody()
                    //window.location.reload();
                    console.log(e);
                },
            },
            columns: [
                // {data: 'id',className: 'text-center', searchable:false},
                { data: 'invoice_no', name: 'invoice_no',className: 'text-center1', searchable:false, orderable:false},
                // { data: 'mobile_no', name: 'mobile_no', className: 'text-center', searchable:false, orderable:false },
                { data: 'payment_type', name: 'payment_type', className: 'text-center', searchable:false, orderable:false },
                { data: 'reference', name: 'reference', className: 'text-center', searchable:false, orderable:false },
                { data: 'amount', name: 'amount', className: 'text-center', searchable:false, orderable:false },
                { data: 'payment_date', name: 'payment_date', className: 'text-center', searchable:false, orderable:false },
                { data: 'status', name: 'status', className: 'text-center', searchable:false, orderable:false },
                { data: 'action', name: 'action' , className: 'text-center', searchable:false, orderable:false },
               
            ]
        });
    }

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        // $('#b_status').val('').change();
        $('#b_keyword').val('');
        $('#datatable').DataTable().draw(true);
    });

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

    function applyFilter() {
        loadData();
    }

    function resetFilter() {
      $('#financial_year').val($('option[data-financial-year="0"]').val()).change();
      applyFilter();
    }
  </script>
@endsection
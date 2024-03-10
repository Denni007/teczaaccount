@extends('admin.app-admin')
@section('title') Production @endsection
@section('page-header')
@php

@endphp
@endsection

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Production </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Production </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="card m-2">
        <div class="card-header header-elements-inline">
            <h5>Production List</h5>
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
            <a href="{{ route('admin.production.add') }}" class="btn btn-primary float-right"> Add Production</a>
            @elseif(!empty($permissions) && in_array('production_add',$permissions))
            <a href="{{ route('admin.production.add') }}" class="btn btn-primary float-right"> Add Production</a>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 mb-2 mb-md-2 mb-lg-0">
                    <div class="controls">
                       <!--  <input type="text" name="keyword" placeholder="Search Product Name" class="form-control"
                            id="b_keyword"> -->
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-2 mb-md-2 mb-lg-0 padding-top-class">
                    <!-- <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                    <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button> -->
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="datatable">
                <thead>
                    <tr class="text-center">
                        {{--  <th>Id</th>  --}}
                        <th>Name</th>
                        <th>Batch No.</th>
                        <th>Quantity</th>
                        <th>Weight</th>
                        <th>Certified</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
@section('footer_content')
<script>
    const delete_url = "{{ route('admin.production.delete') }}";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var companyTable = "";
    $(document).ready( function () {
        companyTable = $('#datatable').DataTable({
            "responsive": true,
            serverSide: true,
            bFilter:false,
            bSort:false,
            ajax: {
                url: "{{ route('admin.production.filter') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                beforeSend: function(){
                    // blockBody();
                },
                data: function (d) {
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
                { data: 'product_name', name: 'product_name',className: 'text-center1', searchable:false, orderable:false},
                { data: 'batch_no', name: 'batch_no', className: 'text-center', searchable:false, orderable:false },
                { data: 'quantity', name: 'quantity',className: 'text-center1',searchable:false , orderable:false},
                { data: 'weight', name: 'weight', className: 'text-center', searchable:false, orderable:false },
                // { data: 'mobile_no', name: 'mobile_no', className: 'text-center', searchable:false, orderable:false },
                { data: 'certified', name: 'certified', className: 'text-center', searchable:false, orderable:false },
                { data: 'action', name: 'action' , className: 'text-center', searchable:false, orderable:false },
               
            ]
        });

        $(document).on('click','.production-delete', function() {
        var id= $(this).data('id');
        var companyTable_row = $(this).closest("tr");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
             //type: 'warning',
            icon: 'warning',
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


        companyTable.columns.adjust().draw();
    });

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        // $('#b_status').val('').change();
        $('#b_keyword').val('');
        $('#datatable').DataTable().draw(true);
    });
</script>
@endsection
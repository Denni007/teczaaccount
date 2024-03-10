@extends('admin.app-admin')
@section('title') Raw Material @endsection
@section('page-header')
<style>
    .custom-loader {
        animation: none !important;
        border-width: 0 !important;
    }
</style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Raw Material </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Raw Material </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="card m-2">
        <div class="card-header header-elements-inline">
            <h5>Raw Material List</h5>
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
            <a href="{{ route('raw-material.create') }}" class="btn btn-primary float-right"> Add Raw Material</a>
            @elseif(!empty($permissions) && in_array('raw_material_add',$permissions))
            <a href="{{ route('raw-material.create') }}" class="btn btn-primary float-right"> Add Raw Material</a>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 mb-2 mb-md-2 mb-lg-0">
                    <div class="controls">
                        <input type="text" name="keyword" placeholder="Search Raw Material" class="form-control"
                            id="b_keyword">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-2 mb-md-2 mb-lg-0 padding-top-class">
                    <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                    <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="datatable">
                <thead>
                    <tr class="text-center">
                        <th>Raw Material Name</th>
                        <th>Material Qty</th>
                        <th>Material Unit</th>
                        <th>Applicabel GST in %</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('footer_content')
<script>
    const delete_url = "{{ route('admin.raw-material.delete') }}";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var rawMaterialTable = "";
    $(document).ready( function () {
        // console.log('called');

        rawMaterialTable = $('#datatable').DataTable({
            "responsive": true,
            serverSide: true,
            bFilter:false,
            bSort:false,
            ajax: {
                url: "{{ route('raw-material-list') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                beforeSend: function(){
                    // blockBody();
                },
                data: function (d) {
                    // d.status = $('#b_status').val();
                    d.keyword = $('#b_keyword').val();
                },
                complete: function(){
                    // unBlockBody();
                },
                error : function(e){
                    // unBlockBody()
                    window.location.reload();
                    console.log(e);
                },
            },
            columns: [
                { data: 'raw_material_name', name: 'raw_material_name',className: 'text-center1', searchable:false, orderable:false},
                { data: 'qty', name: 'qty',className: 'text-center1', searchable:false, orderable:false},
                { data: 'unit', name: 'unit',className: 'text-center1', searchable:false, orderable:false},
                { data: 'applicable_gst', name: 'applicable_gst',className: 'text-center1', searchable:false, orderable:false},
                { data: 'action', name: 'action' , className: 'text-center', searchable:false, orderable:false }, 
            ]

            // rawMaterialTable.columns.adjust().draw();
        });

        $('#btnFilter').click(function(){
            $('#datatable').DataTable().draw(true);
        });

        $('#btnReset').click(function(){
            // $('#b_status').val('').change();
            $('#b_keyword').val('');
            $('#datatable').DataTable().draw(true);
        });
        $(document).on('click','.raw-material-delete',function() {
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
                                rawMaterialTable.row(companyTable_row).remove().draw(false);
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
        rawMaterialTable.columns.adjust().draw();
    });


</script>
@endsection
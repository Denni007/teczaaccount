@extends('admin.app-admin')
@section('title') User @endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user') }}">User List</a></li>
                        <li class="breadcrumb-item active">User Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ $dataArr['name'] ?? '' }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Mobile</strong>
                                    <p>{{ $dataArr->phone ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Email</strong>
                                    <p>{{ $dataArr->email ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Password</strong>
                                    <p>{{ $dataArr->text_pass ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Address</strong>
                                    <p>{{ $dataArr->address ?? "" }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Type</strong>
                                    <p>{{ ($dataArr->user_type == 2) ? 'Cash & Bill' : 'Cash' }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Designation</strong>
                                    <p>{{ $dataArr->designation ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Shift type</strong>
                                    <p>@switch($dataArr->shift_type)
                                    @case(1)
                                        Day
                                        @break

                                    @case(2)
                                        Night
                                        @break

                                    @case(3)
                                        Full time
                                        @break

                                    @case(4)
                                        Daily Worker
                                        @break
                                    @default
                                        -
                                        @break
                                @endswitch</p>
                                </div>
                                
                            </div>
                            <h4>Bank Details</h4>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Bank Name</strong>
                                    <p>{{ $dataArr->bank_name ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>IFSC Code</strong>
                                    <p>{{ $dataArr->ifsc_code ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Swift Code</strong>
                                    <p>{{ $dataArr->swift_code ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Beneficiary Name</strong>
                                    <p>{{ $dataArr->beneficary_name ?? "" }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Account No.</strong>
                                    <p>{{ $dataArr->account_no ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Account Type</strong>
                                    <p>{{ $dataArr->account_type ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Branch Name</strong>
                                    <p>{{ $dataArr->branch_name ?? "" }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    
                </div>
            </div>
        </div>
    </section>

@endsection
@section('footer_content')
<script src="{{ asset('js/pages/user/add_user.js') }}"></script>
@endsection
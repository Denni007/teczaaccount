@extends('admin.app-admin')
@section('title') Company @endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Company Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.company') }}">Company List</a></li>
                        <li class="breadcrumb-item active">Company Details</li>
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
                            <h3 class="card-title">{{ $dataArr['company_name'] ?? '' }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Contact Person Name</strong>
                                    <p>{{ $dataArr->contact_person_name ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Contact No.</strong>
                                    <p>{{ $dataArr->contact_no ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Email Id</strong>
                                    <p>{{ $dataArr->email_id ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Mobile_ No.</strong>
                                    <p>{{ $dataArr->mobile_no ?? "" }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Address</strong>
                                    <p>{{ $dataArr->address ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Country</strong>
                                    <p>{{ $dataArr->country->name ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>State</strong>
                                    <p>{{ $dataArr->state->name ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Pincode</strong>
                                    <p>{{ $dataArr->pincode ?? "" }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>GST No</strong>
                                    <p>{{ $dataArr->gst_no ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>CIN</strong>
                                    <p>{{ $dataArr->cin ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Website</strong>
                                    <p>{{ $dataArr->website ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Currency</strong>
                                    <p>{{ $dataArr->currency ?? "" }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Pan No.</strong>
                                    <p>{{ $dataArr->pan_no ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Company Sufix</strong>
                                    <p>{{ $dataArr->sufix ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Wallet Balance</strong>
                                    <p>{{ $dataArr->wallet_balance ?? "" }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                        <!--  Bank Account Details -->
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Bank Account Details</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <a href="{{ route('admin.company.add-bank-details',['companyId'=>$dataArr->id]) }}" class="btn btn-primary float-right mb-2"> Add Bank Account</a>
                                    <table class="table table-bordered table-striped w-100">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Bank Name</th>
                                                <th>IFSC Code</th>
                                                <th>Swift Code</th>
                                                <th>Beneficary Name</th>
                                                <th>Account No.</th>
                                                <th>Account Type</th>
                                                <th>Branch Name</th>
                                                <th>Balance</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @forelse ($dataArr->bank_account as $detail )
                                                    <td>{{$detail->bank_name}}</td>
                                                    <td>{{$detail->ifsc_code}}</td>
                                                    <td>{{$detail->swift_code}}</td>
                                                    <td>{{$detail->beneficary_name}}</td>
                                                    <td>{{$detail->account_no}}</td>
                                                    <td>{{$detail->account_type}}</td>
                                                    <td>{{$detail->branch_name}}</td>
                                                    <td>{{$detail->balance}}</td>
                                                    <td><a href="{{route('admin.company.edit-bank-details',['companyId'=>$detail->company_id,'id'=>$detail->id])}}" class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>
                                                        <a href="{{route('admin.company.delete-bank-details',['companyId'=>$detail->company_id,'id'=>$detail->id])}}" class="edit btn btn-danger btn-sm mx-1" ><i class="fas fa-trash"></i></a></td>
                                                @empty
                                                    <td colspan="9">No Data Found</td>
                                                @endforelse 
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card -->
                    
                </div>
            </div>
        </div>
    </section>

@endsection
@section('footer_content')
<script src="{{ asset('js/pages/company/add_company.js') }}"></script>
@endsection
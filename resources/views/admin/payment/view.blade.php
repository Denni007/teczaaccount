@extends('admin.app-admin')
@section('title') Payment @endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Payment Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payment') }}">Payment List</a></li>
                        <li class="breadcrumb-item active">Payment Details</li>
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
                            <h3 class="card-title">{{ $dataArr->company->company_name ?? '' }}</h3>
                        </div>
                        <!-- /.card-header -->  
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>User</strong>
                                    <p>{{ $dataArr->user->name ?? '' }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Invoice</strong>
                                    @if($dataArr->type == 1)
                                    <p><a href="{{route('admin.invoice.view',['action'=>'view','id'=>$dataArr->invoice_id])}}">{{$dataArr->invoice_no}}</a></p> 
                                    @elseif($dataArr->type == 2)
                                    <p><a href="{{route('admin.purchase.view',['action'=>'view','id'=>$dataArr->invoice_id])}}">{{$dataArr->invoice_no}}</a></p> 
                                    @else
                                    <p>-</p>
                                    @endif
                                </div>
                                 <div class="form-group col-md-3">
                                    <strong>Payment Type</strong>
                                    @if($dataArr->payment_type == 1)
                                    <p>Credit</p>
                                    @elseif($dataArr->payment_type == 2)
                                    <p>Debit</p>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Payment Mode</strong>
                                    @if($dataArr->payment_type == 1)
                                    <p>Cash</p>
                                    @elseif($dataArr->payment_type == 2)
                                    <p>Bank</p>
                                    @endif
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Bank Account</strong>
                                    <p>{{ $dataArr->bank->bank_name  ?? "" }}-{{ $dataArr->bank->account_no  ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Invoice Amount</strong>
                                    <p>{{ $dataArr->amount ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Paid Amount</strong>
                                    <p>{{ $dataArr->pay_amount ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Reference</strong>
                                    <p>{{ $dataArr->reference ?? "" }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Payment Date</strong>
                                    <p>{{ date('d-m-Y',strtotime($dataArr->payment_date)) ?? "" }}</p>
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
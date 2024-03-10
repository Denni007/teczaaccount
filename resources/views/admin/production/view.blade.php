@extends('admin.app-admin')
@section('title') Production @endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Production Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.production') }}">Production List</a></li>
                        <li class="breadcrumb-item active">Production Details</li>
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
                            <h3 class="card-title">{{ $dataArr->batch_no ?? '' }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Product Type</strong>
                                    <p>{{ $dataArr->product->product_name ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Batch No.</strong>
                                    <p>{{ $dataArr->batch_no ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Weight</strong>
                                    <p>{{ $dataArr->weight ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Quantity</strong>
                                    <p>{{ $dataArr->quantity ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Certified</strong>
                                    <p>{{ ($dataArr->certified == 1) ? 'ISO Certified' : 'Non ISO Certified' }}</p>
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
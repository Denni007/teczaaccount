@extends('admin.app-admin')
@section('title') Company @endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Raw Material Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('raw-material.index') }}">Raw Material List</a></li>
                        <li class="breadcrumb-item active">Raw Material Details</li>
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
                            <h3 class="card-title">Raw Material Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Raw Material Name</strong>
                                    <p>{{ $dataArr->raw_material_name ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Material Quantity</strong>
                                    <p>{{ $dataArr->qty ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Material Unit</strong>
                                    <p>{{ $dataArr->unit ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Applicable GST in %</strong>
                                    <p>{{ $dataArr->applicable_gst ?? "" }}</p>
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
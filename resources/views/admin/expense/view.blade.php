@extends('admin.app-admin')
@section('title') Expense @endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Expense Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.expense') }}">Expense List</a></li>
                        <li class="breadcrumb-item active">Expense Details</li>
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
                            <h3 class="card-title">Expense</h3>
                        </div>
                        <!-- /.card-header -->  
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <strong>Expense</strong>
                                    <p>{{ $dataArr->expense_name ?? "" }}</p>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Amount</strong>
                                    <p>{{ $dataArr->amount ?? "" }}</p>
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

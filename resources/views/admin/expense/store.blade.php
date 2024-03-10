@extends('admin.app-admin')
@section('title') Expense @endsection
@section('page-header')
@endsection
@section('content')
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
                  Expense {{ isset($dataArr) ? "Edit" : "Add" }}
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.expense') }}">Expense</a> </li>
                  <li class="breadcrumb-item active">Expense {{ isset($dataArr) ? "Edit" : "Add" }}</li>
               </ol>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>
   <section class="content">
      <div class="row d-flex justify-content-center">
         <div class="col-md-10">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">Expense</h3>
               </div>
               <div class="card-body">
                  <form class="add-expense-form" action="{{ route('admin.expense.store') }}" method="POST">
                     @csrf
                     @method("POST")
                     <input type="hidden" name="id" value="{{ $dataArr['id'] ?? '' }}">
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="expense_name">Expense <span style="color: red">*</span></label>
                           <input type="text" name="expense_name" id="expense_name" class="form-control" value="{{ $dataArr['expense_name'] ?? '' }}">
                        </div>
                        <div class="col-6 form-group">
                           <label for="amount">Amount <span style="color: red">*</span></label>
                           <input type="text" name="amount" id="amount" class="form-control" value="{{ $dataArr['amount'] ?? '' }}">
                        </div>
                     </div>
                     <div class="row mt-2">
                        <div class="col-12 d-flex justify-content-center">
                           {{-- <input type="submit" value="Save" class="btn btn-success"> --}}
                           <button type="submit" class="btn btn-success">
                              {{ isset($dataArr) ? 'Update' : 'save' }}
                           </button>
                        </div>
                     </div>
                  </form>
                  {{-- @endif --}}
               </div>
               <!-- /.card-body -->
            </div>
            <!-- /.card -->
         </div>
      </div>
   </section>
@endsection
@section('footer_content')
<script src="{{ asset('js/pages/expense/add_expense.js') }}"></script>
@endsection
@extends('admin.app-admin')
@section('title') Company @endsection
@section('page-header')
@endsection
@section('content')
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
                  Bandk Details {{ isset($dataArr) ? "Edit" : "Add" }}
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href={{ route('admin.company') }}></a> Company</li>
                  <li class="breadcrumb-item active">Bank Details {{ isset($dataArr) ? "Edit" : "Add" }}</li>
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
                  <h3 class="card-title">Bank Details</h3>
               </div>
               <form class="form-horizontal add-bank-details-form" action="{{ route('admin.company.bank-details.add') }}" method="POST" autocomplete="off">
                 @csrf
                     <div class="card-body">
                         <input type="hidden" name="id" value="{{ $bankDetails->id ?? 0 }}">
                         <input type="hidden" name="company_id" value="{{ $companyId ?? 0 }}">
                         <div class="form-group row">
                             <label for="bank_name" class="col-sm-2 col-form-label">Bank Name <span style="color: red">*</span> </label>
                             <div class="col-sm-10">
                                 <input type="text" name="bank_name" class="form-control" id="bank_name" placeholder="Bank Name" value="{{ $bankDetails->bank_name ?? old('bank_name') }}">
                             </div>
                         </div>
                         <div class="form-group row">
                             <label for="ifsc_code" class="col-sm-2 col-form-label">IFSC Code <span style="color: red">*</span></label>
                             <div class="col-sm-10">
                                 <input type="text" name="ifsc_code" class="form-control" id="ifsc_code" placeholder="IFSC Code" value="{{ $bankDetails->ifsc_code ?? old('ifsc_code') }}">
                             </div>
                         </div>
                         <div class="form-group row">
                             <label for="swift_code" class="col-sm-2 col-form-label">Swift Code</label>
                             <div class="col-sm-10">
                                 <input type="text" name="swift_code" class="form-control" id="swift_code" placeholder="Swift Code" value="{{ $bankDetails->swift_code ?? old('swift_code') }}">
                             </div>
                         </div>
                         <div class="form-group row">
                             <label for="beneficary_name" class="col-sm-2 col-form-label">Beneficary Name <span style="color: red">*</span></label>
                             <div class="col-sm-10">
                                 <input type="text" name="beneficary_name" class="form-control" id="beneficary_name" placeholder="Beneficary Name" value="{{ $bankDetails->beneficary_name ?? old('beneficary_name') }}">
                             </div>
                         </div>
                         <div class="form-group row">
                             <label for="account_no" class="col-sm-2 col-form-label">Account No. <span style="color: red">*</span></label>
                             <div class="col-sm-10">
                                 <input type="text" name="account_no" class="form-control" id="account_no" placeholder="Account No." value="{{ $bankDetails->account_no ?? old('account_no') }}">
                             </div>
                         </div>
                         <div class="form-group row">
                             <label for="account_type" class="col-sm-2 col-form-label">Account Type</label>
                             <div class="col-sm-10">
                                 <input type="text" name="account_type" class="form-control" id="account_type" placeholder="Account Type" value="{{ $bankDetails->account_type ?? old('account_type') }}">
                             </div>
                         </div>
                         <div class="form-group row">
                             <label for="branch_name" class="col-sm-2 col-form-label">Branch Name</label>
                             <div class="col-sm-10">
                                 <input type="text" name="branch_name" class="form-control" id="branch_name" placeholder="Branch Name" value="{{ $bankDetails->branch_name ?? old('branch_name') }}">
                             </div>
                         </div>
                         <div class="form-group row">
                             <label for="branch_name" class="col-sm-2 col-form-label">Balance</label>
                             <div class="col-sm-10">
                                 <input type="text" name="balance" class="form-control" id="branch_name" placeholder="Balance" value="{{ $bankDetails->balance ?? old('balance') }}">
                             </div>
                         </div>
                         
                     </div>
                 <!-- /.card-body -->
                 <div class="card-footer text-center">
                     <button type="submit" class="btn btn-info d-flex justify-content-center">Submit</button>
                 </div>
                 <!-- /.card-footer -->
               </form>
               <!-- /.card-body -->
            </div>
            <!-- /.card -->
         </div>
      </div>
   </section>
@endsection
@section('footer_content')
<script src="{{ asset('js/pages/company/add_company.js') }}"></script>
@endsection
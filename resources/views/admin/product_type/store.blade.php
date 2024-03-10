@extends('admin.app-admin')
@section('title') Product Type @endsection
@section('page-header')
@endsection
@section('content')

   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
                  Product Type {{ isset($dataArr) ? "Edit" : "Add" }}
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.product_type') }}">Product Types</a></li>
                  <li class="breadcrumb-item active">Product Type {{ isset($dataArr) ? "Edit" : "Add" }}</li>
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
                  <h3 class="card-title">Product Type</h3>
               </div>
               <div class="card-body">
                  <form class="add-product-type-form" action="{{ route('admin.product_type.store') }}" method="POST">
                     @csrf
                     @method("POST")
                     <input type="hidden" name="id" value="{{ $dataArr['id'] ?? '' }}">
                     <div class="row">
                        <div class="col-12 form-group">
                           <label for="name">Product Type <span style="color: red">*</span></label>
                           <input type="text" name="type"
                              value="{{ $dataArr['type'] ?? old('type') }}" id="type"
                              class="form-control">
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
<script src="{{ asset('js/pages/product_type/add_type.js') }}"></script>
@endsection
@extends('admin.app-admin')
@section('title') Raw Material @endsection
@section('page-header')
@endsection
@section('content')
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
                    Raw Material {{ isset($dataArr) ? "Edit" : "Add" }}
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('raw-material.index') }}">Raw Material</a></li>
                  <li class="breadcrumb-item active">Raw Material {{ isset($dataArr) ? "Edit" : "Add" }}</li>
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
                  <h3 class="card-title">Raw Material</h3>
               </div>
               <div class="card-body">
                  <form class="raw-material-form" action="{{ route('raw-material.store') }}" method="POST">
                     @method("POST")
                     @csrf
                     <input type="hidden" name="id" value="{{ $dataArr['id'] ?? "" }}">
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="raw_material_name">Raw Matetial Name<span style="color: red">*</span></label>
                           <input type="text" name="raw_material_name"
                              value="{{ $dataArr['raw_material_name'] ?? old('raw_material_name') }}" id="raw_material_name"
                              class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="qty">Material Quantity <span style="color: red">*</span></label>
                           <input id="qty" type="text" name="qty"
                              value="{{ $dataArr['qty'] ?? old('qty') }}" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="unit">Material Unit </label>
                           <input id="unit" type="text" name="unit"
                              value="{{  $dataArr['unit'] ?? old("unit") }}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="applicable_gst">Applicable GST % </label>
                           <input type="text" id="applicable_gst" name="applicable_gst"
                              value="{{ $dataArr['applicable_gst'] ?? old("applicable_gst") }}" class="form-control">
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
<script src="{{ asset('js/pages/raw_material/raw_material.js') }}"></script>
@endsection
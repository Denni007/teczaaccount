@extends('admin.app-admin')
@section('title') Product @endsection
@section('page-header')

@endsection
@section('content')

   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
                  Product {{ isset($dataArr) ? "Edit" : "Add" }}
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.product') }}">Product</a> </li>
                  <li class="breadcrumb-item active">Product {{ isset($dataArr) ? "Edit" : "Add" }}</li>
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
                  <h3 class="card-title">Product</h3>
               </div>
               <div class="card-body">
                  <form class="add-product-form" action="{{ route('admin.product.store') }}" method="POST">
                     @csrf
                     @method("POST")
                     <input type="hidden" name="id" value="{{ $dataArr['id'] ?? '' }}">
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="name">Product Name <span style="color: red">*</span></label>
                           <input type="text" name="product_name"
                              value="{{ $dataArr['product_name'] ?? old('product_name') }}" id="product_name"
                              class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="productType">Product Type<span style="color: red">*</span></label>
                           <select name="product_type" id="product_type" class="form-control select2">
                             <option value="">Select Product Type</option>
                             @foreach($productType as $pt)
                             <option value="{{$pt->id}}" @if(isset($dataArr['product_type']) && $dataArr['product_type'] == $pt->id) selected @endif>{{$pt->type}}</option>
                             @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="inputWeight">Weight</label>
                           <input id="inputWeight" type="number" name="weight"
                              value="{{  $dataArr['weight'] ?? old('weight') }}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="certified">Certified <span style="color: red">*</span></label>
                           <select class="form-control select2" name="certified" id="certified">
                              <option value="">Select Certified</option>
                              <option value="1" @if(isset($dataArr['certified']) && $dataArr['certified'] == 1) selected @endif>ISO</option>
                              <option value="2" @if(isset($dataArr['certified']) && $dataArr['certified'] == 2) selected @endif>Non ISO</option>
                           </select>
                        </div>
                        <div class="col-6 form-group">
                           <label for="inputGst">GST Percentage<span style="color: red">*</span></label>
                           <input id="inputGst" type="text" name="gst_percentage"
                              value="{{  $dataArr['gst_percentage'] ?? old('gst_percentage') }}" class="form-control">
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
<script src="{{ asset('js/pages/product/add_product.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
  function addMaterial()
  {
    $('#material').append('<div class="row" ><div class="col-6 form-group"><label>Material<span style="color: red">*</span></label><select class="form-control js-example-tags material" name="material[]"><option value="">Select Material</option>@foreach($material as $mr)<option value="{{$mr->id}}">{{$mr->raw_material_name}}</option>@endforeach</select></div><div class="col-5 form-group"><label>Quantity<span style="color: red">*</span></label><input type="number" name="quantity[]" class="form-control quantity" /></div><div class="col-1 "><br /><button type="button" class="btn btn-primary" onclick="removeMaterial(this,0);"><i class="fa fa-minus"></i></button></div></div>');
     $(".js-example-tags").select2({
      tags: true
    });
  }

  function removeMaterial(elem,id)
  {
    $(elem).parent().parent().remove();
  }
 $(".js-example-tags").select2({
      tags: true
    });
</script>



@endsection
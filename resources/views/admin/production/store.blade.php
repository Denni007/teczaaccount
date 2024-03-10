@extends('admin.app-admin')
@section('title') Production @endsection
@section('page-header')

@endsection
@section('content')

   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
                  Production {{ isset($dataArr) ? "Edit" : "Add" }}
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.production') }}">Production</a> </li>
                  <li class="breadcrumb-item active">Production {{ isset($dataArr) ? "Edit" : "Add" }}</li>
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
                  <h3 class="card-title">Production</h3>
               </div>
               <div class="card-body">
                  <form class="add-production-form" action="{{ route('admin.production.store') }}" method="POST">
                     @csrf
                     @method("POST")
                     <input type="hidden" name="id" value="{{ $dataArr['id'] ?? '' }}">
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="name">Product Name <span style="color: red">*</span></label>
                           <select name="product" id="product" class="form-control select2">
                             <option value="">Select Product</option>
                             @foreach($product as $pro)
                             <option value="{{$pro->id}}" @if(isset($dataArr['product_id']) && $dataArr['product_id'] == $pro->id) selected @endif>{{$pro->product_name}}</option>
                             @endforeach
                           </select>
                        </div>
                        <div class="col-6 form-group">
                          <label for="quantity">Quantity<span style="color: red">*</span></label>
                          <input type="number" id="quantity" name="quantity" value="{{  $dataArr['quantity'] ?? old('quantity') }}" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="inputBatch">Batch No.<span style="color: red">*</span></label>
                           <input type="hidden" name="batch_unique_id" value="{{$batch_unique_id}}">
                           <input id="inputBatch" type="text" name="batch_no"
                              value="{{  $batchNo}}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="inputWeight">Weight</label>
                           <input id="inputWeight" type="number" name="weight"
                              value="{{  $dataArr['weight'] ?? old('weight') }}" class="form-control" onblur="updateQuantity()">
                           <input type="hidden" name="certified" value="{{$dataArr['certified'] ?? ''}}" id="certified">
                        </div>
                     </div>
                  
                     <h3>Batch Recipe</h3> <button type="button" class="btn btn-primary" onclick="addMaterial();"><i class="fa fa-plus"></i></button>
                     <div id="material">
                      @if(isset($productionMaterial) && !empty($productionMaterial))
                        @foreach($productionMaterial as $pm)
                          <div class="row">
                            <input type="hidden" name="material_id[]" value="{{$pm->id}}" class="material_id" />
                            <div class="col-6 form-group">
                              <label>Material<span style="color: red">*</span></label>
                              <select class="form-control rawmaterial js-example-tags select2" name="material[]" onchnage="setVal(this)">
                                <option value="">Select Rawmaterial</option>
                                @foreach($material as $rm)
                                <option value="{{$rm->id}}" @if($pm->id == $rm->id) selected @endif>{{$rm->raw_material_name}}</option>
                                @endforeach
                             </select>
                              <!-- <input type="text" class="form-control material" name="material[]" value="{{$pm->raw_material_name}}" /> -->
                            </div>
                            <div class="col-3 form-group">
                              <label>Quantity<span style="color: red">*</span></label>
                              <input type="number" name="material_quantity[]" class="form-control quantity" value="{{$pm->quantity}}" onchange="updateQuantity()" />
                            </div>
                            
                            <div class="col-1 "><br /><button type="button" class="btn btn-primary" onclick="removeMaterial(this);"><i class="fa fa-minus"></i></button>
                            </div>
                          </div>
                        @endforeach
                      @endif
                     </div>
                     <div class="row" id="totalQuantityshow" style="display:none";>
                              <div class="col-md-6">
                              
                              </div>
                              <div class="col-md-6">
                                 <div class="row">
                                    <div class="col-sm-3">
                                       <label>Total Quentity  <span style="color: red">*</span></label>
                                    </div>
                                    <div class="col-sm-2">
                                       <label id="total_qty"> </label>
                                    </div>
                                 </div>
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
<script src="{{ asset('js/pages/production/add_production.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script type="text/javascript">
  $(document).ready(function (){
      $(".js-example-tags").select2({
         tags: true
      });
      updateQuantity();
   });
  $('#product').change(function (){
    var productId = $(this).val();
    if(productId != '')
    {
        var _token     = $('input[name="_token"]').val();
        var formData   = {productId:productId, _token : _token};
        $.ajax({
              url: "{{route('admin.fetch_product_material')}}",
              type: "POST",
              data: formData,       
              cache: false,       
              success: function(data){
                   if(data.status == 1)
                   {
                     //  $('#material').append(data.html);
                      $('#quantity').val('1');
                      $('#inputWeight').val(data.weight);
                      $('#certified').val(data.isoType);
                   }
                   $(".js-example-tags").select2({
                     tags: true
                  });
                  updateQuantity();
              }
        });
    }
  });
  function addMaterial()
  {
   updateQuantity();
    $('#material').append('<div class="row"><input type="hidden" name="material_id[]" value="0" class="material_id"/><div class="col-6 form-group"><label>Material<span style="color: red">*</span></label> <select class="form-control rawmaterial js-example-tags" name="material[]" onchnage="setVal(this)" id="materialType"><option value="">Select Rawmaterial</option>@foreach($material as $rm)<option value="{{$rm->id}}">{{$rm->raw_material_name}}</option>@endforeach</select></div><div class="col-3 form-group"><label>Quantity<span style="color: red">*</span></label><input type="number" min="1" name="material_quantity[]" class="form-control quantity" value="" onchange="updateQuantity()"/></div><div class="col-1 "><br /><button type="button" class="btn btn-primary" onclick="removeMaterial(this);"><i class="fa fa-minus"></i></button></div></div>');
    $(".js-example-tags").select2({
         tags: true
      });
      
     
  }

  function removeMaterial(elem)
  {
    $(elem).parent().parent().remove();
    updateQuantity();
  }

  function updateQuantity()
  {
    var totalqty = 0;
    var weight = $('#inputWeight').val();
    if(weight == undefined)
    {
      weight = 1;
    }
    $('.quantity').each(function(){
      totalqty = parseFloat(totalqty) + parseFloat($(this).val());
    });
   //  $('#quantity').val(Math.ceil(totalqty/weight));
    $('#totalQuantityshow').show();
    $('#total_qty').text(totalqty);
    
  }
 // $('.js-example-tags').on("select2:select",function (){
 //  material = $(this).val();
 //  $(this).parent().parent().find('.material_id').val(material);
 // });

 // function setVal(elem)
 // {
 //    alert();
 // }
//  $(document.body).on("change","#materialType",function(){
//     alert('here');
//    var _token     = $('input[name="_token"]').val();
//    var id = this.value;
//       $.ajax({
//             url: "{{--route('admin.getunits')--}}",
//             type: "POST",
//             data: {id:id,_token:_token},     
//             success: function(data){
//                if(data.unit != "") {
//                   $('#unit').val(data.unit);
//                }
//                else{
//                   $('#unit').val('No data');
//                }
//             }
//       });
//  });
</script>



@endsection
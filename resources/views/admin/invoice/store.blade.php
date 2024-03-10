@extends('admin.app-admin')
@section('title') Invoice @endsection
@section('content')
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>
                  Invoice {{ isset($dataArr) ? "Edit" : "Add" }}
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.invoice') }}">Invoice</a> </li>
                  <li class="breadcrumb-item active">Invoice {{ isset($dataArr) ? "Edit" : "Add" }}</li>
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
                  <h3 class="card-title">Invoice</h3>
               </div>
               <div class="card-body">
                  <form class="add-invoice-form" action="{{ route('admin.invoice.store') }}" method="POST">
                     @csrf
                     @method("POST")
                     <input type="hidden" name="id" value="{{ $dataArr['id'] ?? '' }}">
                     <input type="hidden" name="bill_unique_id" value="{{ $bill_unique_id ?? 10001 }}">
                     <div class="row">
                        <div class="col-12 form-group">
                           <label for="vendor">Vendor <span style="color: red">*</span></label>
                           <select class="form-control select2" name="vendor" id="vendor" onchange="displayBankList();">
                              <option value="">Select Vendor</option>
                              @foreach($vendors as $vendor)
                              <option value="{{$vendor->id}}" {{ (@$dataArr->vendor_id == $vendor->id) ? 'selected':'' }}>{{$vendor->vendor_name}}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="bill_no">Bill No. <span style="color: red">*</span></label>
                           <input id="bill_no" type="text" name="bill_no"
                              value="{{ $billNo }}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="pi">Perfoma Invoice</label>
                           <select class="form-control select2" name="pi" id="pi" onchange="fetch_perfoma_details();">
                              <option value="">Select Perfoma Invoice</option>
                              @foreach($piInvoice as $pi)
                              <option value="{{$pi->id}}" {{ (@$dataArr->pi_id == $pi->id) ? 'selected':'' }}>{{$pi->bill_no}}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="invoice_type">Invoice Type <span style="color: red">*</span></label>
                           <select class="form-control select2" name="invoice_type" id="invoice_type" >
                              <option value="">Select Invoice Type</option>
                              <option value="1" {{ (@$dataArr->invoice_type == 1) ? 'selected':'' }}>Retail Invoice</option>
                              <option value="2" {{ (@$dataArr->invoice_type == 2) ? 'selected':'' }}>Tax Invoice</option>
                           </select>
                        </div>
                        <div class="col-6 form-group">
                           <label for="bill_type">Bill Type <span style="color: red">*</span></label>
                            @if(Auth::user()->type == 2)
                              @if(Auth::user()->user_type == 1)
                                 <select class="form-control select2" name="bill_type" id="bill_type" onchange="displayBankList()">
                                    <option value="">Select Bill Type</option>
                                    <option value="1" {{ (@$dataArr->bill_type == 1) ? 'selected':'' }}>Cash</option>
                                 </select>
                              @else
                                 <select class="form-control select2" name="bill_type" id="bill_type" onchange="displayBankList()">
                                    <option value="">Select Bill Type</option>
                                    <option value="1" {{ (@$dataArr->bill_type == 1) ? 'selected':'' }}>Cash</option>
                                    <option value="2" {{ (@$dataArr->bill_type == 2) ? 'selected':'' }}>Bill</option>
                                 </select>
                              @endif
                           @else
                           <select class="form-control select2" name="bill_type" id="bill_type" onchange="displayBankList()">
                              <option value="">Select Bill Type</option>
                              <option value="1" {{ (@$dataArr->bill_type == 1) ? 'selected':'' }}>Cash</option>
                              <option value="2" {{ (@$dataArr->bill_type == 2) ? 'selected':'' }}>Bill</option>
                           </select>
                           @endif
                        </div>
                        <div class="col-6 form-group" hidden="">
                           <label for="bank_account">Bank Account <span style="color: red">*</span></label>
                           <select class="form-control select2" name="bank_account" id="bank_account">
                              <option value="">Select Bank Account</option>
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="delivery_note">Delivery Note</label>
                           <input id="delivery_note" type="text" name="delivery_note"
                              value="{{ $dataArr['delivery_note'] ?? old('delivery_note') }}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="supplier_ref">Supplier Reference</label>
                           <input id="supplier_ref" type="text" name="supplier_ref"
                              value="{{ $dataArr['supplier_ref'] ?? old('supplier_ref') }}" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="buyers_no">Buyers No.</label>
                           <input id="buyers_no" type="text" name="buyers_no"
                              value="{{ $dataArr['buyers_no'] ?? old('buyers_no') }}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="dated">Dated</label>
                           <input id="dated" type="text" name="dated"
                              value="{{ (isset($dataArr['dated']) && !empty($dataArr['dated'])) ? date('m/d/Y',strtotime($dataArr['dated'])) : old('dated') }}" class="form-control datetimepicker-input">
                        </div>
                     </div>    
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="dispatch_doc_no">Disapatch Doc No.</label>
                           <input id="dispatch_doc_no" type="text" name="dispatch_doc_no"
                              value="{{ $dataArr['dispatch_doc_no'] ?? old('dispatch_doc_no') }}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="delivery_note_date">Delivery Note Date</label>
                           <input id="delivery_note_date" type="text" name="delivery_note_date"
                              value="{{ (isset($dataArr['delivery_note_date']) && !empty($dataArr['delivery_note_date'])) ? date('m/d/Y',strtotime($dataArr['delivery_note_date'])) : old('delivery_note_date') }}" class="form-control datetimepicker-input">
                        </div>
                     </div>                     
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="dispatch_through">Disapatch Through</label>
                           <input id="dispatch_through" type="text" name="dispatch_through"
                              value="{{ $dataArr['dispatch_through'] ?? old('dispatch_through') }}" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="destination">Destination</label>
                           <input id="destination" type="text" name="destination"
                              value="{{ $dataArr['destination'] ?? old('destination') }}" class="form-control ">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="bill_of_landing">Bill of Lading/LR-RR No</label>
                           <input id="bill_of_landing" type="text" name="bill_of_landing"
                              value="{{ (isset($dataArr['bill_of_landing']) && !empty($dataArr['bill_of_landing'])) ? date('m/d/Y',strtotime($dataArr['bill_of_landing'])) : old('bill_of_landing') }}" class="form-control datetimepicker-input">
                        </div>
                        <div class="col-6 form-group">
                           <label for="motor_vehicle_no">Moto Vehicle No.</label>
                           <input id="motor_vehicle_no" type="text" name="motor_vehicle_no"
                              value="{{ $dataArr['motor_vehicle_no'] ?? old('motor_vehicle_no') }}" class="form-control ">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="terms_of_delivery">Term of Delivery</label>
                           <input id="terms_of_delivery" type="text" name="terms_of_delivery"
                              value="{{ $dataArr['terms_of_delivery'] ?? old('terms_of_delivery') }}" class="form-control ">
                        </div>
                        <div class="col-6">
                           <label for="bill_date">Bill Date</label>
                           <input type="text" name="bill_date" value="{{ (isset($dataArr['bill_date']) && !empty($dataArr['bill_date'])) ? date('m/d/Y',strtotime($dataArr['bill_date'])) : old('bill_date') }}" id="bill_date"
                              class="form-control datetimepicker-input">
                        </div>
                     </div>
                     <h3>Products</h3>
                     <button type="button" class="btn btn-primary" onclick="addProduct();"><i class="fa fa-plus"></i></button>
                     <div id="production">
                        @if(isset($dataArr['invoice_items']) && !empty($dataArr['invoice_items']))
                           @foreach($dataArr['invoice_items'] as $key => $pi)
                              <div class="row">
                                 <div class="col-3 form-group">
                                    <label>Select Product</label>
                                    <select class="form-control product select2" name="product[]" onchange="calculateAmount()">
                                       <option value="">Select Product</option>
                                       @foreach($product as $pr)
                                       <option value="{{$pr->id}}|{{$pr->gst_percentage}}" @if($pi->product_id == $pr->id) selected @endif>{{$pr->product_name}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                                 <div class="col-2 form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity[]" class="form-control quantity" onblur="calculateAmount()" value="{{$pi->quantity}}">
                                 </div>
                                 <div class="col-2 form-group">
                                    <label>HAS/SAC</label>
                                    <input type="text" name="hsn[]" class="form-control hsn" value="{{$pi->hsn}}">
                                 </div>
                                 <div class="col-2 form-group">
                                    <label>Rate</label>
                                    <input type="number" name="rate[]" class="form-control rate" onblur="calculateAmount()" value="{{$pi->rate}}">
                                 </div>
                                 <div class="col-1 form-group">
                                    <label>Unit</label>
                                    <input type="text" name="unit[]" class="form-control unit" value="{{$pi->unit}}">
                                 </div>
                                 <div class="col-2 form-group">
                                    <label>Amount</label>
                                    <input type="number" name="pr_amount[]" class="form-control amount" value="{{$pi->amount}}">
                                 </div>
                                 <div class="col-1 form-group">
                                    <label>Tax</label>
                                    <input type="number" name="tax[]" class="form-control tax" value="{{$pi->tax}}">
                                 </div>
                                 <div class="col-1">
                                    <br />
                                    <button type="button" class="btn btn-primary" onclick="removeBatch(this);"><i class="fa fa-minus"></i></button>
                                 </div>
                              </div>
                           @endforeach
                        @endif
                        
                     </div>
                     <br />
                     <br />
                     <br />
                     <h3>Amount Details</h3>
                     <div class="row">
                        <div class="col-6 form-group">
                           <label for="amount">Amount (Excluding GST) <span style="color: red">*</span></label>
                           <input type="text" name="amount"
                              value="{{ $dataArr['amount'] ?? old('amount') }}"
                              id="amount" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="gst">GST Amount</label>
                           <input type="text" name="gst" value="{{ $dataArr['gst'] ?? old('gst') }}"
                              id="gst" class="form-control">
                        </div>
                     </div>
                     <!-- <div class="row">
                        <div class="col-6 form-group">
                           <label for="cgst">CGST Amount</label>
                           <input type="text" name="cgst" value="{{ $dataArr['cgst'] ?? old('cgst') }}"
                              id="cgst" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                           <label for="sgst">SGST Amount <span style="color: red">*</span></label>
                           <input type="text" name="sgst" value="{{ $dataArr['sgst'] ?? old('sgst') }}"
                              id="sgst" class="form-control">
                        </div>
                     </div> -->
                     <div class="row">
                        <!-- <div class="col-6 form-group">
                           <label for="igst">IGST Amount <span style="color: red">*</span></label>
                           <input type="text" name="igst" value="{{ $dataArr['igst'] ?? old('igst') }}" id="igst"
                              class="form-control">
                        </div> -->
                        <div class="col-6 form-group">
                           <label for="total_amount">Total Amount (Including GST) <span style="color: red">*</span></label>
                           <input type="text" name="total_amount" value="{{ $dataArr['total_amount'] ?? old('total_amount') }}"
                              id="total_amount" class="form-control">
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
<script src="{{ asset('js/pages/invoice/add_invoice.js') }}"></script>
<script type="text/javascript">
   $(".datetimepicker-input").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  }).datepicker('update', new Date());
   //$('.datetimepicker-input').datepicker();
   function addBatch()
   {
      $('#production').append('<div class="row"><div class="col-5 form-group"><label>Select batch</label><select class="form-control" name="batch[]"><option value="">Select Batch</option>@foreach($production as $pr)<option value="{{$pr->id}}">{{$pr->batch_no}}</option>@endforeach</select></div><div class="col-5 form-group"><label>Quantity</label><input type="number" name="quantity[]" class="form-control"></div><div class="col-2"><br /><button type="button" class="btn btn-primary" onclick="removeBatch(this);"><i class="fa fa-minus"></i></button></div></div>');
   }

   function addProduct()
   {
       $('#production').append('<div class="row"><div class="col-3 form-group"><label>Select Product</label><select class="form-control product" name="product[]" onchange="calculateAmount()"><option value="">Select Product</option>@foreach($product as $pr)<option value="{{$pr->id}}|{{$pr->gst_percentage}}">{{$pr->product_name}}</option>@endforeach</select></div><div class="col-2 form-group"><label>Quantity</label><input type="number" name="quantity[]" class="form-control quantity" onblur="calculateAmount()"></div><div class="col-2 form-group"><label>HAS/SAC</label><input type="text" name="hsn[]" class="form-control hsn" ></div><div class="col-2 form-group"><label>Rate</label><input type="number" name="rate[]" class="form-control rate" onblur="calculateAmount()"></div><div class="col-1 form-group"><label>Unit</label><input type="text" name="unit[]" class="form-control unit"></div><div class="col-2 form-group"><label>Amount</label><input type="number" name="pr_amount[]" class="form-control amount"></div><div class="col-1 form-group"><label>Tax</label><input type="number" name="tax[]" class="form-control tax"></div><div class="col-1"><br /><button type="button" class="btn btn-primary" onclick="removeBatch(this);"><i class="fa fa-minus"></i></button></div></div>');
   }

   function displayBankList()
   {
      var type = $('#bill_type').val();
      //var vendor = $('#vendor').val();
      if(type == 2)
      {
         var csrfToken = '{{ csrf_token() }}';
         var url = "{{route('fetch-bank-accounts')}}";
         jQuery.ajax({
           url:  url,
           type: "POST",
           data: {
               '_token' : csrfToken,
               //'vendor': vendor
           },
           success: function(response)
           {
               if(response.status == 1)
               {
                  $('#bank_account').html(response.html);
                  $('#bank_account').parent().removeAttr('hidden');
                  @if(isset($dataArr->bank_id))
                     $('#bank_account').val('{{$dataArr->bank_id}}');
                  @endif
               }
               else
               {
                  $('#bank_account').parent().attr('hidden',true);
               }
           }
       });
         // calculateAmount();
      }
      else
      {
       //  calculateAmount();
         $('#bank_account').parent().attr('hidden',true);
      }
      calculateAmount();
   }

   function removeBatch(elem)
   {

      $(elem).parent().parent().remove();
      calculateAmount();
   }

   $(document).ready(function (){
      displayBankList();
   });

   function calculateAmount()
   {
      var totalAmt = 0;
      var totalGST = 0;
      var totalAmountGST = 0;
      var type = $('#bill_type').val();
      $('.quantity').each(function (){
         quantity = $(this).val();
         if(quantity != '')
         {//console.log(quantity);
            var amount = $(this).parent().parent().find('.rate').val();
            //console.log(amount);
            var product = $(this).parent().parent().find('.product').val();

            if(amount != '' && product != '')
            {
               var pro = product.split('|');
               var taxper = pro[1];
               //alert(taxper);
               total = quantity * amount;
               var tax = 0;
               if(type == 2)
               {
                  if(taxper != '')
                  {
                     tax = (total * taxper) / 100;
                  }
               }
               
               //console.log(total);
               totalAmt = totalAmt + total;
               totalGST = totalGST + tax;
               $(this).parent().parent().find('.amount').val(total);
               $(this).parent().parent().find('.tax').val(tax);
            }
         }
      });
      $('#amount').val(totalAmt);
      $('#gst').val(totalGST);
      var finalAmt = parseFloat(totalAmt) + parseFloat(totalGST);
      $('#total_amount').val(finalAmt);
   }

   function fetch_perfoma_details()
   {
      var pi = $('#pi').val();
      if(pi != '')
      {
         var csrfToken = '{{ csrf_token() }}';
         var url = "{{route('admin.invoice.fetch-perfoma-details')}}";
         jQuery.ajax({
           url:  url,
           type: "POST",
           data: {
               '_token' : csrfToken,
               'pi': pi
           },
           success: function(response)
           {
               if(response.status == 1)
               {
                  //console.log(response.invoice);
                  $('#vendor').val(response.invoice.vendor_id);
                  $('#bill_type').val(response.invoice.bill_type);
                  if(response.invoice.bill_type == 2)
                  {
                     displayBankList();
                     //$('#bank_account').val('"'+response.invoice.bank_id+'"');
                     $('#bank_account').val(response.invoice.bank_id);
                  }
                  
                  $('#delivery_note').val(response.invoice.delivery_note);
                  $('#supplier_ref').val(response.invoice.supplier_ref);
                  $('#buyers_no').val(response.invoice.buyers_no);
                  $('#dated').val(response.invoice.dated);
                  $('#dispatch_doc_no').val(response.invoice.dispatch_doc_no);
                  $('#delivery_note_date').val(response.invoice.delivery_note_date);
                  $('#dispatch_through').val(response.invoice.dispatch_through);
                  $('#destination').val(response.invoice.destination);
                  $('#bill_of_landing').val(response.invoice.bill_of_landing);
                  $('#motor_vehicle_no').val(response.invoice.motor_vehicle_no);
                  $('#terms_of_delivery').val(response.invoice.terms_of_delivery);
                  $('#bill_date').val(response.invoice.bill_date);
                  $('#amount').val(response.invoice.amount);
                  $('#gst').val(response.invoice.gst);
                  $('#total_amount').val(response.invoice.total_amount);
                  $('#production').html(response.html);
                  calculateAmount();
                  //console.log(response.invoice.id);
               }
               else
               {
                  $('#vendor').val('');
                  $('#bill_type').val('');
                  $('#bank_account').val('');
                  $('#delivery_note').val('');
                  $('#supplier_ref').val('');
                  $('#buyers_no').val('');
                  $('#dated').val('');
                  $('#dispatch_doc_no').val('');
                  $('#delivery_note_date').val('');
                  $('#dispatch_through').val('');
                  $('#destination').val('');
                  $('#bill_of_landing').val('');
                  $('#motor_vehicle_no').val('');
                  $('#terms_of_delivery').val('');
                  $('#bill_date').val('');
                  $('#amount').val('');
                  $('#gst').val('');
                  $('#total_amount').val('');
                  $('#production').html('');
                  displayBankList();
                  calculateAmount();
               }
           }
       });
      }
      else
      {
         $('#vendor').val('');
         $('#bill_type').val('');
         $('#bank_account').val('');
         $('#delivery_note').val('');
         $('#supplier_ref').val('');
         $('#buyers_no').val('');
         $('#dated').val('');
         $('#dispatch_doc_no').val('');
         $('#delivery_note_date').val('');
         $('#dispatch_through').val('');
         $('#destination').val('');
         $('#bill_of_landing').val('');
         $('#motor_vehicle_no').val('');
         $('#terms_of_delivery').val('');
         $('#bill_date').val('');
         $('#amount').val('');
         $('#gst').val('');
         $('#total_amount').val('');
         $('#production').html('');
         displayBankList();
         calculateAmount();
      }
   }
</script>
@endsection
@extends('admin.app-admin')
@section('title') Purchase Order @endsection
@section('page-header')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
               <h1>
                  Purchase Order
               </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin.purchase-order') }}">Purchase Order</a> </li>
                  <li class="breadcrumb-item active">Purchase Order Details</li>
               </ol>
            </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <!-- Main content-->
          <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
              <div class="col-12">
                <h4>
                  <i class="fas fa-globe"></i> {{$dataArr['invoice']->company->company_name}}
                  <small class="float-right">Date: {{date('d/m/Y',strtotime($dataArr['invoice']->bill_date))}}</small>
                </h4>
              </div>
              <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
              <div class="col-sm-3 invoice-col">
                From
                <address>
                  <strong>{{$dataArr['invoice']->company->company_name}}</strong><br>
                  {{$dataArr['invoice']->company->address}},<br>
                  {{$dataArr['invoice']->company->state_name}}, {{$dataArr['invoice']->company->country_name}} {{$dataArr['invoice']->company->pincode}}<br>
                  Phone: {{$dataArr['invoice']->company->contact_no}}<br>
                  Mobile: {{$dataArr['invoice']->company->mobile_no}}<br>
                  Email: {{$dataArr['invoice']->company->email_id}}
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-3 invoice-col">
                To
                @if(!empty($dataArr['invoice']->vendor))
                <address>
                  <strong>{{$dataArr['invoice']->vendor->vendor_name ?? ''}}</strong><br>
                  {{$dataArr['invoice']->vendor->address ?? ''}},<br>
                  {{$dataArr['invoice']->vendor->state_name ?? ''}}, {{$dataArr['invoice']->vendor->country_name ?? ''}} {{$dataArr['invoice']->vendor->pincode}}<br>
                  Phone: {{$dataArr['invoice']->vendor->contact_no}}<br>
                  Mobile: {{$dataArr['invoice']->vendor->mobile_no}}<br>
                  Email: {{$dataArr['invoice']->vendor->email_id}}
                </address>
                @endif
              </div>
              <!-- /.col -->
              <div class="col-sm-3 invoice-col">
                <b>Invoice {{$dataArr['invoice']->bill_no}}</b><br>
                <br>
                <b>Payment type:</b> {{($dataArr['invoice']->bill_type == 2) ? 'Bank':'Cash'}}<br>
                <b>Delivery Note:</b> {{$dataArr['invoice']->delivery_note}}<br>
                <b>Supplier Ref.:</b> {{$dataArr['invoice']->supplier_ref}}<br>
                <b>Buyers No.:</b> {{$dataArr['invoice']->buyers_no}}<br>
                <b>Dated:</b> {{date('d/m/Y',strtotime($dataArr['invoice']->dated))}}<br>
                <b>Dispatch Doc No.:</b> {{$dataArr['invoice']->dispatch_doc_no}}<br>
              </div>

              <div class="col-sm-3 invoice-col">
                <br><br>
                <b>Delivery not date:</b> {{date('d/m/Y',strtotime($dataArr['invoice']->delivery_note_date))}}<br>
                <b>Dispatch Through:</b> {{$dataArr['invoice']->dispatch_through}}<br>
                <b>Destination:</b> {{$dataArr['invoice']->destination}}<br>
                <b>Bill of Lading/LR-RR No:</b> {{date('d/m/Y',strtotime($dataArr['invoice']->bill_of_landing))}}<br>
                <b>Motor Vehicle No.:</b> {{$dataArr['invoice']->motor_vehicle_no}}<br>
                <b>Terms of Delivery:</b> {{$dataArr['invoice']->terms_of_delivery}}<br>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
              <div class="col-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>HSN/SAC</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Unit</th>
                    <th>Amount</th>
                    <th>Tax</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php $count = 0;$taxper=0;$j=0; @endphp
                    @foreach($dataArr['material'] as $key => $item)
                      <tr>
                        <td>{{$j+1}}</td>
                        <td>{{$item->raw_material_name}}</td>
                        <td>{{$item->hsn}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->rate}}</td>
                        <td>{{$item->unit}}</td>
                        <td>{{$item->amount}}</td>
                        <td>{{$item->tax}}</td>
                      </tr>
                      @php 
                      $j = $j + 1;
                      $count = $count +1;
                      $taxper = $taxper + $item->gst_percentage;
                      @endphp
                    @endforeach
                    @foreach($dataArr['otherProduct'] as $key => $item)
                      <tr>
                        <td>{{$j+1}}</td>
                        <td>{{$item->other_product_name}}</td>
                        <td>{{$item->hsn}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->rate}}</td>
                        <td>{{$item->unit}}</td>
                        <td>{{$item->amount}}</td>
                        <td>{{$item->tax}}</td>
                      </tr>
                      @php 
                      $j = $j + 1;
                      $count = $count +1;
                      $taxper = $taxper + $item->gst_percentage;
                      @endphp
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
              <!-- accepted payments column -->
              <div class="col-6">
                <p class="lead">Amount Chargeable (in words): <b>{{Helper::amountInWords($dataArr['invoice']->total_amount)}}</b></p>
                <p><b>Declartion:</b></p>
                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                 We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct
                </p>
              </div>
              <!-- /.col -->
              <div class="col-6">

                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      <th style="width:50%">Subtotal:</th>
                      <td>₹{{$dataArr['invoice']->amount}}</td>
                    </tr>
                    @if($dataArr['invoice']->bill_type == 2)
                    @php 
                      $finalTax = $taxper / $count;
                    @endphp
                      @if(!empty($dataArr['invoice']->vendor) && $dataArr['invoice']->company->state_id == $dataArr['invoice']->vendor->state_id)

                      <tr>
                        <th>SGST({{$finalTax/2}}%)</th>
                        <td>₹{{$dataArr['invoice']->gst/2}}</td>
                      </tr>
                       <tr>
                        <th>CGST({{$finalTax/2}}%)</th>
                        <td>₹{{$dataArr['invoice']->gst/2}}</td>
                      </tr>
                      @else
                      <tr>
                        <th>IGST({{$finalTax}}%)</th>
                        <td>₹{{$dataArr['invoice']->gst}}</td>
                      </tr>
                      @endif
                    @endif
                    <tr>
                      <th>Total:</th>
                      <td>₹{{$dataArr['invoice']->total_amount}}</td>
                    </tr>
                  </table>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <!-- <div class="row no-print">
              <div class="col-12">
                <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                  Payment
                </button>
                <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                  <i class="fas fa-download"></i> Generate PDF
                </button>
              </div>
            </div> -->
          </div>
          <!-- /.invoice -->
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>

@endsection
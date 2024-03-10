@extends('admin.app-admin')
@section('title') Payment @endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Payment {{ isset($dataArr) ? "Edit" : "Add" }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payment') }}">Payment</a> </li>
                        <li class="breadcrumb-item active">Payment {{ isset($dataArr) ? "Edit" : "Add" }}</li>
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
                        <h3 class="card-title">Payment</h3>
                    </div>
                    <div class="card-body">
                        <form class="add-payment-form" action="{{ route('admin.payment.store') }}" method="POST">
                            @csrf
                            @method("POST")
                            <input type="hidden" name="id" value="{{ $dataArr['id'] ?? '' }}">
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="payment_date">Payment Date <span style="color: red">*</span></label>
                                    <input id="payment_date" type="text" name="payment_date"
                                           value="{{ (isset($dataArr['payment_date']) && !empty($dataArr['payment_date'])) ? date('m/d/Y',strtotime($dataArr['payment_date'])) : old('payment_date') }}" class="form-control datetimepicker-input">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="payment_type">Payment Mode <span style="color: red">*</span></label>
                                    {{-- @if(Auth::user()->type == 2) --}}
                                        @if(Auth::user()->user_type == 1)
                                            <select class="form-control select2" name="payment_type" id="payment_type" onchange="displayBankList()">
                                                <option value="">Select Payment Type</option>
                                                <option value="1" @if(isset($dataArr) && $dataArr['payment_type'] == 1) selected @endif>Cash</option>
                                            </select>
                                        @else
                                            <select class="form-control select2" name="payment_type" id="payment_type" onchange="displayBankList()">
                                                <option value="">Select Payment Type</option>
                                                <option value="1" @if(isset($dataArr) && $dataArr['payment_type'] == 1) selected @endif>Cash</option>
                                                <option value="2" @if(isset($dataArr) && $dataArr['payment_type'] == 2) selected @endif>Bank</option>
                                            </select>
                                        @endif
                                   {{--  @else
                                        <select class="form-control select2" name="payment_type" id="payment_type" onchange="displayBankList()">
                                            <option value="">Select Payment Type</option>
                                            <option value="1" @if(isset($dataArr) && $dataArr['payment_type'] == 1) selected @endif>Cash</option>
                                            <option value="2" @if(isset($dataArr) && $dataArr['payment_type'] == 2) selected @endif>Bank</option>
                                        </select>
                                    @endif --}}
                                </div>
                                <div class="col-6 form-group"  hidden="">
                                    <label for="bank_account">Bank Account <span style="color: red">*</span></label>
                                    <select class="form-control select2" name="bank_account" id="bank_account">
                                        <option value="">Select Bank Account</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="vendor">Vendor <span style="color: red">*</span></label>
                                    <select class="form-control select2" name="vendor" id="vendor" >
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{$vendor->id}}" {{ (@$dataArr->vendor_id == $vendor->id) ? 'selected':'' }}>{{$vendor->vendor_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="amount">Payment Amount <span style="color: red">*</span></label>
                                    <input type="text" name="pay_amount" id="pay_amount" class="form-control" value="{{ $dataArr['pay_amount'] ?? '' }}">
                                    <input type="hidden" name="type" id="type" class="form-control" value="2">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="reference">Reference</label>
                                    <select class="form-control select2" name="reference" id="reference" onchange="displayref()">
                                        <option value="">Select Reference</option>
                                        <option value="1">Advance</option>
                                        <option value="2">On Account</option>
                                        <option value="3">Against Ref.</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group"  hidden="">
                                    <label for="advance_ref.">Against Ref. <span style="color: red">*</span></label>
                                    <select class="form-control select2" name="advance_ref" id="advance_ref">
                                        <option value="">Select Against Ref</option>
                                        <option value="1">Purchase Order</option>
                                        <option value="2">Purchase Invoice</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group" hidden="">
                                    <label for="amount">Invoice Amount <span style="color: red">*</span></label>
                                    <input type="text" name="amount" id="amount" class="form-control" value="{{ $dataArr['total_amount'] ?? '' }}" readonly="">
                                </div>
                                <div class="col-6 form-group" hidden="">
                                    <label for="invoice_id">Bill <span style="color: red">*</span></label>
                                    <select class="form-control select2" name="invoice_id" id="invoice_id" onchange="fetchAmount()">
                                        <option value="">Select Bill</option>
                                        @foreach($purchaseData as $purchase)
                                            <option value="{{$purchase->id}}|{{$purchase->bill_no}}|{{$purchase->payment_due}}|{{$purchase->total_amount}}">{{ $purchase->bill_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 form-group" >
                                    <label for="advance_ref_po">Purchase Order <span style="color: red">*</span></label>
                                    <select class="form-control select2" name="advance_ref_po" id="advance_ref_po" onchange="displayref()">
                                        <option value="">Select PO</option>
                                        @foreach($podata as $po)
                                            <option value="{{$po->id}}|{{$po->bill_no}}|{{$po->status}}|{{$po->total_amount}}">{{ $po->bill_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="password">Login Password <span style="color: red">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control">
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
    <script src="{{ asset('js/pages/payment/add_payment.js') }}"></script>
    <script type="text/javascript">
        $(".datetimepicker-input").datepicker({
            autoclose: true,
            todayHighlight: true
        }).datepicker('update', new Date());
        $(document).ready(function (){
            displayBankList();
            displayref();
            fetchAmount();
        });

        function fetchAmount()
        {
            var inv = $('#invoice_id').val();
            if(inv != '')
            {
                var invoice = inv.split('|');
                var test = $('#amount').val(invoice[2]);
               // console.log(invoice[2]);
            }
        }

        function displayBankList()
        {
            var type = $('#payment_type').val();
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
                        //console.log(response.status);
                        //console.log(response.html);
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
            //calculateAmount();
        }

        function displayref() {
            var reference = $('#reference').val();
            if(reference == 3)  {
                $('#advance_ref').parent().removeAttr('hidden');
                $('#advance_ref').val(2).trigger('change');
                $('#advance_ref').attr("disabled", true);
                $('#advance_ref_po').parent().attr('hidden',true);
                //   $('#type').parent().removeAttr('hidden');
                $('#invoice_id').parent().removeAttr('hidden');
            } else if(reference == 1)  {
                $('#advance_ref').parent().removeAttr('hidden');
                $('#advance_ref_po').parent().removeAttr('hidden');
                $('#advance_ref').val(1).trigger('change');
                $('#advance_ref').attr("disabled", true); 
                $('#invoice_id').parent().attr('hidden',true);
                // $('#type').parent().removeAttr('hidden');
            } else {
                $('#advance_ref_po').parent().attr('hidden',true);
                $('#advance_ref').parent().attr('hidden',true);
                //  $('#type').parent().attr('hidden',true);
                $('#invoice_id').parent().attr('hidden',true);
            }
        }

    </script>
@endsection

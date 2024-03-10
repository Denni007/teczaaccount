@extends('admin.app-admin')
@section('title') Receipt @endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Receipt {{ isset($dataArr) ? "Edit" : "Add" }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.receipt') }}">Receipt</a> </li>
                        <li class="breadcrumb-item active">Receipt {{ isset($dataArr) ? "Edit" : "Add" }}</li>
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
                        <h3 class="card-title">Receipt</h3>
                    </div>
                    <div class="card-body">
                        <form class="add-receipt-form" action="{{ route('admin.receipt.store') }}" method="POST">
                            @csrf
                            @method("POST")
                            <input type="hidden" name="id" value="{{ $dataArr['id'] ?? '' }}">
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="receipt_date">Receipt Date <span style="color: red">*</span></label>
                                    <input id="receipt_date" type="text" name="receipt_date"
                                           value="{{ (isset($dataArr['receipt_date']) && !empty($dataArr['receipt_date'])) ? date('m/d/Y',strtotime($dataArr['receipt_date'])) : old('receipt_date') }}" class="form-control datetimepicker-input">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="receipt_type">Receipt Mode <span style="color: red">*</span></label>
                                    @if(Auth::user()->type == 2)
                                        @if(Auth::user()->user_type == 1)
                                            <select class="form-control select2" name="receipt_type" id="receipt_type" onchange="displayBankList()">
                                                <option value="">Select Receipt Type</option>
                                                <option value="1" @if(isset($dataArr) && $dataArr['receipt_type'] == 1) selected @endif>Cash</option>
                                            </select>
                                        @else
                                            <select class="form-control select2" name="receipt_type" id="receipt_type" onchange="displayBankList()">
                                                <option value="">Select Receipt Type</option>
                                                <option value="1" @if(isset($dataArr) && $dataArr['receipt_type'] == 1) selected @endif>Cash</option>
                                                <option value="2" @if(isset($dataArr) && $dataArr['receipt_type'] == 2) selected @endif>Bank</option>
                                            </select>
                                        @endif
                                    @else
                                        <select class="form-control select2" name="receipt_type" id="receipt_type" onchange="displayBankList()">
                                            <option value="">Select Receipt Type</option>
                                            <option value="1" @if(isset($dataArr) && $dataArr['receipt_type'] == 1) selected @endif>Cash</option>
                                            <option value="2" @if(isset($dataArr) && $dataArr['receipt_type'] == 2) selected @endif>Bank</option>
                                        </select>
                                    @endif
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
                                    <label for="amount">Receipt Amount <span style="color: red">*</span></label>
                                    <input type="text" name="pay_amount" id="pay_amount" class="form-control" value="{{ $dataArr['pay_amount'] ?? '' }}">
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
                                        <option value="1">Performa Invoice</option>
                                        <option value="2">Purchase Invoice</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group" hidden="">
                                    <label for="amount">Invoice Amount <span style="color: red">*</span></label>
                                    <input type="text" name="amount" id="amount" class="form-control" value="{{ $dataArr['amount'] ?? '' }}" readonly="">
                                </div>
                                <div class="col-6 form-group" hidden="">
                                    <label for="invoice_id">Bill <span style="color: red">*</span></label>
                                    <select class="form-control select2" name="invoice_id" id="invoice_id" onchange="fetchAmount()">
                                        <option value="">Select Bill</option>
                                        @foreach($invoiceData as $invoice)
                                            <option value="{{$invoice->id}}|{{$invoice->bill_no}}|{{$invoice->receipt_due}}">{{ $invoice->bill_no }}</option>
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
    <script src="{{ asset('js/pages/receipt/add_receipt.js') }}"></script>
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
                $('#amount').val(invoice[2]);
            }
        }

        function displayBankList()
        {
            var type = $('#receipt_type').val();
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
            //calculateAmount();
        }

        function displayref() {
            var reference = $('#reference').val();
            if(reference == 3)  {
                $('#advance_ref').parent().removeAttr('hidden');
                $('#advance_ref').val(2).trigger('change');
                $('#type').parent().removeAttr('hidden');
                $('#invoice_id').parent().removeAttr('hidden');
            } else if(reference == 1)  {
                $('#advance_ref').parent().removeAttr('hidden');
                $('#advance_ref').val(1).trigger('change');
                $('#type').parent().removeAttr('hidden');
                $('#invoice_id').parent().removeAttr('hidden');
            } else {
                $('#advance_ref').parent().attr('hidden',true);
                $('#type').parent().attr('hidden',true);
                $('#invoice_id').parent().attr('hidden',true);
            }
        }

    </script>
@endsection

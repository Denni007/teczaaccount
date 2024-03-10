<!DOCTYPE html>
<html>

<head>
</head>

<body>
    <h4 style="text-align: center; font-family: sans-serif;">@if($dataArr['invoice']->invoice_type == 1) RETAIL INVOICE @elseif($dataArr['invoice']->invoice_type == 2) TAX INVOICE @else INVOICE @endif</h4>
    <table
        style="max-width:1170px; margin: 0 auto; width:100%; font-family: sans-serif; border: 1px solid black; border-collapse: collapse; border-spacing: 0px; padding: 5px;">
        <tbody>
            <tr>
                <td style="width: 50%; border-right: 1px solid #000;  padding: 0; vertical-align: top;">
                    <table style="width:100%; border-collapse: collapse; ">
                        <tr>
                            <td colspan="2" style="font-size: 10px; font-weight: bold; padding:1px 0px 1px 2px;">{{$dataArr['invoice']->company->company_name}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style=" border-bottom: 1px solid #000; font-size: 10px; color: #000; padding:1px 0px 1px 2px;">{{$dataArr['invoice']->company->address}},<br/>
                                GSTIN/UIN: {{$dataArr['invoice']->company->gst_no}}<br/>
                                State Name : {{$dataArr['invoice']->company->state_name}}, Code : {{$dataArr['invoice']->company->pincode}}<br/>
                                E-Mail : {{$dataArr['invoice']->company->email_id}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style=" font-size: 10px; color: #000; padding:1px 0px 1px 2px;">Buyer</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 10px; font-weight: bold; padding:1px 0px 1px 2px;">{{$dataArr['invoice']->vendor->vendor_name}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style=" font-size: 10px; color: #000; padding:1px 0px 1px 2px;">
                               {{$dataArr['invoice']->vendor->address}},
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25%; font-size: 10px; color: #000; padding:1px 0px 1px 2px;">
                                GSTIN/UIN :
                            </td>
                            <td style="width: 65%; font-size: 10px; color: #000; padding:1px 0px 1px 2px;">
                                {{$dataArr['invoice']->vendor->gst_no}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25%; font-size: 10px; color: #000; padding:1px 0px 1px 2px;">
                                State Name  :
                            </td>
                            <td style="width: 65%; font-size: 10px; color: #000; padding:1px 0px 1px 2px;">
                                {{$dataArr['invoice']->vendor->state_name}}, Code : {{$dataArr['invoice']->vendor->pincode}}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top; padding: 0px; ">
                    <table style="width:100%; border-collapse: collapse; ">
                        <tr>
                            <td style="width: 50%; padding: 0px;border-right: 1px solid #000; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; ">
                                            Invoice No
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; font-weight: bold; ">
                                            {{$dataArr['invoice']->bill_no}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%; padding: 0px; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; ">
                                            Invoice Dated
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; font-weight: bold; ">
                                            {{date('d-m-Y',strtotime($dataArr['invoice']->bill_date))}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%; padding: 0px;border-right: 1px solid #000; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; ">
                                            Delivery Note
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px;color: #000; padding:1px 0px 1px 2px; vertical-align: top; font-weight: bold; ">
                                            {{$dataArr['invoice']->delivery_note}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%; padding: 0px; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; ">
                                            Mode/Terms of Payment
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; font-weight: bold; ">
                                            BY {{($dataArr['invoice']->bill_type == 2) ? 'Bank':'Cash'}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%; padding: 0px;border-right: 1px solid #000; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px;color: #000; padding:1px 0px 1px 2px; vertical-align: top; ">
                                            Supplier&#39;s Ref.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; font-weight: bold; ">
                                            {{$dataArr['invoice']->supplier_ref}} 
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%; padding: 0px; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; ">
                                            Other Reference(s)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; font-weight: bold; ">
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%; padding: 0px;border-right: 1px solid #000; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; ">
                                            Buyer&#39;s Order No.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px;color: #000; padding:1px 0px 1px 2px; vertical-align: top; font-weight: bold; ">
                                            {{$dataArr['invoice']->buyers_no}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%; padding: 0px; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; ">
                                            Dated
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 2px; vertical-align: top; font-weight: bold; ">
                                            {{date('d-m-Y',strtotime($dataArr['invoice']->dated))}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%; padding: 0px;border-right: 1px solid #000; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; ">
                                            Despatch Document No
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; font-weight: bold; ">
                                            {{$dataArr['invoice']->dispatch_doc_no}} 
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%; padding: 0px; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; ">
                                            Delivery Note Date
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; font-weight: bold; ">
                                            {{date('d-m-Y',strtotime($dataArr['invoice']->delivery_note_date))}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%; padding: 0px;border-right: 1px solid #000; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; ">
                                            Despatched through
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px;color: #000; padding:1px 0px 1px 3px; vertical-align: top; font-weight: bold; ">
                                            {{$dataArr['invoice']->dispatch_through}} 
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%; padding: 0px; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; ">
                                            Destination
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; font-weight: bold; ">
                                            {{$dataArr['invoice']->destination}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%; padding: 0px;border-right: 1px solid #000; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; ">
                                            Bill of Lading/LR-RR No.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; font-weight: bold; ">
                                            dt. {{date('d-m-Y',strtotime($dataArr['invoice']->bill_of_landing))}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%; padding: 0px; border-bottom: 1px solid #000; vertical-align: top; ">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; ">
                                            Motor Vehicle No.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; font-weight: bold; ">
                                            {{$dataArr['invoice']->motor_vehicle_no}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style=" font-size: 10px; color: #000; padding:1px 0px 1px 3px; vertical-align: top; ">
                                Terms of Delivery
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style=" font-size: 10px; font-weight: bold; color: #000; padding:1px 0px 1px 3px; vertical-align: top; ">
                                {{$dataArr['invoice']->terms_of_delivery}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 100%; border-top: 1px solid #000;  padding: 0; vertical-align: top;">
                    <table style="width:100%; border-collapse: collapse; ">
                        <tr>
                            <td style="width: 6%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">SI No.</td>
                            <td style="width: 39%; text-align: left; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">Description of Goods</td>
                            <td style="width: 15%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">HSN/SAC</td>
                            <td style="width: 10%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">Quantity</td>
                            <td style="width: 10%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">Rate</td>
                            <td style="width: 5%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">Per</td>
                            <td style="width: 15%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-bottom: 1px solid #000;">Amount</td>
                        </tr>
                        @php $count = 0;$taxper=0;$totQty =0; @endphp
                            @foreach($dataArr['items'] as $key => $item)
                                <tr>
                                    <td style="width: 6%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;">{{$key+1}}</td>
                                    <td style="width: 39%; text-align: left; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;">{{$item->product->product_name}}</td>
                                    <td style="width: 15%; text-align: center; font-size: 10px;   padding:1px 0px 1px 3px; border-right: 1px solid #000;">{{$item->hsn}}</td>
                                    <td style="width: 10%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;">{{$item->quantity}} {{$item->unit}}</td>
                                    <td style="width: 10%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000;">{{$item->rate}}</td>
                                    <td style="width: 5%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000;">{{$item->unit}}.</td>
                                    <td style="width: 15%; text-align: right; font-size: 10px; font-weight: 600;  padding:1px 5px 1px 3px;">{{$item->amount}}</td>
                                </tr>
                                @php 
                                  $count = $count +1;
                                  $taxper = $taxper + $item->gst_percentage;
                                  $totQty = $totQty + $item->quantity;
                                @endphp
                            @endforeach
                        <tr>
                            <td style="width: 6%; text-align: center; font-size: 10px; font-weight: 600; padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                            <td style="width: 39%; text-align: right; font-size: 10px; font-weight: 600; padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                            <td style="width: 15%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                            <td style="width: 10%; text-align: center; font-size: 10px; font-weight: 600; padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                            <td style="width: 10%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                            <td style="width: 5%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                            <td style="width: 15%; text-align: right; font-size: 10px;  padding:1px 5px 1px 3px; border-top: 1px solid #000;">{{$dataArr['invoice']->amount}}</td>
                        </tr>
                        @if($dataArr['invoice']->bill_type == 2)
                             @if($dataArr['invoice']->company->state_id == $dataArr['invoice']->vendor->state_id)
                                <tr>
                                    <td style="width: 6%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px;  border-right: 1px solid #000;"></td>
                                    <td style="width: 39%; text-align: right; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px;  border-right: 1px solid #000; font-style: italic;">S GST</td>
                                    <td style="width: 15%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px;  border-right: 1px solid #000;"></td>
                                    <td style="width: 10%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px;  border-right: 1px solid #000;"></td>
                                    <td style="width: 10%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px;  border-right: 1px solid #000;"></td>
                                    <td style="width: 5%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px;  border-right: 1px solid #000;"></td>
                                    <td style="width: 15%; text-align: right; font-size: 10px; font-weight: 600;  padding:1px 5px 1px 3px; ">{{$dataArr['invoice']->gst/2}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 6%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 39%; text-align: right; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000; font-style: italic;">C GST</td>
                                    <td style="width: 15%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 10%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 10%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 5%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 15%; text-align: right; font-size: 10px; font-weight: 600;  padding:1px 5px 1px 3px;">{{$dataArr['invoice']->gst/2}}</td>
                                </tr>
                             @else
                                <tr>
                                    <td style="width: 6%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 39%; text-align: right; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000; font-style: italic;">I GST</td>
                                    <td style="width: 15%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 10%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 10%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 5%; text-align: center; font-size: 10px;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                                    <td style="width: 15%; text-align: right; font-size: 10px; font-weight: 600; padding:1px 5px 1px 3px;">{{$dataArr['invoice']->gst}}</td>
                                </tr>
                             @endif
                        @endif
                        
                        <!-- <tr>
                            <td style="width: 6%; text-align: center; font-size: 10px; font-weight: 600;  padding:1px 0px 1px 3px; border-right: 1px solid #000;"></td>
                            <td style="width: 39%; font-size: 10px; font-weight: 600; padding:10px 8px 10px 8px; border-right: 1px solid #000; font-style: italic;">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="text-align: left;">Less :</td>
                                        <td style="text-align: right;">ROUND OFF</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 15%; text-align: center; font-size: 10px; font-weight: 600; padding:10px 8px 10px 8px; border-right: 1px solid #000;"></td>
                            <td style="width: 10%; text-align: center; font-size: 10px; font-weight: 600; padding:10px 8px 10px 8px; border-right: 1px solid #000;"></td>
                            <td style="width: 10%; text-align: center; font-size: 10px; padding:10px 8px 10px 8px; border-right: 1px solid #000;"></td>
                            <td style="width: 5%; text-align: center; font-size: 10px;  padding:10px 8px 10px 8px; border-right: 1px solid #000;"></td>
                            <td style="width: 15%; text-align: right; font-size: 10px; font-weight: 600; padding:10px 8px 10px 8px;">(-)0.20</td>
                        </tr> -->
                        
                        <tr>
                            <td style="width: 6%; text-align: center; font-size: 10px; font-weight: 600; padding:1px 0px 1px 3px; border-right: 1px solid #000; border-top: 1px solid #000;"></td>
                            <td style="width: 39%; text-align: right; font-size: 10px; font-weight: 600; padding:1px 5px 1px 3px; border-right: 1px solid #000; border-top: 1px solid #000;">Total</td>
                            <td style="width: 15%; text-align: center; font-size: 10px; font-weight: 600; padding:1px 0px 1px 3px; border-right: 1px solid #000;  border-top: 1px solid #000;"></td>
                            <td style="width: 10%; text-align: center; font-size: 10px; font-weight: 600; padding:1px 5px 1px 3px; border-right: 1px solid #000;  border-top: 1px solid #000;">{{$totQty}} {{$item->unit}}</td>
                            <td style="width: 10%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000;  border-top: 1px solid #000;"></td>
                            <td style="width: 5%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000;  border-top: 1px solid #000;"></td>
                            <td style="width: 15%; text-align: right; font-size: 10px; font-weight: 600; padding:1px 5px 1px 3px;  border-top: 1px solid #000;">₹ {{$dataArr['invoice']->total_amount}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 100%; border-top: 1px solid #000;  padding: 0; vertical-align: top;">
                    <table style="width:100%; border-collapse: collapse; ">
                        <tr>
                            <td style=" font-size: 10px; padding:1px 0px 1px 3px;"> Amount Chargeable (in words)</td>
                            <td style=" font-size: 10px; text-align: right; padding:1px 5px 1px 3px; font-style: italic;">E. & O.E</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; font-size: 10px; padding:1px 0px 1px 3px; font-weight: bold;">Indian Rupees {{Helper::amountInWords($dataArr['invoice']->total_amount)}} Only</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 100%; border-top: 1px solid #000;  padding: 0; vertical-align: top;">
                    <table style="width:100%; border-collapse: collapse; ">
                        <tr>
                            <td style="width: 55%; text-align: left; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">HSN/SAC</td>
                            <td style="width: 15%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">Taxable Value</td>
                            @if($dataArr['invoice']->bill_type == 2)
                                @if($dataArr['invoice']->company->state_id == $dataArr['invoice']->vendor->state_id)
                                <td style="width: 7%; border-right: 1px solid #000; border-bottom: 1px solid #000;  padding: 0px;">
                                    <table style="width:100%; border-collapse: collapse; padding: 0px; ">
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">Central Tax</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 30%; border-right: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">Rate</td>
                                            <td style="width: 70%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">Amount</td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 8%; border-right: 1px solid #000; border-bottom: 1px solid #000;  padding: 0px;">
                                    <table style="width:100%; border-collapse: collapse; padding: 0px; ">
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">State Tax</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 30%; border-right: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">Rate</td>
                                            <td style="width: 70%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">Amount</td>
                                        </tr>
                                    </table>
                                </td>
                                @else
                                <td style="width: 15%; border-right: 1px solid #000; border-bottom: 1px solid #000;  padding: 0px;">
                                    <table style="width:100%; border-collapse: collapse; padding: 0px; ">
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">Integrated Tax</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 30%; border-right: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">Rate</td>
                                            <td style="width: 70%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">Amount</td>
                                        </tr>
                                    </table>
                                </td>
                                @endif
                            @else
                                <td style="width: 7%; border-right: 1px solid #000; border-bottom: 1px solid #000;  padding: 0px;">
                                    <table style="width:100%; border-collapse: collapse; padding: 0px; ">
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 30%; border-right: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;"></td>
                                            <td style="width: 70%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;"></td>
                                        </tr>
                                    </table>
                                </td>
                            @endif
                            <td style="width: 15%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-bottom: 1px solid #000;">Total Tax Amount</td>
                        </tr>
                        <tr>
                            <td style="width: 55%; text-align: left; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">{{$item->hsn}}</td>
                            <td style="width: 15%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000; border-bottom: 1px solid #000;">{{$dataArr['invoice']->amount}}</td>
                            @if($dataArr['invoice']->bill_type == 2)
                            @php 
                              $finalTax = $taxper / $count;
                            @endphp
                                @if($dataArr['invoice']->company->state_id == $dataArr['invoice']->vendor->state_id)
                                <td style="width: 7%; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 0px; border-spacing: 0px;">
                                    <table style="width:100%; border-collapse: collapse;  padding: 0px; border-spacing: 0px;">
                                        <tr>
                                            <td style="width: 30%; border-right: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">{{$finalTax/2}}%</td>
                                            <td style="width: 70%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">{{$dataArr['invoice']->gst/2}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 8%; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 0px; border-spacing: 0px;">
                                    <table style="width:100%; border-collapse: collapse;  padding: 0px; border-spacing: 0px;">
                                        <tr>
                                            <td style="width: 30%; border-right: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">{{$finalTax/2}}%</td>
                                            <td style="width: 70%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">{{$dataArr['invoice']->gst/2}}</td>
                                        </tr>
                                    </table>
                                </td>
                            @else
                                <td style="width: 15%; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 0px; border-spacing: 0px;">
                                    <table style="width:100%; border-collapse: collapse;  padding: 0px; border-spacing: 0px;">
                                        <tr>
                                            <td style="width: 30%; border-right: 1px solid #000; text-align: center; font-size: 10px; padding:10px 8px 10px 8px;">{{$finalTax}}%</td>
                                            <td style="width: 70%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">{{$dataArr['invoice']->gst}}</td>
                                        </tr>
                                    </table>
                                </td>
                            @endif
                            @else
                                <td style="width: 15%; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 0px; border-spacing: 0px;">
                                    <table style="width:100%; border-collapse: collapse;  padding: 0px; border-spacing: 0px;">
                                        <tr>
                                            <td style="width: 30%; border-right: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;"></td>
                                            <td style="width: 70%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;"></td>
                                        </tr>
                                    </table>
                                </td>
                            @endif
                            <td style="width: 15%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-bottom: 1px solid #000;">{{$dataArr['invoice']->gst}}</td>
                        </tr>
                        <tr>
                            <td style="width: 55%; text-align: right; font-size: 10px; font-weight: bold; padding:1px 0px 1px 3px; border-right: 1px solid #000;">Total</td>
                            <td style="width: 15%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px; border-right: 1px solid #000;">{{$dataArr['invoice']->total_amount}}</td>
                            <td style="width: 15%; border-right: 1px solid #000; padding: 0px; border-spacing: 0px;">
                                <table style="width:100%; border-collapse: collapse;  padding: 0px; border-spacing: 0px;">
                                    <tr>
                                        <td style="width: 30%; border-right: 1px solid #000; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;"></td>
                                        <td style="width: 70%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">{{$dataArr['invoice']->gst}}</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 15%; text-align: center; font-size: 10px; padding:1px 0px 1px 3px;">{{$dataArr['invoice']->gst}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 100%; border-top: 1px solid #000;  padding: 0; vertical-align: top;">
                    <table style="width:100%; border-collapse: collapse; ">
                        <tr>
                            <td style="width: 20%; font-size: 10px; padding:1px 0px 1px 3px;">Tax Amount (in words) :</td>
                            <td style="width: 20%; font-size: 10px; text-align: left; padding:1px 0px 1px 3px; font-weight: bold;">Indian Rupees {{Helper::amountInWords($dataArr['invoice']->gst)}} Only</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 100%;  padding: 0; vertical-align: top;">
                    <table style="width:100%; border-collapse: collapse; ">
                        <tr>
                            <td style="width: 50%;  padding: 0; vertical-align: top;">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td style="width: 50%; font-size: 10px; padding:1px 0px 1px 3px;">Company&#39;s PAN :</td>
                                        <td style="width: 50%; font-size: 10px; text-align: left; padding:1px 0px 1px 3px; font-weight: bold;">{{$dataArr['invoice']->company->pan_no}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="font-size: 10px; text-align: left; padding:1px 0px 1px 3px;">
                                            <u><b>Declaration</b></u><br><br>
                                            We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 50%;  padding: 0; vertical-align: top;">
                                <table style="width:100%; border-collapse: collapse; ">
                                    <tr>
                                        <td colspan="2" style="font-size: 10px; text-align: left; padding:1px 0px 1px 3px;">
                                            Company&#39;s Bank Details
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%; font-size: 10px; padding:1px 0px 1px 3px;">Bank Name :</td>
                                        <td style="width: 70%; font-size: 10px; text-align: left; padding:1px 0px 1px 3px; font-weight: bold;">{{$dataArr['invoice']->bank_account->bank_name ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%; font-size: 10px; padding:1px 0px 1px 3px;">A/c No. :</td>
                                        <td style="width: 70%; font-size: 10px; text-align: left; padding:1px 0px 1px 3px; font-weight: bold;">{{$dataArr['invoice']->bank_account->account_no ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%; font-size: 10px; padding:1px 0px 1px 3px; vertical-align: top;">Branch & IFS Code :</td>
                                        <td style="width: 70%; font-size: 10px; text-align: left; padding:1px 0px 1px 3px; font-weight: bold;">{{$dataArr['invoice']->bank_account->branch_name ?? ''}} & {{$dataArr['invoice']->bank_account->ifsc_code ?? ''}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 100%; border-top: 1px solid #000;  padding: 0; vertical-align: top;">
                    <table style="width:100%; border-collapse: collapse; ">
                        <tr>
                            <td style=" width: 50%; font-size: 10px; padding:10px 8px 10px 8px;  border-right: 1px solid #000; "> Customer&#39;s Seal and Signature</td>
                            <td style="width: 50%;  font-size: 10px; text-align: right; padding:1px 5px 1px 3px; font-weight: bold; ">for {{$dataArr['invoice']->company->company_name}}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%;  font-size: 10px; padding:10px 8px 10px 8px;  border-right: 1px solid #000;"></td>
                            <td style="width: 50%;  font-size: 10px; text-align: right; padding:1px 5px 1px 3px; ">Authorised Signatory</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- <h2 style="text-align: center; font-family: sans-serif;">SUBJECT TO SURAT JURISDICTION</h2> -->
    <p style="text-align: center; font-family: sans-serif;">This is a Computer Generated Invoice</p>
</body>

</html>
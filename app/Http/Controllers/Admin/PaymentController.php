<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\PerfomaInvoice;
use App\Models\BankAccountDetails;
use App\User;
use App\Models\Company;
use App\Models\Vendor;
use App\Models\Production;
use App\Models\PiItems;
use App\Models\Product;
use App\Models\InvoiceItems;
use Barryvdh\DomPDF\Facade as PDF;
use Validator,DB,Auth,Hash;
use App\Helpers\Helper;

class PaymentController extends Controller
{
    public function index(){
        $financial_years = [];
        $current_year = date('Y');
        if((int) date('m') >= 4) {
            for ($year=$current_year; $year > $current_year-5; $year--) {
                array_push($financial_years, $year . '-' . substr( $year+1, -2));
            }
        } else {
            for ($year=$current_year-1; $year > $current_year-6; $year--) {
                array_push($financial_years, $year . '-' . substr( $year+1, -2));
            }
        }

        return view('admin.payment.index', compact('financial_years'));
    }

    public function ajaxData(Request $request) {
        $duration = explode(' - ', $request->duration);
        $startDuration = \Carbon\Carbon::parse(str_replace("/", "-", $duration[0]))->format('Y-m-d');
        $endDuration = \Carbon\Carbon::parse(str_replace("/", "-", $duration[1]))->format('Y-m-d');

        $permissions = Helper::permissions();
        $keyword = "";
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
        }
        $companyId = \Session::get('company');
        $user = Auth::user();
        /* if($user->type == 2)
        { */
            if($user->user_type == 1)
            {
                $Query = Payment::select('payment.*')
                    //->join('users', 'users.id', '=', 'payment.user_id')
                    //->where('users.user_type', $user->user_type)
                    ->where('payment.company_id',$companyId)
                    ->where('payment.payment_type',1)
                    ->whereBetween('payment.payment_date',[$startDuration, $endDuration])
                    ->orderBy('payment.id', 'desc');
                   // print_r($Query);
            }
            else
            {
                $Query = Payment::select('payment.*')
                   // ->join('users', 'users.id', '=', 'payment.user_id')
                   // ->where('users.user_type', $user->user_type)
                    ->where('payment.company_id',$companyId)
                    ->where('payment.payment_type',2)
                    ->whereBetween('payment.payment_date',[$startDuration, $endDuration])
                    ->orderBy('payment.id', 'desc');
            }
        /* }
        else
        {
            $Query = Payment::select('payment.*', 'users.id as userid')
                ->join('users', 'users.id', '=', 'payment.user_id')
                ->where('users.user_type', $user->user_type)
                ->where('payment.company_id',$companyId)
                ->whereBetween('payment.payment_date',[$startDuration, $endDuration])
                ->orderBy('payment.id', 'desc');
        } */


        $data = datatables()->of($Query)
            ->addColumn('invoice_no', function ($Query) {
                return $Query->invoice_no;
            })
            ->addColumn('payment_type', function ($Query) {
                switch($Query->payment_type){
                    case 1:
                        return 'Cash';
                        break;
                    case 2:
                        return 'Bank';
                        break;
                    default:
                        return '-';
                        break;
                }
            })
            ->addColumn('reference', function ($Query) {
                $ref = $Query->reference;
                if($ref==3){
                    $ref = "Against Ref.";
                }
                if($ref==2){
                    $ref = "On Account";
                }
                if($ref==1){
                    $ref = "Advance";
                }
                return $ref;
            })
            ->addColumn('amount', function ($Query) {
                return $Query->amount;
            })
            ->addColumn('payment_date', function ($Query) {
                return date('d-m-Y',strtotime($Query->payment_date));
            })
            // ->addColumn('status', function ($Query) {
            // 	switch($Query->status){
            // 		case 1:
            // 			return 'Approved';
            // 			break;
            // 		case 2:
            // 			return 'Rejected';
            // 			break;
            // 		case 0:
            // 			return 'Pending';
            // 			break;
            // 		default:
            // 			return '-';
            // 			break;
            // 	}
            // })
            ->addColumn('status',function($Query) use($permissions){
                $actionid = isset($Query->id) ? $Query->id : null;
                $status = isset($Query->status) ? $Query->status : 0;
                $btn = '';
                if(empty($permissions))
                {
                    if($status == 0)
                    {
                        $state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 payment-approve" title="Approve" data-id='.$actionid.'>
	                 <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 payment-reject" title="Reject" data-id='.$actionid.'>
	                 <i class="far fa-times-circle"></i></a>';
                    }
                    else
                    {
                        if($status == 1)
                        {
                            $state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 payment-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
                        }
                        else
                        {
                            $state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 payment-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
                        }

                    }
                }
                elseif(!empty($permissions) && in_array('payment_approve',$permissions))
                {
                    if($status == 0)
                    {
                        $state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 payment-approve" title="Approve" data-id='.$actionid.'>
		             <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 payment-reject" title="Reject" data-id='.$actionid.'>
		             <i class="far fa-times-circle"></i></a>';
                    }
                    else
                    {
                        if($status == 1)
                        {
                            $state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 payment-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
                        }
                        else
                        {
                            $state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 payment-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
                        }

                    }

                }
                else
                {
                    switch($Query->status){
                        case 1:
                            return 'Approved';
                            break;
                        case 2:
                            return 'Rejected';
                            break;
                        case 0:
                            return 'Pending';
                            break;
                        default:
                            return 'Pending';
                            break;
                    }
                }


                return $state;

            })
            ->addColumn('action',function($Query) use($permissions){
                $actionid = isset($Query->id) ? $Query->id : null;
                $btn = '';
                $btn =$btn. '<a href='.route("admin.payment.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                if(empty($permissions))
                {
                    $btn = $btn.'<a href='.route("admin.payment.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }
                elseif(!empty($permissions) && in_array('invoice_add',$permissions))
                {
                    $btn = $btn.'<a href='.route("admin.payment.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }

                if(empty($permissions))
                {
                    $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 payment-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }
                elseif(!empty($permissions) && in_array('invoice_delete',$permissions))
                {
                    $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 payment-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }




                return $btn;

            })
            ->rawColumns(['status','action'])
            ->make(true);

        return $data;
    }

    public function add()
    {
        $companyId = \Session::get('company');
        $vendors = Vendor::where('company_id',$companyId)->get();
        $purchaseData = Purchase::select('purchase.*', 'users.id as userid')
            ->where('purchase.company_id',$companyId)
            ->join('users', 'users.id', '=', 'purchase.user_id')
           // ->where('users.user_type', auth()->user()->user_type)
            ->where('purchase.payment_due','!=',0.00)
            ->get();
         $podata = PurchaseOrder::select('po_invoice.*','users.id as userid') 
            ->where('po_invoice.company_id',$companyId)
            ->where('po_invoice.status',1)
            ->join('users', 'users.id', '=', 'po_invoice.user_id')
            ->get(); 
        return view('admin.payment.store',compact('vendors', 'purchaseData','podata'));
    }

    public function store(Request $request){
            //dd($request->all());
        DB::beginTransaction();
        try {
            //dd($request->all());
            $user = Auth::user();
            if($request->has('password'))
            {
                if(Hash::check($request->password, $user->password))
                {
                    $invoiceData = explode('|',$request->invoice_id);
                    $invoice_id = $invoiceData[0];
                    $invoice_no = $invoiceData[1];
                    $amount = $invoiceData[2];
                    $company_id = \Session::get('company');
                    $id = null;
                    if($request->has('id')){
                        $id = $request->id;
                    }
                    $payment = Payment::updateOrCreate(
                        [
                            "id" => $id
                        ],
                        [
                            "company_id" => $company_id,
                            "user_id" => $user->id,
                            "type" => $request->type,
                            "invoice_id" => $invoice_id,
                            "invoice_no" => $invoice_no,
                            "payment_type" => $request->payment_type,
                            "bank_id" => !empty($request->bank_account) ? $request->bank_account: 0,
                            "amount" => $request->pay_amount,
                            "pay_amount" => $request->pay_amount,
                            "reference" => $request->reference,
                            "payment_date" => date('Y-m-d',strtotime($request->payment_date)),
                            "status"=>0,
                        ]);
                    DB::commit();
                    $message = "created";
                    if($id){
                        $message = "updated";
                    }
                    return redirect()->route('admin.payment')->with('success','Payment has been '.$message.' successfully');
                }
                else
                {
                    return back()->with('error', 'Password does not match');
                }
            }
            else
            {
                return back()->with('error', 'Please enter password');
            }

        } catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
        }
    }

    public function view($action = null , $id = null){
        $dataArr = Payment::where('id',$id)->with(['company','user','bank'])->first();
        //dd($dataArr);
        if($action == 'edit'){
            return view('admin.payment.store', compact('dataArr'));
        }else{
            return view('admin.payment.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $payment = Payment::find($request->id);
            $payment->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Payment has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Payment catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();
        try{
            $payment = Payment::find($request->id);
            if(!empty($payment))
            {
                if($payment->type == 1)
                {
                    $invoiceDetails = Invoice::find($payment->invoice_id);
                    if(!empty($invoiceDetails))
                    {
                        $payamount = $payment->pay_amount;
                        $invoiceAmount = $invoiceDetails->payment_due;
                        $remainingPay = $invoiceDetails->payment_due - $payment->pay_amount;
                        Invoice::where('id',$invoiceDetails->id)->update(['payment_due'=>$remainingPay]);
                        if($payment->payment_type == 1)
                        {
                            $walletbalnce = Helper::walletBalance(\Session::get('company'));
                            $updatedWalletBalnce = $walletbalnce + $payment->pay_amount;
                            Company::where('id',\Session::get('company'))->update(['wallet_balance'=>$updatedWalletBalnce]);
                        }
                        elseif ($payment->payment_type == 2)
                        {
                            $bankbalance = BankAccountDetails::where('id',$payment->bank_id)->first();
                            if(!empty($bankbalance))
                            {
                                $updatedamount = $payment->pay_amount + $bankbalance->balance;
                                BankAccountDetails::where('id',$bankbalance->id)->update(['balance'=>$updatedamount]);
                            }
                        }
                    }

                }
                elseif($payment->type == 2)
                {
                    $invoiceDetails = Purchase::find($payment->invoice_id);
                    if(!empty($invoiceDetails))
                    {
                        $payamount = $payment->pay_amount;
                        $invoiceAmount = $invoiceDetails->payment_due;
                        $remainingPay = $invoiceDetails->payment_due - $payment->pay_amount;
                        Purchase::where('id',$invoiceDetails->id)->update(['payment_due'=>$remainingPay]);
                        if($payment->payment_type == 1)
                        {
                            $walletbalnce = Helper::walletBalance(\Session::get('company'));
                            $updatedWalletBalnce = $walletbalnce - $payment->pay_amount;
                            Company::where('id',\Session::get('company'))->update(['wallet_balance'=>$updatedWalletBalnce]);
                        }
                        elseif ($payment->payment_type == 2)
                        {
                            $bankbalance = BankAccountDetails::where('id',$payment->bank_id)->first();
                            if(!empty($bankbalance))
                            {
                                $updatedamount = $bankbalance->balance - $payment->pay_amount;
                                BankAccountDetails::where('id',$bankbalance->id)->update(['balance'=>$updatedamount]);
                            }
                        }
                    }

                }
            }
            $payment->status = 1;
            $payment->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Payment has been approved successfully.');

        } catch(Exception $e){
            \Log::info('Approve Payment catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function reject(Request $request)
    {
        DB::beginTransaction();
        try{
            $payment = Payment::find($request->id);
            $payment->status = 2;
            $payment->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Payment has been rejected successfully.');

        } catch(Exception $e){
            \Log::info('Reject Payment catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }
}

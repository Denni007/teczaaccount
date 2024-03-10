<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Invoice;
use App\Models\Purchase;
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

class ReceiptController extends Controller
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

        return view('admin.receipt.index', compact('financial_years'));
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
        if($user->type == 2)
        {
            if($user->user_type == 1)
            {
                $Query = Receipt::select('receipt.*', 'users.id as userid')
                    ->join('users', 'users.id', '=', 'receipt.user_id')
                    ->where('users.user_type', $user->user_type)
                    ->where('receipt.company_id',$companyId)
                    ->where('receipt.receipt_type',1)
                    ->whereBetween('receipt.receipt_date',[$startDuration, $endDuration])
                    ->orderBy('receipt.id', 'desc');
            }
            else
            {
                $Query = Receipt::select('receipt.*', 'users.id as userid')
                    ->join('users', 'users.id', '=', 'receipt.user_id')
                    ->where('users.user_type', $user->user_type)
                    ->where('receipt.company_id',$companyId)
                    ->whereBetween('receipt.receipt_date',[$startDuration, $endDuration])
                    ->orderBy('receipt.id', 'desc');
            }
        }
        else
        {
            $Query = Receipt::select('receipt.*', 'users.id as userid')
                ->join('users', 'users.id', '=', 'receipt.user_id')
                ->where('users.user_type', $user->user_type)
                ->where('receipt.company_id',$companyId)
                ->whereBetween('receipt.receipt_date',[$startDuration, $endDuration])
                ->orderBy('receipt.id', 'desc');
        }


        $data = datatables()->of($Query)
            ->addColumn('invoice_no', function ($Query) {
                return $Query->invoice_no;
            })
            ->addColumn('receipt_type', function ($Query) {
                switch($Query->receipt_type){
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
                return $Query->reference;
            })
            ->addColumn('amount', function ($Query) {
                return $Query->amount;
            })
            ->addColumn('receipt_date', function ($Query) {
                return date('d-m-Y',strtotime($Query->receipt_date));
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
                        $state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 receipt-approve" title="Approve" data-id='.$actionid.'>
	                 <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 receipt-reject" title="Reject" data-id='.$actionid.'>
	                 <i class="far fa-times-circle"></i></a>';
                    }
                    else
                    {
                        if($status == 1)
                        {
                            $state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 receipt-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
                        }
                        else
                        {
                            $state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 receipt-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
                        }

                    }
                }
                elseif(!empty($permissions) && in_array('receipt_approve',$permissions))
                {
                    if($status == 0)
                    {
                        $state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 receipt-approve" title="Approve" data-id='.$actionid.'>
		             <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 receipt-reject" title="Reject" data-id='.$actionid.'>
		             <i class="far fa-times-circle"></i></a>';
                    }
                    else
                    {
                        if($status == 1)
                        {
                            $state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 receipt-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
                        }
                        else
                        {
                            $state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 receipt-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
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
                $btn =$btn. '<a href='.route("admin.receipt.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                if(empty($permissions))
                {
                    $btn = $btn.'<a href='.route("admin.receipt.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }
                elseif(!empty($permissions) && in_array('invoice_add',$permissions))
                {
                    $btn = $btn.'<a href='.route("admin.receipt.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }

                if(empty($permissions))
                {
                    $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 receipt-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }
                elseif(!empty($permissions) && in_array('invoice_delete',$permissions))
                {
                    $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 receipt-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
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
        $invoiceData = Invoice::select('invoice.*', 'users.id as userid')
            ->join('users', 'users.id', '=', 'invoice.user_id')
            ->where('users.user_type', auth()->user()->user_type)
            ->where('invoice.company_id',$companyId)
            ->where('invoice.receipt_due','!=',0.00)
            ->where('invoice.status',1)
            ->get();
        return view('admin.receipt.store',compact('vendors', 'invoiceData'));
    }

    public function store(Request $request){

        DB::beginTransaction();
        try {
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
                    $receipt = Receipt::updateOrCreate(
                        [
                            "id" => $id
                        ],
                        [
                            "company_id" => $company_id,
                            "user_id" => $user->id,
                            "type" => $request->type,
                            "invoice_id" => $invoice_id,
                            "invoice_no" => $invoice_no,
                            "receipt_type" => $request->receipt_type,
                            "bank_id" => !empty($request->bank_account) ? $request->bank_account: 0,
                            "amount" => $request->amount,
                            "pay_amount" => $request->pay_amount,
                            "reference" => $request->reference,
                            "receipt_date" => date('Y-m-d',strtotime($request->receipt_date)),
                            "status"=>0,
                        ]);
                    DB::commit();
                    $message = "created";
                    if($id){
                        $message = "updated";
                    }
                    return redirect()->route('admin.receipt')->with('success','Receipt has been '.$message.' successfully');
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
        $dataArr = Receipt::where('id',$id)->with(['company','user','bank'])->first();
        //dd($dataArr);
        if($action == 'edit'){
            return view('admin.receipt.store', compact('dataArr'));
        }else{
            return view('admin.receipt.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $receipt = Receipt::find($request->id);
            $receipt->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Receipt has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Receipt catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();
        try{
            $receipt = Receipt::find($request->id);
            if(!empty($receipt))
            {
                if($receipt->type == 1)
                {
                    $invoiceDetails = Invoice::find($receipt->invoice_id);
                    if(!empty($invoiceDetails))
                    {
                        $payamount = $receipt->pay_amount;
                        $invoiceAmount = $invoiceDetails->receipt_due;
                        $remainingPay = $invoiceDetails->receipt_due - $receipt->pay_amount;
                        Invoice::where('id',$invoiceDetails->id)->update(['receipt_due'=>$remainingPay]);
                        if($receipt->receipt_type == 1)
                        {
                            $walletbalnce = Helper::walletBalance(\Session::get('company'));
                            $updatedWalletBalnce = $walletbalnce + $receipt->pay_amount;
                            Company::where('id',\Session::get('company'))->update(['wallet_balance'=>$updatedWalletBalnce]);
                        }
                        elseif ($receipt->receipt_type == 2)
                        {
                            $bankbalance = BankAccountDetails::where('id',$receipt->bank_id)->first();
                            if(!empty($bankbalance))
                            {
                                $updatedamount = $receipt->pay_amount + $bankbalance->balance;
                                BankAccountDetails::where('id',$bankbalance->id)->update(['balance'=>$updatedamount]);
                            }
                        }
                    }

                }
                elseif($receipt->type == 2)
                {
                    $invoiceDetails = Purchase::find($receipt->invoice_id);
                    if(!empty($invoiceDetails))
                    {
                        $payamount = $receipt->pay_amount;
                        $invoiceAmount = $invoiceDetails->receipt_due;
                        $remainingPay = $invoiceDetails->receipt_due - $receipt->pay_amount;
                        Purchase::where('id',$invoiceDetails->id)->update(['receipt_due'=>$remainingPay]);
                        if($receipt->receipt_type == 1)
                        {
                            $walletbalnce = Helper::walletBalance(\Session::get('company'));
                            $updatedWalletBalnce = $walletbalnce - $receipt->pay_amount;
                            Company::where('id',\Session::get('company'))->update(['wallet_balance'=>$updatedWalletBalnce]);
                        }
                        elseif ($receipt->receipt_type == 2)
                        {
                            $bankbalance = BankAccountDetails::where('id',$receipt->bank_id)->first();
                            if(!empty($bankbalance))
                            {
                                $updatedamount = $bankbalance->balance - $receipt->pay_amount;
                                BankAccountDetails::where('id',$bankbalance->id)->update(['balance'=>$updatedamount]);
                            }
                        }
                    }

                }
            }
            $receipt->status = 1;
            $receipt->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Receipt has been approved successfully.');

        } catch(Exception $e){
            \Log::info('Approve Receipt catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function reject(Request $request)
    {
        DB::beginTransaction();
        try{
            $receipt = Receipt::find($request->id);
            $receipt->status = 2;
            $receipt->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Receipt has been rejected successfully.');

        } catch(Exception $e){
            \Log::info('Reject Receipt catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,DB,Auth;
use App\Models\PerfomaInvoice;
use App\Models\BankAccountDetails;
use App\User;
use App\Models\Company;
use App\Models\Vendor;
use App\Models\Production;
use App\Models\PiItems;
use App\Models\Product;
use App\Helpers\Helper;
use Barryvdh\DomPDF\Facade as PDF;

class PIController extends Controller
{
    public function index(){
        return view('admin.pi.index');
    }

    public function ajaxData(Request $request) {
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
				$Query = PerfomaInvoice::select('*')->where('company_id',$companyId)->where('bill_type',1)->with(['user','vendor'])->orderBy('id', 'desc');
			}
			else
			{
				$Query = PerfomaInvoice::select('*')->where('company_id',$companyId)->with(['user','vendor'])->orderBy('id', 'desc');
			}
		}
		else
		{
			$Query = PerfomaInvoice::select('*')->where('company_id',$companyId)->with(['user','vendor'])->orderBy('id', 'desc');
		}
		// dd($Query->get()->toArray());
			
		$data = datatables()->of($Query)
			->addColumn('bill_no', function ($Query) {
				return $Query->bill_no;
			})
			->addColumn('user', function ($Query) {
				return $Query->user->name;
			})
			->addColumn('vendor', function ($Query) {
				return $Query->vendor->vendor_name ?? '';
			})
			->addColumn('bill_type', function ($Query) {
				switch($Query->bill_type){
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
			// ->addColumn('mobile_no', function ($Query) {
			// 	return $Query->mobile_no;
			// })
			->addColumn('total_amount', function ($Query) {
				return $Query->total_amount;
			})
			->addColumn('bill_date', function ($Query) {
				return date('d-m-Y',strtotime($Query->bill_date));
			})
			->addColumn('status',function($Query) use($permissions){
			$actionid = isset($Query->id) ? $Query->id : null;
             $status = isset($Query->status) ? $Query->status : 0;
             	$btn = '';
             	if(empty($permissions))
                {
                	if($status == 0)
	             	{
	             		$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 pi-approve" title="Approve" data-id='.$actionid.'>
	                 <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 pi-reject" title="Reject" data-id='.$actionid.'>
	                 <i class="far fa-times-circle"></i></a>';
	             	}
	             	else
	             	{
	             		if($status == 1)
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 pi-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
	             		}
	             		else
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 pi-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
	             		}
	             		
	             	}
                }
                elseif(!empty($permissions) && in_array('invoice_approve',$permissions))
                {
                	if($status == 0)
	             	{
	             		$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 pi-approve" title="Approve" data-id='.$actionid.'>
	                 <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 pi-reject" title="Reject" data-id='.$actionid.'>
	                 <i class="far fa-times-circle"></i></a>';
	             	}
	             	else
	             	{
	             		if($status == 1)
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 pi-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
	             		}
	             		else
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 pi-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
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
             	$btn = '<a href='.route("admin.perfoma-invoice.download",["id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-file-pdf"></i></a>';
                $btn =$btn. '<a href='.route("admin.perfoma-invoice.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                if(empty($permissions))
                {
                	$btn = $btn.'<a href='.route("admin.perfoma-invoice.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }
                elseif(!empty($permissions) && in_array('invoice_add',$permissions))
	            {
                	$btn = $btn.'<a href='.route("admin.perfoma-invoice.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }

                if(empty($permissions))
                {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 pi-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }
                elseif(!empty($permissions) && in_array('invoice_delete',$permissions))
	            {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 pi-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';	
                }
                
                

                 return $btn;
            
            })
			->rawColumns(['status','action'])
			->make(true);

		return $data;
    }

    public function add(){
    	$companyId = \Session::get('company');
    	$vendors = Vendor::where('company_id',$companyId)->get();
    	$lastId = PerfomaInvoice::orderBy('id', 'desc')->first();
    	$company = Company::where('id',\Session::get('company'))->first();
    	$production = Production::where('company_id',\Session::get('company'))->get();
    	$product = Product::where('company_id',\Session::get('company'))->get();
    	
    	if(!empty($lastId))
    	{
    		$bill_unique_id = $lastId->bill_unique_id + 1;
    		$billNo = 'PI-'.$company->sufix.'-'.date('Y').'/'.$bill_unique_id;
    	}
    	else
    	{
    		$bill_unique_id = 10001;
    		$billNo = 'PI-'.$company->sufix.'-'.date('Y').'/'.$bill_unique_id;
    	}
    	//$billNo  = '123';
        return view('admin.pi.store',compact('vendors','billNo','production','bill_unique_id','product'));
    }

    public function fetchBankAccounts(Request $request)
    {
    	$companyId = \Session::get('company');
    	//dd($companyId);
    	$html = '<option value="">Select Bank Account</option>';
    	// if(isset($request['vendor']) && !empty($request['vendor']))
    	// {
    		$bankDetails = BankAccountDetails::where('company_id',$companyId)->get();
    		if(!$bankDetails->isEmpty())
    		{
    			foreach($bankDetails as $bd)
    			{
    				$html = $html.'<option value="'.$bd->id.'">'.$bd->bank_name.'-'.$bd->account_no.'</option>';
    			}
    		}
    	//}
    	return response()->json(['status'=>1,'html'=>$html]);
    }

    public function store(Request $request){

        DB::beginTransaction();
        try {
        	//dd($request->all());
            $user = Auth::user();
            $id = null;
            if($request->has('id')){
                $id = $request->id;
            }
            $company_id = \Session::get('company');
            $perfomaInvoice = PerfomaInvoice::updateOrCreate(
            [
                "id" => $id
            ],
            [
                "company_id" => $company_id,
                "user_id" => $user->id,
                "vendor_id" => $request->vendor,
                "bill_no" => $request->bill_no,
                "bill_unique_id" => $request->bill_unique_id,
                "bill_type" => $request->bill_type,
                "invoice_type" => $request->invoice_type,
                "bank_id" => !empty($request->bank_account) ? $request->bank_account: 0,
                "amount" => $request->amount,
                "gst" => !empty($request->gst) ? $request->gst : 0.00,
                "cgst" => 0.00,
                "sgst" => 0.00,
                "igst" => 0.00,
                "total_amount" => $request->total_amount,
                "bill_date" => date('Y-m-d',strtotime($request->bill_date)),
                "delivery_note" => $request->delivery_note,
                "supplier_ref" => $request->supplier_ref,
                "buyers_no" => $request->buyers_no,
                "dated" => !empty($request->dated) ? date('Y-m-d',strtotime($request->dated)) : Null,
                "dispatch_doc_no" => $request->dispatch_doc_no,
                "delivery_note_date" => !empty($request->delivery_note_date) ? date('Y-m-d',strtotime($request->delivery_note_date)) : Null,
                "dispatch_through" => $request->dispatch_through,
                "destination" => $request->destination,
                "bill_of_landing" => !empty($request->bill_of_landing) ? date('Y-m-d',strtotime($request->bill_of_landing)) : Null,
                "motor_vehicle_no" => $request->motor_vehicle_no,
                "terms_of_delivery" => $request->terms_of_delivery,
            ]);
            if(isset($request['product']) && !empty($request['product']))
            {
            	PiItems::where('pi_id',$perfomaInvoice->id)->delete();
            	for($i=0;$i<count($request['product']);$i++)
            	{
            		$productdata = explode('|',$request['product'][$i]);
	            	$product_id = $productdata[0];
	            	$gst_percentage = $productdata[1];
	            	
	            	$piItems = new PiItems();
	            	$piItems->pi_id = $perfomaInvoice->id;
	            	$piItems->product_id = $product_id;
	            	$piItems->hsn = $request['hsn'][$i];
	            	$piItems->unit = $request['unit'][$i];
	            	$piItems->quantity = $request['quantity'][$i];
	            	$piItems->gst_percentage = $gst_percentage;
	            	$piItems->tax = $request['tax'][$i];
	            	$piItems->rate = $request['rate'][$i];
	            	$piItems->amount = $request['pr_amount'][$i];
	            	$piItems->save();
            	}
            	
            	// $items = PiItems::where('pi_id',$perfomaInvoice->id)->get();
            	// foreach($items as $it)
            	// {
            	// 	$proddata = Production::where('id',$it->production_id)->first();
            	// 	$oldQty = $proddata->quantity + $it->quantity;
            	// 	Production::where('id',$proddata->id)->update(['quantity'=>$oldQty]);
            	// }
            	// PiItems::where('pi_id',$perfomaInvoice->id)->delete();
            	// for($i=0;$i<count($request['batch']);$i++)
            	// {
            	// 	PiItems::insert(['pi_id'=>$perfomaInvoice->id,'production_id'=>$request['batch'][$i],'quantity'=>$request['quantity'][$i]]);
            	// 	$production = Production::where('id',$request['batch'][$i])->first();
            	// 	//dd($production['quantity']);
            	// 	$finalQty = $production->quantity - $request['quantity'][$i];
            	// 	Production::where('id',$production->id)->update(['quantity'=>$finalQty]);
            	// 	// $production->quantity = $production->quantity - $request['quantity'][$i];
            	// 	// $production->save();
            	// }
            }
            DB::commit();
            $message = "saved";
            if($id){
                $message = "updated";
            }
			return redirect()->route('admin.perfoma-invoice')->with('success','Invoice has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){
    	$companyId = \Session::get('company');
    	
        if($action == 'edit'){
        	$vendors = Vendor::where('company_id',$companyId)->get();
	    	$production = Production::where('company_id',\Session::get('company'))->get();
	        $dataArr = PerfomaInvoice::where('id',$id)->with(['pi_items'])->first();
	        $product = Product::where('company_id',\Session::get('company'))->get();
	        $bill_unique_id = $dataArr->bill_unique_id;
	    	$billNo = $dataArr->bill_no;
            return view('admin.pi.store', compact('dataArr','bill_unique_id','billNo','vendors','production','product'));
        }else{ 
        	$dataArr = [];
        	$invoice = PerfomaInvoice::where('id',$id)->with(['vendor','user','company','bank_account'])->first(); 
        	if(!empty($invoice))
        	{
        		$dataArr['invoice'] = $invoice;
        		//dd($invoice->company->state_name);
        		$dataArr['items'] = PiItems::where('pi_id',$id)->with(['product'])->get();
        		//dd($dataArr['items']);
        	}
            return view('admin.pi.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $pi = PerfomaInvoice::find($request->id);
            PiItems::where('pi_id',$request->id)->delete();
            $pi->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Perfoma Invoice has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Perfoma Invoice catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();
        try{
            $pi = PerfomaInvoice::find($request->id);
            //PiItems::where('pi_id',$request->id)->delete();
            $pi->status = 1;
            $pi->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Perfoma Invoice has been Approved successfully.');

        } catch(Exception $e){
            \Log::info('Approve Perfoma Invoice catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function reject(Request $request)
    {
        DB::beginTransaction();
        try{
            $pi = PerfomaInvoice::find($request->id);
            //PiItems::where('pi_id',$request->id)->delete();
            $pi->status = 2;
            $pi->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Perfoma Invoice has been rejected successfully.');

        } catch(Exception $e){
            \Log::info('reject Perfoma Invoice catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }
    public function downloadInvoice($id)
    {
    	$dataArr = [];
        $invoice = PerfomaInvoice::where('id',$id)->with(['vendor','user','company','bank_account'])->first(); 
		$dataArr['invoice'] = $invoice;
		//dd($invoice->company->state_name);
		$dataArr['items'] = PiItems::where('pi_id',$id)->with(['product'])->get();
    	$pdf=PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'fontHeightRatio' => 1.4]);
        $html = view('admin.pi.pdf',compact('dataArr'))->render();
        return $pdf->loadHTML($html)->setPaper('a4', 'potrait')->setWarnings(false)->stream('perfoma-invoices.pdf');
        //return view('admin.pi.view', compact('dataArr'));
    }
}

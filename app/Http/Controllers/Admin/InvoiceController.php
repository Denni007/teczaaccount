<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
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
use Validator,DB,Auth;
use App\Helpers\Helper;

class InvoiceController extends Controller
{
    public function index(){

        return view('admin.invoice.index');
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
				$Query = Invoice::select('*')->where('company_id',$companyId)->where('bill_type',1)->with(['user','vendor'])->orderBy('id', 'desc');
			}
			else
			{
				$Query = Invoice::select('*')->where('company_id',$companyId)->with(['user','vendor'])->orderBy('id', 'desc');
			}
		}
		else
		{
			$Query = Invoice::select('*')->where('company_id',$companyId)->with(['user','vendor'])->orderBy('id', 'desc');
		}
		
			
		$data = datatables()->of($Query)
			->addColumn('bill_no', function ($Query) {
				return $Query->bill_no;
			})
			->addColumn('user', function ($Query) {
				return $Query->user->name;
			})
			->addColumn('vendor', function ($Query) {
				return $Query->vendor->vendor_name;
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
	             		$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 invoice-approve" title="Approve" data-id='.$actionid.'>
	                 <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 invoice-reject" title="Reject" data-id='.$actionid.'>
	                 <i class="far fa-times-circle"></i></a>';
	             	}
	             	else
	             	{
	             		if($status == 1)
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 invoice-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
	             		}
	             		else
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 invoice-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
	             		}
	             		
	             	}
                }
                elseif(!empty($permissions) && in_array('invoice_approve',$permissions))
                {
                	if($status == 0)
	             	{
	             		$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 invoice-approve" title="Approve" data-id='.$actionid.'>
	                 <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 invoice-reject" title="Reject" data-id='.$actionid.'>
	                 <i class="far fa-times-circle"></i></a>';
	             	}
	             	else
	             	{
	             		if($status == 1)
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 invoice-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
	             		}
	             		else
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 invoice-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
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
             	$btn = '<a href='.route("admin.invoice.download",["id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-file-pdf"></i></a>';
                $btn =$btn. '<a href='.route("admin.invoice.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                if(empty($permissions))
                {
	                $btn = $btn.'<a href='.route("admin.invoice.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
	                
	            }
	            elseif(!empty($permissions) && in_array('invoice_add',$permissions))
	            {
	            	$btn = $btn.'<a href='.route("admin.invoice.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
	            }
	            if(empty($permissions))
                {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 invoice-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }
                elseif(!empty($permissions) && in_array('invoice_delete',$permissions))
	            {
	            	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 invoice-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
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
    	$lastId = Invoice::orderBy('id', 'desc')->first();
    	$company = Company::where('id',\Session::get('company'))->first();
    	$production = Production::where('company_id',\Session::get('company'))->get();
    	$product = Product::where('company_id',\Session::get('company'))->get();
    	$piInvoice = PerfomaInvoice::where('company_id',\Session::get('company'))->where('status',1)->get();

    	if(!empty($lastId))
    	{
    		$bill_unique_id = $lastId->bill_unique_id + 1;
    		$billNo = 'INV-'.$company->sufix.'-'.date('Y').'/'.$bill_unique_id;
    	}
    	else
    	{
    		$bill_unique_id = 10001;
    		$billNo = 'INV-'.$company->sufix.'-'.date('Y').'/'.$bill_unique_id;
    	}
    	//$billNo  = '123';
        return view('admin.invoice.store',compact('vendors','billNo','production','bill_unique_id','product','piInvoice'));
    }

    public function fetchBankAccounts(Request $request)
    {
    	$companyId = \Session::get('company');
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
            $user = Auth::user();
            $id = null;
            if($request->has('id')){
                $id = $request->id;
            }
            $company_id = \Session::get('company');
            $invoice = Invoice::updateOrCreate(
            [
                "id" => $id
            ],
            [
                "company_id" => $company_id,
                "pi_id" => $request->pi,
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
                //"payment_due" => $request->total_amount,
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
                "status"=>0,
            ]);
            if(isset($request['product']) && !empty($request['product'] && $invoice->id))
            {
            	InvoiceItems::where('invoice_id',$invoice->id)->delete();
            	for($i=0;$i<count($request['product']);$i++)
            	{
            		$productdata = explode('|',$request['product'][$i]);
	            	$product_id = $productdata[0];
	            	$gst_percentage = $productdata[1];
	            	
	            	$Items = new InvoiceItems();
	            	$Items->invoice_id = $invoice->id;
	            	$Items->product_id = $product_id;
	            	$Items->hsn = $request['hsn'][$i];
	            	$Items->unit = $request['unit'][$i];
	            	$Items->quantity = $request['quantity'][$i];
	            	$Items->gst_percentage = $gst_percentage;
	            	$Items->tax = $request['tax'][$i];
	            	$Items->rate = $request['rate'][$i];
	            	$Items->amount = $request['pr_amount'][$i];
	            	$Items->save();
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
			return redirect()->route('admin.invoice')->with('success','Invoice has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){
    	$companyId = \Session::get('company');
    	
        if($action == 'edit'){
        	$vendors = Vendor::where('company_id',$companyId)->get();
	    	$production = Production::where('company_id',\Session::get('company'))->get();
	        $dataArr = Invoice::where('id',$id)->with(['invoice_items'])->first();
	        $product = Product::where('company_id',\Session::get('company'))->get();
	        $bill_unique_id = $dataArr->bill_unique_id;
	    	$billNo = $dataArr->bill_no;
	    	$piInvoice = PerfomaInvoice::where('company_id',\Session::get('company'))->where('status',1)->get();
            return view('admin.invoice.store', compact('dataArr','bill_unique_id','billNo','vendors','production','product','piInvoice'));
        }else{ 
        	$dataArr = [];
        	$invoice = Invoice::where('id',$id)->with(['vendor','user','company','bank_account'])->first(); 
        	if(!empty($invoice))
        	{
        		$dataArr['invoice'] = $invoice;
        		$dataArr['items'] = InvoiceItems::where('invoice_id',$id)->with(['product'])->get();
        		
        	}
            return view('admin.invoice.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $invoice = Invoice::find($request->id);
            InvoiceItems::where('invoice_id',$request->id)->delete();
            $invoice->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Invoice has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Invoice catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();
        try{
            $invoice = Invoice::find($request->id);

            //PiItems::where('pi_id',$request->id)->delete();
            $invoice->status = 1;
            $invoice->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Invoice has been Approved successfully.');

        } catch(Exception $e){
            \Log::info('Approve Invoice catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function reject(Request $request)
    {
        DB::beginTransaction();
        try{
            $invoice = Invoice::find($request->id);
            //PiItems::where('pi_id',$request->id)->delete();
            $invoice->status = 2;
            $invoice->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Invoice has been rejected successfully.');

        } catch(Exception $e){
            \Log::info('reject Invoice catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function downloadInvoice($id)
    {
    	$dataArr = [];
        $invoice = Invoice::where('id',$id)->with(['vendor','user','company','bank_account'])->first(); 
		$dataArr['invoice'] = $invoice;
		$dataArr['items'] = InvoiceItems::where('invoice_id',$id)->with(['product'])->get();
		
    	$pdf=PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'fontHeightRatio' => 1.4]);
        $html = view('admin.invoice.pdf',compact('dataArr'))->render();
        return $pdf->loadHTML($html)->setPaper('a4', 'potrait')->setWarnings(true)->download('invoice.pdf');
        //return view('admin.pi.view', compact('dataArr'));
    }

    public function fetchPerfomaDetails(Request $request)
    {
    	$id = $request->pi;
    	if(!empty($id))
    	{
    		$product = Product::where('company_id',\Session::get('company'))->get();
    		$invoice = PerfomaInvoice::where('id',$id)->with(['pi_items'])->first();
    		$html = '';
    		if(!empty($invoice))
    		{
    			$invoice->dated = !empty($invoice->dated) ? date('m/d/Y',strtotime($invoice->dated)) : ''; 
    			$invoice->delivery_note_date = !empty($invoice->delivery_note_date) ? date('m/d/Y',strtotime($invoice->delivery_note_date)) : ''; 
    			$invoice->bill_of_landing = !empty($invoice->bill_of_landing) ? date('m/d/Y',strtotime($invoice->bill_of_landing)) : ''; 
    			$invoice->bill_date = !empty($invoice->bill_date) ? date('m/d/Y',strtotime($invoice->bill_date)) : ''; 
    			if(!empty($invoice['pi_items']))
    			{
    				foreach($invoice['pi_items'] as $pi)
    				{
    					$option = '';
    					foreach($product as $pr)
    					{
    						$selected = '';
    						if($pi->product_id == $pr->id)
    						{
    							$selected = 'selected';
    						}
    						$option = $option . '<option value="'.$pr->id.'|'.$pr->gst_percentage.'" '.$selected.'>'.$pr->product_name.'</option>';
    					}
    					$html = $html . '<div class="row">
                                <div class="col-3 form-group">
                                    <label>Select Product</label>
                                    <select class="form-control product" name="product[]" onchange="calculateAmount()">
                                       <option value="">Select Product</option>'.$option.'
                                    </select>
                                 </div>
                                 <div class="col-1 form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity[]" class="form-control quantity" onblur="calculateAmount()" value="'.$pi->quantity.'">
                                 </div>
                                 <div class="col-1 form-group">
                                    <label>HAS/SAC</label>
                                    <input type="text" name="hsn[]" class="form-control hsn" value="'.$pi->hsn.'">
                                 </div>
                                 <div class="col-2 form-group">
                                    <label>Rate</label>
                                    <input type="number" name="rate[]" class="form-control rate" onblur="calculateAmount()" value="'.$pi->rate.'">
                                 </div>
                                 <div class="col-1 form-group">
                                    <label>Unit</label>
                                    <input type="text" name="unit[]" class="form-control unit" value="'.$pi->unit.'">
                                 </div>
                                 <div class="col-2 form-group">
                                    <label>Amount</label>
                                    <input type="number" name="pr_amount[]" class="form-control amount" value="'.$pi->amount.'">
                                 </div>
                                 <div class="col-1 form-group">
                                    <label>Tax</label>
                                    <input type="number" name="tax[]" class="form-control tax" value="'.$pi->tax.'">
                                 </div>
                                 <div class="col-1">
                                    <br />
                                    <button type="button" class="btn btn-primary" onclick="removeBatch(this);"><i class="fa fa-minus"></i></button>
                                 </div>
                              </div>';
    				}
    			}
    		}
    		return response()->json(['status'=>1,'invoice'=>$invoice,'html'=>$html]);
    	}
    	else
    	{
    		return response()->json(['status'=>2,'message'=>'No data']);
    	}
    }
	public function salesChart(){
		$data = Invoice::select('amount', DB::raw('count(*) as total'));

			/* $data = Invoice::select('year(bill_date),month(bill_date),sum(amount)')
			->groupByRaw('year(bill_date),month(bill_date)')
			->orderByRaw('year(bill_date),month (bill_date)')
			->get(); */
			echo "<pre>";
			var_dump($data);
			
			
			//return $data;
	}
}

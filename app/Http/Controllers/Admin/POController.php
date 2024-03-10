<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,DB,Auth;
use App\Models\PurchaseOrder;
use App\Models\BankAccountDetails;
use App\User;
use App\Models\Company;
use App\Models\Vendor;
use App\Models\Production;
use App\Models\PiItems;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\OtherProduct;
use App\Models\POItems;
use App\Helpers\Helper;
use Barryvdh\DomPDF\Facade as PDF;

class POController extends Controller
{
    public function index(){

        return view('admin.po.index');
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
				$Query = PurchaseOrder::select('*')->where('company_id',$companyId)->where('bill_type',1)->with(['user','vendor'])->orderBy('id', 'desc');
			}
			else
			{
				$Query = PurchaseOrder::select('*')->where('company_id',$companyId)->with(['user','vendor'])->orderBy('id', 'desc');
			}
		}
		else
		{
			$Query = PurchaseOrder::select('*')->where('company_id',$companyId)->with(['user','vendor'])->orderBy('id', 'desc');
		}
		
		
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
		         		$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 po-approve" title="Approve" data-id='.$actionid.'>
		             <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 po-reject" title="Reject" data-id='.$actionid.'>
		             <i class="far fa-times-circle"></i></a>';
		         	}
		         	else
		         	{
		         		if($status == 1)
		         		{
		         			$state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 po-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
		         		}
		         		else
		         		{
		         			$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 po-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
		         		}
		         		
		         	}
             	}
             	elseif(!empty($permissions) && in_array('invoice_approve',$permissions))
                {
             		if($status == 0)
	             	{
	             		$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 po-approve" title="Approve" data-id='.$actionid.'>
	                 <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 po-reject" title="Reject" data-id='.$actionid.'>
	                 <i class="far fa-times-circle"></i></a>';
	             	}
	             	else
	             	{
	             		if($status == 1)
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 po-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
	             		}
	             		else
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 po-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
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
             	$btn = '<a href='.route("admin.purchase-order.download",["id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-file-pdf"></i></a>';
                $btn = $btn.'<a href='.route("admin.purchase-order.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                if(empty($permissions))
                {
                	$btn = $btn.'<a href='.route("admin.purchase-order.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }
                elseif(!empty($permissions) && in_array('invoice_add',$permissions))
                {
                	$btn = $btn.'<a href='.route("admin.purchase-order.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }

                if(empty($permissions))
                {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 po-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }
                elseif(!empty($permissions) && in_array('invoice_delete',$permissions))
	            {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 po-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';	
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
    	$lastId = PurchaseOrder::orderBy('id', 'desc')->first();
    	$company = Company::where('id',\Session::get('company'))->first();
    	$production = Production::where('company_id',\Session::get('company'))->get();
    	$product = Product::where('company_id',\Session::get('company'))->get();
    	$rawmaterial = RawMaterial::where('company_id',\Session::get('company'))->get();
    	$otherProducts = OtherProduct::where('company_id',\Session::get('company'))->get();
    	if(!empty($lastId))
    	{
    		$bill_unique_id = $lastId->bill_unique_id + 1;
    		$billNo = 'PO-'.$company->sufix.'-'.date('Y').'/'.$bill_unique_id;
    	}
    	else
    	{
    		$bill_unique_id = 10001;
    		$billNo = 'PO-'.$company->sufix.'-'.date('Y').'/'.$bill_unique_id;
    	}
    	//$billNo  = '123';
        return view('admin.po.store',compact('vendors','billNo','production','bill_unique_id','product','rawmaterial','otherProducts'));
    }

    public function fetchBankAccounts(Request $request)
    {
    	$html = '<option value="">Select Bank Account</option>';
    	if(isset($request['vendor']) && !empty($request['vendor']))
    	{
    		$bankDetails = BankAccountDetails::where('vendor_id',$request['vendor'])->get();
    		if(!$bankDetails->isEmpty())
    		{
    			foreach($bankDetails as $bd)
    			{
    				$html = $html.'<option value="'.$bd->id.'">'.$bd->bank_name.'-'.$bd->account_no.'</option>';
    			}
    		}
    	}
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
            $purchaseOrder = PurchaseOrder::updateOrCreate(
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
                "bank_id" => 0,
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
            if(isset($request['rawmaterial']) && !empty($request['rawmaterial']))
            {
            	POItems::where('po_id',$purchaseOrder->id)->where('product_type',1)->delete();
            	for($i=0;$i<count($request['rawmaterial']);$i++)
            	{
            		$materialdata = explode('|',$request['rawmaterial'][$i]);
	            	//$product_id = $productdata[0];
	            	if(is_numeric($materialdata[0]))
	            	{
	            		$materialId = $materialdata[0];
	            		$itemData = RawMaterial::where('id',$materialId)->first();
	            		if(!empty($itemData))
	            		{
	            			RawMaterial::where('id',$materialId)->update(['qty'=>$itemData->qty + $request['quantity'][$i],'applicable_gst'=>$request['gst_per'][$i]]);
	            		}
	            		else
	            		{
	            			$materialId = RawMaterial::insertGetId(['raw_material_name'=>$materialdata[0],'qty'=>$request['quantity'][$i],'unit'=>$request['unit'][$i],'applicable_gst'=>$request['gst_per'][$i],'company_id'=>$company_id,'createdBy'=>$user->id]);
	            		}
	            	}
	            	else
	            	{
	            		$materialId = RawMaterial::insertGetId(['raw_material_name'=>$materialdata[0],'qty'=>$request['quantity'][$i],'unit'=>$request['unit'][$i],'applicable_gst'=>$request['gst_per'][$i],'company_id'=>$company_id,'createdBy'=>$user->id]);
	            	}
	            	
	            	$poItems = new POItems();
	            	$poItems->po_id = $purchaseOrder->id;
	            	$poItems->product_type = 1;
	            	$poItems->product_id = $materialId;
	            	$poItems->hsn = $request['hsn'][$i];
	            	$poItems->unit = $request['unit'][$i];
	            	$poItems->quantity = $request['quantity'][$i];
	            	$poItems->gst_percentage = $request['gst_per'][$i];
	            	$poItems->tax = $request['tax'][$i];
	            	$poItems->rate = $request['rate'][$i];
	            	$poItems->amount = $request['pr_amount'][$i];
	            	$poItems->save();
            	}
            }
            if(isset($request['otherproduct']) && !empty($request['otherproduct']))
            {
            	POItems::where('po_id',$purchaseOrder->id)->where('product_type',2)->delete();
            	for($i=0;$i<count($request['otherproduct']);$i++)
            	{
            		$otherdata = explode('|',$request['otherproduct'][$i]);
	            	//$product_id = $productdata[0];
	            	if(is_numeric($otherdata[0]))
	            	{
	            		$otherId = $otherdata[0];
	            		$prData = OtherProduct::where('id',$otherId)->first();
	            		if(!empty($prData))
	            		{
	            			OtherProduct::where('id',$otherId)->update(['gst_percentage'=>$request['other_gst'][$i],'quantity'=>$prData->quantity + $request['other_quantity'][$i]]);
	            		}
	            		else
	            		{
	            			$otherId = OtherProduct::insertGetId(['other_product_name'=>$otherdata[0],'quantity'=>$request['other_quantity'][$i],'unit'=>$request['other_unit'][$i],'gst_percentage'=>$request['other_gst'][$i],'company_id'=>$company_id]);
	            		}
	            		
	            	}
	            	else
	            	{
	            		$otherId = OtherProduct::insertGetId(['other_product_name'=>$otherdata[0],'quantity'=>$request['other_quantity'][$i],'unit'=>$request['other_unit'][$i],'gst_percentage'=>$request['other_gst'][$i],'company_id'=>$company_id]);
	            	}
	            	
	            	$poItems = new POItems();
	            	$poItems->po_id = $purchaseOrder->id;
	            	$poItems->product_type = 2;
	            	$poItems->product_id = $otherId;
	            	$poItems->hsn = $request['other_hsn'][$i];
	            	$poItems->unit = $request['other_unit'][$i];
	            	$poItems->quantity = $request['other_quantity'][$i];
	            	$poItems->gst_percentage = $request['other_gst'][$i];
	            	$poItems->tax = $request['other_tax'][$i];
	            	$poItems->rate = $request['other_rate'][$i];
	            	$poItems->amount = $request['other_amount'][$i];
	            	$poItems->save();
            	}
            }
            DB::commit();
            $message = "saved";
            if($id){
                $message = "updated";
            }
			return redirect()->route('admin.purchase-order')->with('success','Invoice has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){
    	$companyId = \Session::get('company');
    	
        if($action == 'edit'){
        	$vendors = Vendor::where('company_id',$companyId)->get();
	    	$production = Production::where('company_id',\Session::get('company'))->get();
	        $dataArr = PurchaseOrder::where('id',$id)->with(['po_items'])->first();
	        $product = Product::where('company_id',\Session::get('company'))->get();
	        $rawmaterial = RawMaterial::where('company_id',\Session::get('company'))->get();
    		$otherProducts = OtherProduct::where('company_id',\Session::get('company'))->get();
	        $bill_unique_id = $dataArr->bill_unique_id;
	    	$billNo = $dataArr->bill_no;
            return view('admin.po.store', compact('dataArr','bill_unique_id','billNo','vendors','production','product','rawmaterial','otherProducts'));
        }else{ 
        	$dataArr = [];
        	$invoice = PurchaseOrder::where('id',$id)->with(['vendor','user','company'])->first(); 
        	if(!empty($invoice))
        	{
        		$dataArr['invoice'] = $invoice;
        		//dd($invoice->company->state_name);
        		$dataArr['material'] =  POItems::join('raw_materials','raw_materials.id','po_invoice_item.product_id')->where('product_type',1)->where('po_id',$id)->select('po_invoice_item.id','hsn','po_invoice_item.unit','quantity','rate','po_invoice_item.gst_percentage','po_invoice_item.amount','po_invoice_item.tax','raw_materials.raw_material_name')->get();
        		$dataArr['otherProduct'] =  POItems::join('other_product','other_product.id','po_invoice_item.product_id')->where('product_type',2)->where('po_id',$id)->select('po_invoice_item.id','hsn','po_invoice_item.unit','po_invoice_item.quantity','rate','po_invoice_item.gst_percentage','po_invoice_item.amount','po_invoice_item.tax','other_product.other_product_name')->get();
        		//$dataArr['items'] = POItems::where('po_id',$id)->with(['product'])->get();
        		//dd($dataArr['otherProduct']);
        	}
            return view('admin.po.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $po = PurchaseOrder::find($request->id);
            POItems::where('po_id',$request->id)->delete();
            $po->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Purchase Order has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Purchase Order catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();
        try{
            $po = PurchaseOrder::find($request->id);
            //PiItems::where('pi_id',$request->id)->delete();
            $po->status = 1;
            $po->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Purchase Order has been Approved successfully.');

        } catch(Exception $e){
            \Log::info('Approve Purchase Order catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function reject(Request $request)
    {
        DB::beginTransaction();
        try{
            $po = PurchaseOrder::find($request->id);
            //PiItems::where('pi_id',$request->id)->delete();
            $po->status = 2;
            $po->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Purchase order has been rejected successfully.');

        } catch(Exception $e){
            \Log::info('reject Purchase Order catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function downloadInvoice($id)
    {
    	$dataArr = [];
        $invoice = PurchaseOrder::where('id',$id)->with(['vendor','user','company'])->first(); 
		$dataArr['invoice'] = $invoice;
		//dd($invoice->company->state_name);
		$dataArr['material'] =  POItems::join('raw_materials','raw_materials.id','po_invoice_item.product_id')->where('product_type',1)->where('po_id',$id)->select('po_invoice_item.id','hsn','po_invoice_item.unit','quantity','rate','po_invoice_item.gst_percentage','po_invoice_item.amount','po_invoice_item.tax','raw_materials.raw_material_name')->get();
        $dataArr['otherProduct'] =  POItems::join('other_product','other_product.id','po_invoice_item.product_id')->where('product_type',2)->where('po_id',$id)->select('po_invoice_item.id','hsn','po_invoice_item.unit','po_invoice_item.quantity','rate','po_invoice_item.gst_percentage','po_invoice_item.amount','po_invoice_item.tax','other_product.other_product_name')->get();
		//dd($dataArr['items']);
    	$pdf=PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'fontHeightRatio' => 1.4]);
        $html = view('admin.po.pdf',compact('dataArr'))->render();
        return $pdf->loadHTML($html)->setPaper('a4', 'potrait')->setWarnings(true)->download('purchase-order.pdf');
        //return view('admin.pi.view', compact('dataArr'));
    }
}

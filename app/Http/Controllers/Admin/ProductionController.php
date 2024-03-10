<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,DB,Auth;
use App\Models\Product;
use App\Models\Production;
use App\Models\ProductType;
use App\Models\RawMaterial;
use App\Models\ProductionMaterial;
use App\Models\ProductMaterial;
use App\Helpers\Helper;
use App\Models\Company;

class ProductionController extends Controller
{
    public function index(){

        return view('admin.production.index');
    }

    public function ajaxData(Request $request) {
    	$permissions = Helper::permissions();
		$keyword = "";
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;
		}
		$companyId = \Session::get('company');
		$Query = Production::select('*')->with(['product'])->where('company_id',$companyId)->orderBy('id', 'desc')->get();
		
		
		// if (!empty($keyword)) {
  //           $Query->where('company_name', 'like', '%' . $keyword . '%');
		// }
			
		$data = datatables()->of($Query)
			->addColumn('product_name', function ($Query) {
				return $Query->product->product_name;
			})
			->addColumn('batch_no', function ($Query) {
				return $Query->batch_no;
			})
			->addColumn('quantity', function ($Query) {
				return $Query->quantity;
			})
			->addColumn('weight', function ($Query) {
				return $Query->weight;
			})
			->addColumn('certified', function ($Query) {
				switch($Query->certified){
					case 1:
						return 'ISO Certified';
						break;
					case 2:
						return 'Non ISO Certified';
						break;
					default:
						return '-';
						break;
				}
				//return $Query->certified;
			})->addColumn('action',function($Query) use($permissions){
             $actionid = isset($Query->id) ? $Query->id : null;

                $btn = '<a href='.route("admin.production.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                if(empty($permissions))
                {
                	$btn = $btn.'<a href='.route("admin.production.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
            	}
            	else if(!empty($permissions) && in_array('production_add',$permissions))
            	{
            		$btn = $btn.'<a href='.route("admin.production.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
            	}

            	if(empty($permissions))
                {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 production-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }
                else if(!empty($permissions) && in_array('production_delete',$permissions))
                {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 production-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }
				return $btn;
            
            })
			->rawColumns(['action'])
			->make(true);

		return $data;
    }

    public function add(){
    	$companyId = \Session::get('company');
    	$product = Product::where('company_id',$companyId)->get();
    	$productType = ProductType::get();
    	$material = RawMaterial::where('company_id',\Session::get('company'))->get();
    	$lastId = Production::orderBy('id','desc')->first();
    	$company = Company::where('id',\Session::get('company'))->first();
    	if(!empty($lastId))
    	{	//BATCH-TCZ-2021/2203212109
    		$batch_unique_id = $lastId->batch_unique_id + 1;
    		$batchNo = 'BATCH-'.$company->sufix.'-'.date('Y').'/'.date('dmy').$batch_unique_id;
    	}
    	else
    	{
    		$batch_unique_id = 10001;
    		$batchNo = 'BATCH-'.$company->sufix.'-'.date('Y').'/'.date('dmy').$batch_unique_id;
    	}
    	return view('admin.production.store',compact('productType','material','product','batch_unique_id','batchNo'));
    }

    public function store(Request $request){
    	DB::beginTransaction();
        try {
            $user = Auth::user();
            $id = null;
            if($request->has('id')){
                $id = $request->id;
            }
            $companyId = \Session::get('company');
            $productionId = Production::updateOrCreate(
            [
                "id" => $id
            ],
            [
                "company_id" => $companyId,
                "product_id" => $request->product,
                "quantity" => $request->quantity,
                "weight" => $request->weight,
                "batch_no" => $request->batch_no,
                "batch_unique_id"=>$request->batch_unique_id,
                "certified" => $request->certified,
                "created_by" => $user->id,
            ]);
            if(isset($request['material']) && !empty($request['material']))
            {
            	$count = count($request['material']);
            	$promaterial = ProductionMaterial::where('production_id',$productionId->id)->get();
            	foreach($promaterial as $pm)
            	{
            		$rm = RawMaterial::where('id',$pm->material_id)->first();
            		if(!empty($rm))
            		{
            			$newQty = $pm->quantity + $rm->qty;
            			RawMaterial::where('id',$rm->id)->update(['qty'=>$newQty]);
            		}
            		//RawMaterial::where('id',$pm->material_id)->update(['qty'=>()])
            	}
            	ProductionMaterial::where('production_id',$productionId->id)->delete();
            	for($i=0;$i<$count;$i++)
            	{
            		if(isset($request['material'][$i]) && !empty($request['material'][$i]))
            		{
            			if(is_numeric($request['material'][$i]))
            			{
            				$materialId = $request['material'][$i];
            				ProductionMaterial::insert(['production_id'=>$productionId->id,'material_id'=>$request['material'][$i],'quantity'=>$request['material_quantity'][$i]]);
            			}
            			else
            			{
            				$materialId = RawMaterial::insertGetId(['raw_material_name'=>$request['material'][$i],'qty'=>$request['material_quantity'][$i],'company_id'=>$companyId,'createdBy'=>$user->id]);
            				ProductionMaterial::insert(['production_id'=>$productionId->id,'material_id'=>$materialId,'quantity'=>$request['material_quantity'][$i]]);
            			}

            			$rm = RawMaterial::where('id',$materialId)->first();
	            		if(!empty($rm))
	            		{
							$totalQty = array_sum($request->material_quantity);
							$getFinalTotal = $request->quantity * $request->weight;
							$getFinalTotal = $getFinalTotal / $totalQty;
							$getFinalTotal = number_format((float)$getFinalTotal, 2, '.', '');
							$finalTotalQty = $getFinalTotal * $request['material_quantity'][$i];
	            			$newQty = $rm->qty - $finalTotalQty;
	            			RawMaterial::where('id',$rm->id)->update(['qty'=>$newQty]);
	            		}
            			
            		}
            	}
            }
            DB::commit();
            $message = "saved";
            if($id){
                $message = "updated";
            }
			return redirect()->route('admin.production')->with('success','Production has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){
    	$companyId = \Session::get('company');
    	$product = Product::where('company_id',$companyId)->get();
        $dataArr = Production::where('id',$id)->with(['product'])->first();
        $material = RawMaterial::where('company_id',$companyId)->get();
        $batch_unique_id = $dataArr->batch_unique_id;
	   	$batchNo = $dataArr->batch_no;
        $productionMaterial = ProductionMaterial::join('raw_materials','raw_materials.id','production_material.material_id')->where('production_id',$id)->select('raw_materials.id','raw_materials.raw_material_name','production_material.quantity')->get();

        if($action == 'edit'){
            return view('admin.production.store', compact('dataArr','product','material','productionMaterial','batch_unique_id','batchNo'));
        }else{    
            return view('admin.production.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $Production = Production::find($request->id);
            ProductionMaterial::where('production_id',$request->id)->delete();
            $Production->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Production has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Production catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function fetchProductMaterial(Request $request)
    {
    	$productId = $request->productId;
    	if(!empty($productId))
    	{
    		$productmaterial = ProductMaterial::join('raw_materials','raw_materials.id','product_material.material_id')->where('product_id',$productId)->select('raw_materials.id','raw_materials.raw_material_name','product_material.quantity')->get();

    		$product = Product::where('id',$productId)->first();
    		$material = RawMaterial::where('company_id',\Session::get('company'))->get();
    		if(!$productmaterial->isEmpty())
    		{

    			$html = '<input type="hidden" id="weight" value="'.$product->weight.'" />';
    			foreach($productmaterial as $pm)
    			{
    				$option ='';
    				foreach($material as $mt)
    				{
    					$selected = '';
    					if($pm->id == $mt->id)
    					{
    						$selected = 'selected';
    					}

    					$option .= '<option value="'.$mt->id.'" '.$selected.'>'.$mt->raw_material_name.'</option>';
    				}
    				
    				$html = $html.'<div class="row">
    					<input type="hidden" name="material_id[]" value="'.$pm->id.'" class="material_id"/>
    					  <div class="col-6 form-group">
                             <label>Material<span style="color: red">*</span></label>
                             <select class="form-control rawmaterial js-example-tags" name="material[]" onchnage="setVal(this)">
                                <option value="">Select Rawmaterial</option>
                                '.$option.'
                             </select>
                          </div>
                          <div class="col-5 form-group">
                             <label>Quantity<span style="color: red">*</span></label>
                             <input type="number" name="material_quantity[]" class="form-control quantity" value="'.$pm->quantity.'" onchange="updateQuantity()"/>
                          </div>
                          <div class="col-1 ">
                            <br />
                            <button type="button" class="btn btn-primary" onclick="removeMaterial(this);"><i class="fa fa-minus"></i></button>
                          </div>
                       </div>';
    			}
    		}
    		else
    		{
    			$html = '<div class="row">
    					<input type="hidden" name="material_id[]" value="0" />
    					  <div class="col-6 form-group">
                             <label>Material<span style="color: red">*</span></label>
                             <input type="text" class="form-control material" name="material[]" value="" />
                          </div>
                          <div class="col-5 form-group">
                             <label>Quantity<span style="color: red">*</span></label>
                             <input type="number" name="material_quantity[]" class="form-control quantity" value="" />
                          </div>
                          <div class="col-1 ">
                            <br />
                            <button type="button" class="btn btn-primary" onclick="removeMaterial(this);"><i class="fa fa-minus"></i></button>
                          </div>
                       </div>';
    		}
    		return response()->json(['status'=>1,'html'=>$html,'weight'=>$product->weight,'isoType'=>$product->certified]);
    	}
    	else
    	{
    		return response()->json(['status'=>0,'html'=>'']);
    	}
    }

	public function getUnits(Request $request) {
		$material = RawMaterial::where('id',$request->id)->first();
		if(!empty($material)){
    		return response()->json(['status'=>1,'unit'=>$material->unit]);
		}
		return response()->json(['status'=>0,'unit'=>'No data found']);

	}
}

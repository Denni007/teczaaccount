<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,DB,Auth;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\RawMaterial;
use App\Models\ProductMaterial;
use App\Helpers\Helper;
use App\Models\Production;

class ProductController extends Controller
{
    public function index(){

        return view('admin.product.index');
    }

    public function ajaxData(Request $request) {
    	$permissions = Helper::permissions();
		$keyword = "";
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;
		}
		$companyId = \Session::get('company');
		$Query = Product::select('*')->with(['type'])->where('company_id',$companyId)->orderBy('id', 'desc')->get();
		
		
		// if (!empty($keyword)) {
  //           $Query->where('company_name', 'like', '%' . $keyword . '%');
		// }
			
		$data = datatables()->of($Query)
			->addColumn('product_name', function ($Query) {
				return $Query->product_name;
			})
			->addColumn('product_type', function ($Query) {
				return $Query->type->type;
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

                $btn = '<a href='.route("admin.product.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                 if(empty($permissions))
                 {
                 	$btn = $btn.'<a href='.route("admin.product.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                 }
                 elseif(!empty($permissions) && in_array('product_add',$permissions))
                 {
                 	$btn = $btn.'<a href='.route("admin.product.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                 }
                if(empty($permissions))
                {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 product-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
            	}
            	elseif(!empty($permissions) && in_array('product_delete',$permissions))
                {
                 	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 product-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }

                 return $btn;
            
            })
			->rawColumns(['action'])
			->make(true);

		return $data;
    }

    public function add(){
    	$productType = ProductType::get();
    	$companyId = \Session::get('company');
    	$material = RawMaterial::where('company_id',$companyId)->get();
    	
    	return view('admin.product.store',compact('productType','material'));
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
            $productId = Product::updateOrCreate(
            [
                "id" => $id
            ],
            [
                "company_id" => $companyId,
                "product_name" => $request->product_name,
                "product_type" => $request->product_type,
                "weight" => $request->weight,
                "gst_percentage" => $request->gst_percentage,
                "certified" => $request->certified,
                "created_by" => $user->id,
            ]);
            DB::commit();
            $message = "saved";
            if($id){
                $message = "updated";
            }
			return redirect()->route('admin.product')->with('success','Product has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){
    	$productType = ProductType::get();
        $dataArr = Product::where('id',$id)->with(['type'])->with(['product_material'])->first();
        $companyId = \Session::get('company');
    	$material = RawMaterial::where('company_id',$companyId)->get();
        //dd($dataArr['product_material'][0]['id']);
        if($action == 'edit'){
            return view('admin.product.store', compact('dataArr','productType','material'));
        }else{    
            return view('admin.product.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $company = Product::find($request->id);
            $prodata = Production::where('product_id',$request->id)->first();
            if(!empty($prodata))
            {
            	return array('status' => '0', 'msg_fail' => 'Unable to delete product assign to production');
            }
            ProductMaterial::where('product_id',$request->id)->delete();
            $company->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Product has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Product catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'msg_fail' => 'Something went wrong!');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,DB,Auth;
use App\Models\ProductType;
use App\Models\Product;

class ProductTypeController extends Controller
{
     public function index(){

        return view('admin.product_type.index');
    }

    public function ajaxData(Request $request) {
		$keyword = "";
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;
		}

		$Query = ProductType::select('*')->orderBy('id', 'desc');
		
		// if (!empty($keyword)) {
  //           $Query->where('company_name', 'like', '%' . $keyword . '%');
		// }
			
		$data = datatables()->of($Query)
			->addColumn('type', function ($Query) {
				return $Query->type;
			})
			->addColumn('action',function($Query){
             $actionid = isset($Query->id) ? $Query->id : null;

                $btn = '<a href='.route("admin.product_type.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 product-type-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';

                 return $btn;
            
            })
			->rawColumns(['action'])
			->make(true);

		return $data;
    }

    public function add(){
        return view('admin.product_type.store');
    }

    public function store(Request $request){

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $id = null;
            if($request->has('id')){
                $id = $request->id;
            }

            ProductType::updateOrCreate(
            [
                "id" => $id
            ],
            [
                "type" => $request->type,
            ]);

            DB::commit();
            $message = "saved";
            if($id){
                $message = "updated";
            }
			return redirect()->route('admin.product_type')->with('success','Product Type has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){

        $dataArr = ProductType::where('id',$id)->first();
        
        if($action == 'edit'){
            return view('admin.product_type.store', compact('dataArr'));
        }else{    
            return view('admin.product_type.store', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $producttype = ProductType::find($request->id);
            $prdata = Product::where('product_type',$request->id)->first();
            if(!empty($prdata))
            {
            	return array('status' => '0', 'msg_fail' => 'Unable to delete type assign to product');
            }
            $producttype->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Product Type has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete product type catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'msg_fail' => 'Something went wrong!');
        }
    }
}

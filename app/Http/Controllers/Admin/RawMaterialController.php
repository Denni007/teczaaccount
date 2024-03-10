<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RawMaterial;
use Auth,DB;
use App\Helpers\Helper;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.raw-material.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.raw-material.store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $id = null;

            if($request->has('id')){
                $id = $request->id;
            }
            $companyId = \Session::get('company');
            RawMaterial::updateOrCreate(
            [
                "id" => $id
            ],
            [
                "company_id" => $companyId,
                "raw_material_name" => $request->raw_material_name,
                "qty" => $request->qty,
                "unit" => $request->unit,
                "applicable_gst" => $request->applicable_gst,
                "createdBy" => $user->id,
            ]);

            DB::commit();
            $message = "saved";
            if($id){
                $message = "updated";
            }
			return redirect()->route('raw-material.index')->with('success','Raw Material has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function ajaxData(Request $request)
    {
        $permissions = Helper::permissions();
        $keyword = "";
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;
		}
        $companyId = \Session::get('company');
		$Query = RawMaterial::select('*')->where('company_id',$companyId)->orderBy('id', 'desc');
		
		if (!empty($keyword)) {
            $Query->where('raw_material_name', 'like', '%' . $keyword . '%');
		}
			
		$data = datatables()->of($Query)
			->addColumn('raw_material_name', function ($Query) use($permissions) {
				return $Query->raw_material_name;
			})
			->addColumn('qty', function ($Query) {
				return $Query->qty;
			})
			->addColumn('unit', function ($Query) {
				return $Query->unit;
			})
			->addColumn('applicable_gst', function ($Query) {
				return $Query->applicable_gst;
			})
            ->addColumn('action',function($Query){
             $actionid = isset($Query->id) ? $Query->id : null;

                $btn = '<a href='.route("raw-material.show",[ "raw_material" => $actionid ]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                if(empty($permissions))
                {
                    $btn = $btn.'<a href='.route("raw-material.edit",[ "raw_material" => $actionid ]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }
                else if(!empty($permissions) && in_array('raw_material_add',$permissions))
                {
                    $btn = $btn.'<a href='.route("raw-material.edit",[ "raw_material" => $actionid ]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }
                if(empty($permissions))
                {
                    $btn = $btn.'<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 raw-material-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }
                else if(!empty($permissions) && in_array('raw_material_delete',$permissions))
                {
                    $btn = $btn.'<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 raw-material-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }

                return $btn;
            
            })
			->rawColumns(['action'])
			->make(true);

		return $data;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataArr = RawMaterial::find($id);

        return view('admin.raw-material.view',compact('dataArr'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataArr = RawMaterial::where('id',$id)->first();
        return view('admin.raw-material.store',compact('dataArr'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
         DB::beginTransaction();
        try{
            $raw_material = RawMaterial::find($request->id);
            $raw_material->delete();

            DB::commit();

            return array('status' => '200', 'msg_success' => 'Raw Material has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Raw material catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
        
    }

}

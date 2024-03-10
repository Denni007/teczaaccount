<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,DB,Auth;
use App\Models\Vendor;
use App\Models\BankAccountDetails;
use App\Helpers\Helper;
use App\Models\Country;
use App\Models\State;

class VendorController extends Controller
{
    public function index(){

        return view('admin.vendor.index');
    }

    public function ajaxData(Request $request) {
        $permissions = Helper::permissions();
		$keyword = "";
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;
		}
        $companyId = \Session::get('company');
		$Query = Vendor::select('*')->where('company_id',$companyId)->orderBy('id', 'desc');
		
		if (!empty($keyword)) {
            $Query->where('vendor_name', 'like', '%' . $keyword . '%');
		}
			
		$data = datatables()->of($Query)
			->addColumn('vendor_name', function ($Query) {
				return $Query->vendor_name;
			})
			->addColumn('contact_no', function ($Query) {
				return $Query->contact_no;
			})
			->addColumn('email_id', function ($Query) {
				return $Query->email_id;
			})
			// ->addColumn('mobile_no', function ($Query) {
			// 	return $Query->mobile_no;
			// })
			->addColumn('contact_person_name', function ($Query) {
				return $Query->contact_person_name;
			})
			->addColumn('address', function ($Query) {
				return $Query->address;
			})
			->addColumn('website', function ($Query) {
				$website = isset($Query->website) ? $Query->website : null;
                $link = "-";
                if(isset($website)){
                    $link = "<a href='{{$website}}' target='_blank'>$website</a>";
                }

                return $link;
			})->addColumn('action',function($Query) use($permissions){
             $actionid = isset($Query->id) ? $Query->id : null;

                $btn = '<a href='.route("admin.vendor.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                if(empty($permissions))
                {
                    $btn = $btn.'<a href='.route("admin.vendor.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }
                else if(!empty($permissions) && in_array('vendor_add',$permissions))
                {
                    $btn = $btn.'<a href='.route("admin.vendor.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }
                if(empty($permissions))
                {
                    $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 vendor-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                                }
                else if(!empty($permissions) && in_array('vendor_delete',$permissions))
                {
                    $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 vendor-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }

                 return $btn;
            
            })
			->rawColumns(['website','action'])
			->make(true);

		return $data;
    }

    public function add(){
        $country = Country::get();
        return view('admin.vendor.store',compact('country'));
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
            Vendor::updateOrCreate(
            [
                "id" => $id
            ],
            [
                "company_id" => $companyId,
                "vendor_name" => $request->vendor_name,
                "contact_no" => $request->contact_no,
                "email_id" => $request->email_id,
                "mobile_no" => $request->mobile_no,
                "contact_person_name" => $request->contact_person_name,
                "gst_no" => $request->gst_no,
                "address" => $request->address,
                "country_id" => $request->country,
                "state_id" => $request->state,
                "pincode" => $request->pincode,
                "cin" => $request->cin,
                "website" => $request->website,
                "currency" => $request->currency,
                "pan_no" => $request->pan_no,
                "created_by" => $user->id,
            ]);

            DB::commit();
            $message = "saved";
            if($id){
                $message = "updated";
            }
			return redirect()->route('admin.vendor')->with('success','Vendor has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){
        $country = Country::get();
        $dataArr = Vendor::where('id',$id)->with(['bank_account','state','country'])->first();
        if($action == 'edit'){
            return view('admin.vendor.store', compact('dataArr','country'));
        }else{    
            return view('admin.vendor.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $company = Vendor::find($request->id);
            $company->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Vendor has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Vendor catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function storeBankDetails(Request $request)
    {
        DB::beginTransaction();
        try{

            BankAccountDetails::updateOrCreate(
            [
                "id" => $request->id
            ],
            [
                'vendor_id' => $request->vendor_id,
                'bank_name' => $request->bank_name,
                'ifsc_code' => $request->ifsc_code,
                'swift_code' => $request->swift_code,
                'beneficary_name' => $request->beneficary_name,
                'account_no' => $request->account_no,
                'account_type' => $request->account_type,
                'branch_name' => $request->branch_name,
                'balance' => $request->balance,
                'created_by' => Auth::id()
            ]);
            
            DB::commit();
            $message = "saved";
            if($request->id){
                $message = "updated";
            }
            //dd($request->all());
            return redirect()->route('admin.vendor.view',['action'=>'view','id'=>$request->vendor_id])->with('success','Bank details has been '.$message.' successfully');
            //return redirect()->back()->with('success','Bank account details saved successfully');

        } catch(Exception $e){
            \Log::info('Store bank details catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return back()->with('error', 'Sorry something went worng. Please try again.');
        }
    }
    public function addBankDetails($vendorId,$id=null,Request $request)
    {
        //dd('here');
        $bankDetails = null;
        if($id)
        {
            $bankDetails = BankAccountDetails::where('vendor_id',$vendorId)->first();
        }
        
        return view('admin.vendor.bank_store', compact('bankDetails','vendorId'));
    }

    public function deleteBankDetails($vendorId,$id,Request $request)
    {
        BankAccountDetails::where('vendor_id',$vendorId)->where('id',$id)->delete();
        return redirect()->route('admin.vendor.view',['action'=>'view','id'=>$vendorId])->with('success','Bank details has been deleted successfully');
    }

    public function fetchState(Request $request)
    {
        $html = '<option value="">Select State</option>';
        if(isset($request['country']) && !empty($request['country']))
        {
            $state = State::where('country_id',$request['country'])->get();
            if(!$state->isEmpty())
            {
                foreach($state as $st)
                {
                    $html = $html.'<option value="'.$st->id.'">'.$st->name.'</option>';
                }
            }
        }
        return response()->json(['status'=>1,'html'=>$html]);
    }
}

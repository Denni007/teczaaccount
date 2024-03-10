<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,DB,Auth;
use App\Models\Company;
use App\Models\BankAccountDetails;
use App\Models\Country;

class CompanyComtroller extends Controller
{
    public function index(){

        return view('admin.company.index');
    }

    public function ajaxData(Request $request) {
		$keyword = "";
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;
		}
        if(Auth::user()->type == 1) {
            $Query = Company::select('*')->orderBy('id', 'desc');
        } else {
            $Query = Company::select('*')->where('user_id',Auth::user()->id)->orderBy('id', 'desc');
        }
		
		if (!empty($keyword)) {
            $Query->where('company_name', 'like', '%' . $keyword . '%');
		}
			
		$data = datatables()->of($Query)
			->addColumn('company_name', function ($Query) {
				return $Query->company_name;
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
			})->addColumn('action',function($Query){
             $actionid = isset($Query->id) ? $Query->id : null;

                $btn = '<a href='.route("admin.company.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                $btn = $btn.'<a href='.route("admin.company.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 company-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';

                 return $btn;
            
            })
			->rawColumns(['website','action'])
			->make(true);

		return $data;
    }

    public function add(){
        $country = Country::get();
        return view('admin.company.store',compact('country'));
    }

    public function store(Request $request){

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $id = null;
            if($request->has('id')){
                $id = $request->id;
            }

            Company::updateOrCreate(
            [
                "id" => $id
            ],
            [
                "company_name" => $request->company_name,
                "user_id" => $user->id,
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
                "sufix"  => $request->sufix,
                "wallet_balance"  => $request->wallet_balance,
                "created_by" => $user->id,
            ]);

            DB::commit();
            $message = "saved";
            if($id){
                $message = "updated";
            }
			return redirect()->route('admin.company')->with('success','Company has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){
        $country = Country::get();
        $dataArr = Company::where('id',$id)->with(['bank_account','state','country'])->first();
        if($action == 'edit'){
            return view('admin.company.store', compact('dataArr','country'));
        }else{    
            return view('admin.company.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $company = Company::find($request->id);
            $company->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Company has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete company catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
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
                'company_id' => $request->company_id,
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
            return redirect()->route('admin.company.view',['action'=>'view','id'=>$request->company_id])->with('success','Bank details has been '.$message.' successfully');
            //return redirect()->back()->with('success','Bank account details saved successfully');

        } catch(Exception $e){
            \Log::info('Store bank details catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return back()->with('error', 'Sorry something went worng. Please try again.');
        }
    }

    public function addBankDetails($companyId,$id=null,Request $request)
    {
        //dd('here');
        $bankDetails = null;
        if($id)
        {
            $bankDetails = BankAccountDetails::where('company_id',$companyId)->first();
        }
        
        return view('admin.company.bank_store', compact('bankDetails','companyId'));
    }

    public function deleteBankDetails($companyId,$id,Request $request)
    {
        BankAccountDetails::where('company_id',$companyId)->where('id',$id)->delete();
        return redirect()->route('admin.company.view',['action'=>'view','id'=>$companyId])->with('success','Bank details has been deleted successfully');
    }
}

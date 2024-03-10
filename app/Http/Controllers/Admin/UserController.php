<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,DB,Auth,Hash;
use App\User;
use App\Models\Permissions;

class UserController extends Controller
{
    public function index(){

        return view('admin.user.index');
    }

    public function ajaxData(Request $request) {
		$keyword = "";
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;
		}
		$companyId = \Session::get('company');
		$Query = User::select('*')->where('company_id',$companyId)->where('type',2)->orderBy('id', 'desc');
		
		if (!empty($keyword)) {
            $Query->where('name', 'like', '%' . $keyword . '%');
		}
			
		$data = datatables()->of($Query)
			->addColumn('name', function ($Query) {
				return $Query->name;
			})
			->addColumn('mobile', function ($Query) {
				return $Query->phone;
			})
			->addColumn('email', function ($Query) {
				return $Query->email;
			})
			// ->addColumn('mobile_no', function ($Query) {
			// 	return $Query->mobile_no;
			// })
			->addColumn('user_type', function ($Query) {
				switch ($Query->user_type) {
					case 1:
						return 'Cash';
						break;
					case 2:
						return 'Cash & Bill';
						break;
					default:
						return 'Cash';
						break;
				}
			})
			->addColumn('designation', function ($Query) {
				return $Query->designation;
			})
			->addColumn('shift_type', function ($Query) {
				switch ($Query->shift_type) {
					case 1:
						return 'Day';
						break;
					case 2:
						return 'Night';
						break;
					case 3:
						return 'Full time';
						break;
					case 4:
						return 'Daily Worker';
						break;
					default:
						return '-';
						break;
				}
			})->addColumn('action',function($Query){
             $actionid = isset($Query->id) ? $Query->id : null;

                $btn = '<a href='.route("admin.user.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                $btn = $btn.'<a href='.route("admin.user.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 user-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';

                 return $btn;
            
            })
			->rawColumns(['action'])
			->make(true);

		return $data;
    }

    public function add(){
        return view('admin.user.store');
    }

    public function store(Request $request){
    	//dd($request->all());
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $companyId = \Session::get('company');
            //dd($user->id);
            $id = null;
            if($request->has('id')){
                $id = $request->id;
            }
            if(empty($id))
            {
            	if(isset($request['email']) && !empty($request['email']))
            	{
            		$userdata = User::where('email',$request->email)->first();
            		if(!empty($userdata))
            		{
            			return back()->with('error', 'User with email already exist.');
            		}
            	}
            	else
            	{
            		 return back()->with('error', 'Email is required.');
            	}
            }
            $insData = User::updateOrCreate(
            [
                "id" => $id
            ],
            [
                "name" => $request->name,
                "company_id" => $companyId,
                "email" => $request->email,
                "phone" => $request->phone,
                "address" => $request->address,
                "user_type" => $request->user_type,
                "designation" => $request->designation,
                "shift_type" => $request->shift_type,
                "bank_name" => $request->bank_name,
                "ifsc_code" => $request->ifsc_code,
                "swift_code" => $request->swift_code,
                "beneficary_name" => $request->beneficary_name,
                "account_no" => $request->account_no,
                "account_type" => $request->account_type,
                "branch_name" => $request->branch_name,
                "is_active" => 1,
                "type" => 2,
                "password" => Hash::make($request->text_pass),
                "text_pass" => $request->text_pass,
                "created_by" => $user->id,
            ]);
            if(isset($request['permission']) && !empty($request['permission']))
            {
            	Permissions::where('user_id',$insData->id)->delete();
            	foreach ($request['permission'] as $key => $value) {
            		Permissions::insert(['user_id'=>$insData->id,'permission'=>$key,'status'=>1]);
            	}
            }
            DB::commit();
            $message = "saved";
            if($id){
                $message = "updated";
            }
			return redirect()->route('admin.user')->with('success','User has been '.$message.' successfully');

		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){

        $dataArr = User::where('id',$id)->with(['permission'])->first();
        if($action == 'edit'){
        	$permission = [];
        	if(!empty($dataArr))
        	{
        		if(isset($dataArr['permission']))
        		{
        			foreach($dataArr['permission'] as $pr)
        			{
        				$permission[] = $pr->permission;
        			}
        		}
        	}
        	return view('admin.user.store', compact('dataArr','permission'));
        }else{    
            return view('admin.user.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $user = User::find($request->id);
            Permissions::where('user_id',$user->id)->delete();
            $user->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'User has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete User catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }
}

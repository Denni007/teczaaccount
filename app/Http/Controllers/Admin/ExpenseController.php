<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Company;
use App\Models\Expense;
use Validator,DB,Auth,Hash;
use App\Helpers\Helper;

class ExpenseController extends Controller
{
    public function index(){
		return view('admin.expense.index');
    }

    public function ajaxData(Request $request) {
    	$permissions = Helper::permissions();
		$keyword = "";
		if (!empty($request->keyword)) {
			$keyword = $request->keyword;
		}
		$companyId = \Session::get('company');
		$Query = Expense::select('expense.*')->join('users', 'users.id', '=', 'expense.user_id')->where('users.user_type', auth()->user()->user_type)->where('expense.company_id',$companyId)->orderBy('expense.id', 'desc');
			
		$data = datatables()->of($Query)
			->addColumn('expense_name', function ($Query) {
				return $Query->expense_name;
			})
			->addColumn('amount', function ($Query) {
				return $Query->amount;
			})
			->addColumn('status',function($Query) use($permissions){
			$actionid = isset($Query->id) ? $Query->id : null;
             $status = isset($Query->status) ? $Query->status : 0;
             	$btn = '';
             	if(empty($permissions))
                {
                	if($status == 0)
	             	{
	             		$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 expense-approve" title="Approve" data-id='.$actionid.'>
	                 <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 expense-reject" title="Reject" data-id='.$actionid.'>
	                 <i class="far fa-times-circle"></i></a>';
	             	}
	             	else
	             	{
	             		if($status == 1)
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 expense-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
	             		}
	             		else
	             		{
	             			$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 expense-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
	             		}
	             		
	             	}
                }
                elseif(!empty($permissions) && in_array('expense_approve',$permissions))
                {
                	if($status == 0)
		         	{
		         		$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 expense-approve" title="Approve" data-id='.$actionid.'>
		             <i class="far fa-check-circle"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 expense-reject" title="Reject" data-id='.$actionid.'>
		             <i class="far fa-times-circle"></i></a>';
		         	}
		         	else
		         	{
		         		if($status == 1)
		         		{
		         			$state = '<a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 expense-reject" title="Reject" data-id='.$actionid.'><i class="far fa-times-circle"></i></a>';
		         		}
		         		else
		         		{
		         			$state = '<a href="javascript:void(0)" class="btn btn-success btn-sm mx-1 expense-approve" title="Approve" data-id='.$actionid.'><i class="far fa-check-circle"></i></a>';
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
                $btn =$btn. '<a href='.route("admin.expense.view",['action'=>"view","id"=>$actionid]).' class="btn btn-success btn-sm mx-1" >
                 <i class="far fa-eye"></i></a>';
                if(empty($permissions))
                {
                	$btn = $btn.'<a href='.route("admin.expense.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }
                elseif(!empty($permissions) && in_array('expense_add',$permissions))
                {
                	$btn = $btn.'<a href='.route("admin.expense.view",['action'=>"edit","id"=>$actionid]).' class="edit btn btn-primary  mx-1 btn-sm"><i class="fa fa-edit"></i></a>';
                }

                if(empty($permissions))
                {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 expense-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }
                elseif(!empty($permissions) && in_array('expense_delete',$permissions))
                {
                	$btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm mx-1 expense-delete" data-id='.$actionid.'><i class="fas fa-trash"></i></a>';
                }

                
                

                 return $btn;
            
            })
			->rawColumns(['status','action'])
			->make(true);

		return $data;
    }

    public function add()
    {
    	return view('admin.expense.store');
    }

    public function store(Request $request){

        DB::beginTransaction();
        try {
        	//dd($request->all());
            $user = Auth::user();
            		$company_id = \Session::get('company');
            		$id = null;
		            if($request->has('id')){
		                $id = $request->id;
		            }
            		$expense = Expense::updateOrCreate(
		            [
		                "id" => $id
		            ],
		            [
		                "company_id" => $company_id,
		                "user_id" => $user->id,
		                "expense_name" => $request->expense_name,
		                "amount" => $request->amount,
		                "status"=>0,
		            ]);
		            DB::commit();
		            $message = "created";
		            if($id){
		                $message = "updated";
		            }
					return redirect()->route('admin.expense')->with('success','Expense has been '.$message.' successfully');
            
		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
    }

    public function view($action = null , $id = null){
        $dataArr = Expense::where('id',$id)->first();
        //dd($dataArr);
        if($action == 'edit'){
            return view('admin.expense.store', compact('dataArr'));
        }else{    
            return view('admin.expense.view', compact('dataArr'));
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $expense = Expense::find($request->id);
            $expense->delete();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Expense has been deleted successfully.');

        } catch(Exception $e){
            \Log::info('Delete Expense catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();
        try{
            $expense = Expense::find($request->id);
            if(!empty($expense))
            {
            	$payamount = $expense->amount;
    			$walletbalnce = Helper::walletBalance(\Session::get('company'));
    			$updatedWalletBalnce = $walletbalnce - $payamount;
    			Company::where('id',\Session::get('company'))->update(['wallet_balance'=>$updatedWalletBalnce]);
            		
            	
            }
            $expense->status = 1;
            $expense->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Expense has been approved successfully.');

        } catch(Exception $e){
            \Log::info('Approve expense catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }

    public function reject(Request $request)
    {
        DB::beginTransaction();
        try{
           	$expense = Expense::find($request->id);
            $expense->status = 2;
            $expense->save();
            DB::commit();
            return array('status' => '200', 'msg_success' => 'Expense has been rejected successfully.');

        } catch(Exception $e){
            \Log::info('Reject expense catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return array('status' => '0', 'error' => 'Something went wrong!');
        }
    }
}

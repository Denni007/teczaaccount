<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Invoice;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Validator;

class AdminController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public $user;

	public function __construct() {
		$this->user = Auth::user();
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		if (Auth::check()){
			if($this->user->type == 1 || $this->user->type == 2){
				return redirect()->route('admin.index');
			}
		}
		return redirect()->route('index');
	}

	public function dashboard() {		
		$financial_years = [];
		$current_year = date('Y');
		if((int) date('m') >= 4) {
			for ($year=$current_year; $year > $current_year-5; $year--) {
				array_push($financial_years, $year . '-' . substr( $year+1, -2));
			}
		} else {
			for ($year=$current_year-1; $year > $current_year-6; $year--) {
				array_push($financial_years, $year . '-' . substr( $year+1, -2));
			}
		}
		
		$data = DB::select(DB::raw("SELECT year(`bill_date`) as Year ,month(`bill_date`) as Month,sum(`total_amount`) as TotalSales, sum(`amount`) as BasicAmount, sum(`gst`) as Gst from invoice group by year(`bill_date`), month(`bill_date`) order by year(`bill_date`) ASC"));
		//dd($data);
		/* Query for year and month wise sales
			SELECT year(`bill_date`) as Year ,month(`bill_date`) as Month,sum(`total_amount`) as TotalSales from invoice group by year(`bill_date`),month(`bill_date`) order by year(`bill_date`),month (`bill_date`)
		*/
				
		/* Below is a valid code for array from here like ['','','']......*/  
		/*
		$chartData = "";
		foreach ($data as $key => $value) {
			$chartData.="[".$value->Month.",".$value->TotalSales.",".$value->Year."],";
		}
		$chartData=rtrim($chartData,",");
			//echo $chartData;
			//exit;	
		 */
		return view('admin.dashboard', compact('financial_years','data'));
	}

	public function filter(Request $request) {
		$companyId = session()->get('company');
		$user = FacadesAuth::user();

		$duration = explode(' - ', $request->duration);
		$startDuration = \Carbon\Carbon::parse(str_replace("/", "-", $duration[0]))->format('Y-m-d');
		$endDuration = \Carbon\Carbon::parse(str_replace("/", "-", $duration[1]))->format('Y-m-d');

		// get total incomes
		$incomes = '';
		if($user->type == 2)
		{
			if($user->user_type == 1)
			{
				$incomes = Invoice::select('*')->where('company_id',$companyId)->where('bill_type',1)->whereBetween('created_at',[$startDuration, $endDuration])->with(['user','vendor'])->orderBy('id', 'desc')->get();
			}
			else
			{
				$incomes = Invoice::select('*')->where('company_id',$companyId)->whereBetween('created_at',[$startDuration, $endDuration])->with(['user','vendor'])->orderBy('id', 'desc')->get();
			}
		}
		else
		{
			$incomes = Invoice::select('*')->where('company_id',$companyId)->whereBetween('created_at',[$startDuration, $endDuration])->with(['user','vendor'])->orderBy('id', 'desc')->get();
		}
		$total_income = 0;
		if(!empty($incomes)) {
			foreach ($incomes as $key => $income) {
				$total_income += $income->total_amount;
			}
		}

		// get total expenses
		$expenses = Expense::select('*')->where('company_id',$companyId)->whereBetween('created_at',[$startDuration, $endDuration])->orderBy('id', 'desc')->get();
		$total_expense = 0;
		if(!empty($expenses)) {
			foreach ($expenses as $key => $expense) {
				$total_expense += $expense->amount;
			}
		}

		$response = [
			'total_income' => $total_income,
			'total_expense' => $total_expense
		];
		return json_encode($response);
	}

	public function changePassword() {
		return view('admin.change_password');
	}

	public function updatePassword(Request $request) {
		try {
			$validator = Validator::make($request->all(), [
				'current_password' => 'required',
				'password' => 'required|min:8',
				'password_confirmation' => 'required|same:password',
			], [
				'current_password.required' => 'Please enter current password',
				'password.required' => 'Please enter new password',
				'password.min' => 'At least :min characters required',
				'password_confirmation.required' => 'Please enter confirm password',
				'password_confirmation.same' => 'Password and Repeat Password does not match',
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$user = User::find(Auth::user()->id);
			if (!Hash::check($request->current_password, $user->password)) {
				return back()->withErrors(['current_password' => 'The specified password does not match the current password']);
			} else {
				$user->password = Hash::make($request->password);
				$user->save();
                return back()->with('success', 'Password has been changed successfully');
			}
		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
	}
	public function fillProfile(){
		$user_id = Auth::user()->id;
		$admin = User::select('email','name','logo')->where('id',$user_id)->first();

		return view('admin.admin-fill-profile',compact('admin'));
	}
	public function updateProfile(Request $request){
		try {
			$profilePicOrgDynamicUrl = str_replace('{userSlug}', Auth::user()->slug, config('constant.profile_url'));
			$profilePicThumbDynamicUrl = str_replace('{userSlug}', Auth::user()->slug, config('constant.profile_thumb_url'));


			// echo "<pre>";print_r($request->all());exit;
			$validator = Validator::make($request->all(), [
					'name' => 'required',
				]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			 if ($request->file('profile_photo') != "") {
                $file = $request->file('profile_photo');
                $logo_name = "profile_".time().'.'.$file->getClientOriginalExtension();
				Helper::uploadDynamicFile($profilePicOrgDynamicUrl, $logo_name, $file);
				
                if (isset($request->old_logo) && $request->old_logo != "" && $request->old_logo != '') {
                    Helper::checkFileExists($profilePicOrgDynamicUrl . $request->old_logo, true, true);
                }
            } else {
                $logo_name = $request->old_logo;
			}
			// echo "<pre>";print_r($logo_name);exit;
			
			$user_id = Auth::user()->id;
			$admin = User::updateOrCreate(['id' => $user_id],['logo' => $logo_name, 'name' => $request->name]);
			// dd($admin);
			return redirect()->route('admin.index')->with('success', 'Profile has been updated successfully');
		} catch (Exception $e) {
			return redirect()->route('admin.index')->with('error', 'Sorry something went worng. Please try again.');
		}
	}

	public function removeUser(Request $request){
		try{
			if($request->id){
				DB::beginTransaction();
				$user = User::where('id',$request->id)->first();
				if($user->is_active != 2){
					$user->delete();
					$user->is_active = 2;
					$user->save();
					DB::commit();
					return array('status' => '200', 'msg_success' => 'This Account has been closed successfully');
				}
			}else{
				DB::rollback();
				return array('status' => '0', 'msg_fail' => 'Something went wrong');
			}
		} catch(Exception $e){
			DB::rollback();
			return array('status' => '0', 'msg_fail' => 'Something went wrong');
		}
	}
}

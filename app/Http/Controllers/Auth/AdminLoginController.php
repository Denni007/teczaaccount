<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\Company;

class AdminLoginController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Login Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller handles authenticating users for the application and
		    | redirecting them to your home screen. The controller uses a trait
		    | to conveniently provide its functionality to your applications.
		    |
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/admin';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest')->except('logout');
	}

	public function index(){
        return view('admin.auth.login');
    }

	public function authenticated(Request $request, $user) {
		$companyList = Company::where('deleted_at',null)->select('id','company_name')->get();
		if(!$companyList->isEmpty())
		{
			if($user->type == 1)
			{
				\Session::put('company', $companyList[0]->id);
			}
			elseif ($user->type == 2) {
				\Session::put('company', $user->company_id);
			}
			
		}
		else
		{
			if($user->type == 1)
			{
				\Session::put('company', 0);
			}
			elseif ($user->type == 2) {
				\Session::put('company', $user->company_id);
			}
		}
		if ($user->type == 1 || $user->type == 2) {
			if ($user->type == 2 && $user->is_active != 1) {
                Auth::logout();
				return redirect()->back()->with('error', 'Your account is deactivated, Please contact admin to activate your account')->withInput();
			}
		}else {
			Auth::logout();
			return redirect('/')->with('error','You do not have sufficient permissions to access this page.');
		}
	}
	public function logout(Request $request){
		$this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect()->route('admin.login');
    }
}

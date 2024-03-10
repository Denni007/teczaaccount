<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getCompany(Request $request)
    {
        try {
            $company = $request->input('company');
            $flag = 0;
            // app()->setLocale();
            //app()->setLocale($locale);
            //$latestLocale = app()->getLocale();

            \Session::put('company', $company);

            //if(app()->isLocale($locale))
                $flag = 1;
            return $flag;
        } catch (Exception $e) {
            return 0;
        }
    }
}

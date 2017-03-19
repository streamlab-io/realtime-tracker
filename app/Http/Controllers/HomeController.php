<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('home' ,compact('user'));
    }
    public function geo($id = null)
    {
        if($id != null){
            $user = User::find($id);
        }else{
            $user = Auth::user();
        }
        return view('geo.index' ,compact('user' , 'id'));
    }
}

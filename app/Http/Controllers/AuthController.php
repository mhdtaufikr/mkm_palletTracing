<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function postLogin(Request $request){
        //dd($request->all());
        $email=$request->email;
        $password=$request->password;
        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        //dd($credentials);
        $cekuser_status=User::where('email',$email)->first();
        // dd($cekuser_status);
        $dologin=Auth::attempt($credentials);
        
        //dd($dologin);
        if($dologin){
            // dd('hai');
            if($cekuser_status->is_active=='1'){

                //update last login
                $update_lastlogin=User:: where('email',$email)
                ->update([
                    'last_login' => now(),
                    'login_counter' => $cekuser_status->login_counter+1,
                ]);

                if($update_lastlogin){
                    //Audit Log
                    // $username= auth()->user()->email; 
                    // $ipAddress=$_SERVER['REMOTE_ADDR'];
                    // $location='0';
                    // $access_from=Browser::browserName();
                    // $activity='Login';

                    // //dd($location);
                    // $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    return redirect('/home');
                }
            }
            else{
                return redirect('/')->with('statusLogin','Give Access First to User');
            }
        }
        else{
            //dd('hai');
            return redirect('/')->with('statusLogin','Wrong Email or Password');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('statusLogout','Success Logout');
    }
}

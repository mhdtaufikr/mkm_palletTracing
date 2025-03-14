<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dropdown;
use App\Mail\AccessRequestMail;
use Illuminate\Support\Facades\Mail;
/* use DB;
use App\Mail\TaxReturnNotification; */


class AuthController extends Controller
{
    public function login(){
        $dropdown = Dropdown::where('category','Role')
        ->get();
        return view('auth.login',compact('dropdown'));
    }

    public function postLogin(Request $request){
        $emailOrName = $request->input('email');
        $password = $request->input('password');

        $isEmail = filter_var($emailOrName, FILTER_VALIDATE_EMAIL);
        $credentials = $isEmail ? ['email' => $emailOrName] : ['name' => $emailOrName];
        $credentials['password'] = $password;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->is_active == '1') {
                // Update last login
                User::where('email', $user->email)->update([
                    'last_login' => now(),
                    'login_counter' => $user->login_counter + 1,
                ]);

                // Redirect to the intended route after login
                return redirect()->intended('/home');
            } else {
                return redirect('/')->with('statusLogin', 'Give Access First to User');
            }
        } else {
            return redirect('/')->with('statusLogin', 'Wrong Email/Name or Password');
        }
    }



    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('statusLogout','Success Logout');
    }

    public function requestAccess(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
        ]);

        // Send the email
        Mail::to(['aditia@ptmkm.co.id','muhammad.taufik@ptmkm.co.id','aji.bayu@ptmkm.co.id'])
            ->cc('bayu@ptmkm.co.id')
            ->send(new AccessRequestMail($request->all()));

        // Optionally, you can flash a success message or redirect to a specific page
        return back()->with('statusLogin', 'Your request has been submitted.');
    }

   /*  public function mail() {
        set_time_limit(300); // sets the time limit to 300 seconds

        // Fetch up to 100 employees from the database where email is not null and flag is null
        $employees = DB::table('employees')
                        ->whereNotNull('email')
                        ->whereNull('flag')  // Selects only employees whose flag is null
                        ->limit(100)
                        ->get();

        // Base path where the files are stored
        $basePath = 'C:\\xampp\\htdocs\\mkm_palletTracing\\public\\pdf\\';

        // Use a variable to collect response messages
        $response = [];

        foreach ($employees as $employee) {
            $filePath = $basePath . $employee->nama_file;

            if (file_exists($filePath)) {
                try {
                    // Send email
                    Mail::to($employee->email)
                        ->send(new TaxReturnNotification((object) [
                            'nama_file' => $employee->nama_file,
                            'file_path' => $filePath
                        ]));

                    // Update the flag to '1' if email sent
                    DB::table('employees')
                        ->where('id', $employee->id)
                        ->update(['flag' => '1']);

                    // Add successful message for this employee
                    $response[] = "Email successfully sent to: " . $employee->email;
                } catch (\Exception $e) {
                    // Add error message for this employee
                    $response[] = "Failed to send email to: " . $employee->email . " Error: " . $e->getMessage();
                }
            } else {
                // File does not exist
                $response[] = "File does not exist for: " . $employee->email;
            }
        }

        // Return JSON response with all messages
        return response()->json(['messages' => $response], 200);
    } */






}



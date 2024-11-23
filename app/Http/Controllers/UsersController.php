<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use App\Models\users;
use App\Models\roles;
use App\Models\user_roles;
use App\Models\role_access;
use App\Models\user_mac_address;
use App\Traits\SaveImage;

class UsersController extends Controller
{
    //
    use SaveImage;

    public function index()
    {
        $users = users::join('user_roles', 'user_roles.user_id', '=', 'users.id')
        ->leftjoin('roles', 'roles.id', '=', 'user_roles.role_id')
        ->select('users.*', 'roles.name as role_name')
        ->get();
        $roles = roles::get();

        return view('users.users',compact('roles','users'));
    }

    public function createValidation(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'username' => 'required|string|max:255|unique:users',
            'cnic_no' => 'required|string|max:255|unique:users',
            'phone_no' => 'nullable|string|max:255|unique:users',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // Unprocessable Entity status code
        }
    }
    
    public function loginScreen()
    {
        if (Auth::check()) {
            return view('home'); // Show the home view if authenticated
        }
        // $user_mac = $this->getMacAddress();
        return view('login')->with([
            'mac_add' => '',
        ]);
    }

    public function createUser(Request $request)
    {
        $att_path = '';
        $cnic_front_path = '';
        $cnic_back_path = '';

        if ($request->hasFile('att') && $request->file('att') && !empty($request->file('att'))) {
            $file = $request->file('att');
            $extension = $file->getClientOriginalExtension();
            $att_path = $this->UserProfile($file, $extension);
        }

        if ($request->hasFile('cnic_front') && $request->file('cnic_front')) {
            $file = $request->file('cnic_front');
            $extension = $file->getClientOriginalExtension();
            $cnic_front_path = $this->UserProfile($file, $extension);
        }

        if ($request->hasFile('cnic_back') && $request->file('cnic_back')) {
            $file = $request->file('cnic_back');
            $extension = $file->getClientOriginalExtension();
            $cnic_back_path = $this->UserProfile($file, $extension);
        }
        
        try {
            $user = users::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'username' => $request->username,
                'cnic_no' => $request->cnic_no,
                'phone_no' => $request->phone_no,
                'address' => $request->address, 
                'date' => $request->date,      
                'picture' => $att_path,
                'cnic_front' => $cnic_front_path,
                'cnic_back' => $cnic_back_path,
                'created_by' => session('user_id')
            ]);

            $user_id=users::latest()->select('id')->first()->id;

            $user_role= user_roles::create([
                'user_id' => $user_id,
                'role_id' => $request->role_id,
                'created_by ' => session('user_id'),
            ]);

            return redirect()->route('all-users')->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 422); // Unprocessable Entity status code
            
            // return back()->withErrors(['msg' => 'Failed to create user: ' . $e->getMessage()]);
        }
    }

    public function updateUser(Request $request)
    {
        $user = users::where('id', $request->update_user_id)->first();

        if ($request->hasFile('update_att') && $request->file('update_att')) {
            $filePath = public_path($user->picture);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $file = $request->file('update_att');
            $extension = $file->getClientOriginalExtension();
            $user->picture = $this->UserProfile($file, $extension);
        }

        if ($request->hasFile('update_cnic_front') && $request->file('update_cnic_front')) {

            $filePath = public_path($user->cnic_front);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $file = $request->file('update_cnic_front');
            $extension = $file->getClientOriginalExtension();
            $user->cnic_front = $this->UserProfile($file, $extension);
        }

        if ($request->hasFile('update_cnic_back') && $request->file('update_cnic_back')) {

            $filePath = public_path($user->cnic_front);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $file = $request->file('update_cnic_back');
            $extension = $file->getClientOriginalExtension();
            $user->cnic_back = $this->UserProfile($file, $extension);
        }

        if ($request->has('update_date') && $request->update_date) {
            $user->date=$request->update_date;
        }
        if ($request->has('update_emp_name') && $request->update_emp_name) {
            $user->name=$request->update_emp_name;
        }
        if ($request->has('update_phone_no') && $request->update_phone_no) {
            $user->phone_no=$request->update_phone_no;
        }
        if ($request->has('update_email_add') && $request->update_email_add) {
            $user->email=$request->update_email_add;
        }
        if ($request->has('update_cnic_no') && $request->update_cnic_no ) {
            $user->cnic_no=$request->update_cnic_no;
        }
        if ($request->has('update_add') && $request->update_add) {
            $user->address=$request->update_add;
        }

        users::where('id', $request->update_user_id)->update([
            'date'=>$user->date,
            'address'=>$user->address,
            'cnic_no'=>$user->cnic_no,
            'email'=>$user->email,
            'phone_no'=>$user->phone_no,
            'name'=>$user->name,
            'picture'=>$user->picture,
            'cnic_front'=>$user->cnic_front,
            'cnic_back'=>$user->cnic_back,
            'updated_by' => session('user_id'),
        ]);

        user_roles::where('user_id', $request->update_user_id)->update([
            'role_id' => $request->update_role,
            'updated_by' => session('user_id'),
        ]);

        return redirect()->route('all-users')->with('success', 'User updated successfully.');
    }

    public function login(Request $request)
    {
        // if (session()->has('expired') || !session()->has('user')) {
        //     return redirect()->route('login')->withErrors(['Your session has expired. Please log in again.']);
        // }

        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'status' => 1])) {
            // Authentication passed
            $user = Auth::user();

            // $user_mac =  $this->getMacAddress();

            // $allowed_macs = $user_mac_address::where('user_id',$user['id'])-get('mac_address');

            // if ($allowed_macs->contains($user_mac)) {

                $request->session()->regenerate();

                users::where('id', $user['id'])->update([
                    'is_login'=>1,
                ]);

                $user_roles = user_roles::where('user_id',$user['id'])
                ->join('roles','roles.id','=','user_roles.role_id')
                ->select('user_roles.*','roles.name as role_name')
                ->first();
    
                $user_permission = role_access::where('role_id',$user_roles['role_id'])
                ->select('module_id','view')
                ->get();
                
                $user_access = $user_permission->toArray();
        
                session([
                    'user_id' => $user['id'],
                    'user_name' => $user['name'],
                    'role_name' => $user_roles->role_name,
                    'user_role' => $user_roles->role_id,
                    'user_access' => $user_access,
                    'last_activity_time' => now(),
                ]);
    
                return redirect()->intended('/home');
            // }
            // else{
            //     Auth::logout();
            //     return view('login')->with([
            //         'error' => 'Device Not registered',
            //         'mac_add' => $user_mac,
            //     ]);
            // }

           
        }

        // Authentication failed
        return back()->withErrors([
            'error' => 'Invalid Username or Password.',
        ]);

    }

    public function addDevice(Request $request){
        $user_mac_address = user_mac_address::create([
            'user_id' => $request->user_mac_id,
            'device_name' => $request->user_device_name,
            'mac_address' => $request->user_device_password,
            'created_by' => session('user_id'),
        ]);

        return redirect()->route('all-users');

    }

    public function getMacAdd(Request $request){
        $user_mac_address = user_mac_address::where('user_id', $request->id)
        ->select('id', 'device_name', 'mac_address')
        ->get();

        return $user_mac_address;
    }

    public function logout()
    {
        users::where('id', session('user_id'))->update([
            'is_login'=>0,
        ]);
        Auth::logout();
        return redirect()->route('login');
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->attach($request->role_id);

        return redirect()->back()->with('success', 'Role assigned successfully!');
    }

    public function getUserDetails(Request $request){
        $user = users::where('id', $request->id)->first();
        $user_role=user_roles::where('user_id',$request->id)->first();
       
        return response()->json([
            'user' => $user,
            'user_role' => $user_role
        ]);
    }

    public function deactivateUser(Request $request)
    {
        users::where('id', $request->deactivate_user)->update([
            'status' => '0',
            'updated_by' => session('user_id'),
        ]);
        return redirect()->route('all-users');
    }

    public function activateUser(Request $request)
    {
        users::where('id', $request->activate_user)->update([
            'status' => '1',
            'updated_by' => session('user_id'),
        ]);
        return redirect()->route('all-users');
    }

    public function getAttachements(Request $request)
    {
        $user_att = users::where('id', $request->id)
        ->select('picture','cnic_front','cnic_back')
        ->get();

        return $user_att;
    }

    public function changeCredentials(Request $request){
        $user = users::where('id', $request->user_cred_id)
        ->select('username','password')
        ->first();

        if ($request->has('update_user_username') && $request->update_user_username) {
            $user->username=$request->update_user_username;
        }

        if ($request->has('update_user_password') && $request->update_user_password) {
            $user->password=Hash::make($request->update_user_password);
        }

        users::where('id', $request->user_cred_id)->update([
            'username'=> $user->username,
            'password' => $user->password,
        ]);

        return redirect()->route('all-users');
    }

    public function getMacAddress()
    {
        $output = shell_exec('ifconfig'); // Or 'ip link show'
        
        if ($output === null) {
            return 'Command execution failed.';
        }
    
        // Log output to a file for debugging
        file_put_contents('ifconfig_output.txt', $output);
    
        $lines = explode("\n", $output);
        foreach ($lines as $line) {
            if (preg_match('/ether ([\da-f:]+)/', $line, $matches)) {
                return $matches[1]; // Return the first MAC address found
            }
        }
    
        return 'Unable to retrieve MAC Address';
    }

    public function getUserPassword(Request $request){
        $user_password = users::where('id', session('user_id'))->value('password');
        if (Hash::check($request->password, $user_password)) {
            return 1;
        }
        return 0;
    }

    public function updateUserPassowrd(Request $request){
        $user = users::where('id', session('user_id'))
        ->select('password')
        ->first();

        if ($request->has('new_password') && $request->new_password) {
            $user->password=Hash::make($request->new_password);
        }

        users::where('id', session('user_id'))->update([
            'password' => $user->password,
        ]);
        return redirect('/home');
    }
}

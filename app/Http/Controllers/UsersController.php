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
use App\Traits\SaveImage;

class UsersController extends Controller
{
    //
    use SaveImage;

    public function index()
    {
        $users = users::join('user_roles', 'user_roles.user_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'user_roles.role_id')
        ->select('users.*', 'roles.name as role_name')
        ->get();
        $roles = roles::get();

        return view('users.users',compact('roles','users'));
    }

    public function loginScreen()
    {
        if (Auth::check()) {
            return view('home'); // Show the home view if authenticated
        }
        return view('/login');
    }

    public function createUser(Request $request)
    {
        $att_path = null;
        $cnic_front_path = null;
        $cnic_back_path = null;

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

        if ($request->hasFile('att') && $request->file('att')) {
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
                'profile' => $att_path,
                'cnic_front' => $cnic_front_path,
                'cnic_back' => $cnic_back_path,
            ]);

            $user_id=users::latest()->select('id')->first()->id;

            $user_role= user_roles::create([
                'user_id' => $user_id,
                'role_id' => $request->role_id,
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
    
        $user = users::where('id', $request->user_id)->first();

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
        if ($request->has('update_cnic_no') && $request->update_cnic_no) {
            $user->cnic_no=$request->update_date;
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
        ]);

        user_roles::where('user_id', $request->update_user_id)->update([
            'role_id' => $request->update_role,
        ]);

        return redirect()->route('all-users')->with('success', 'User updated successfully.');
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'status' => 1])) {
            // Authentication passed

            $request->session()->regenerate();
            $user = Auth::user();

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
                'role_name' => $user_roles['role_name'],
                'user_role' => $user_roles['role_id'],
                'user_access' => $user_access,
            ]);

            return redirect()->intended('/home');
        }

        // Authentication failed
        return back()->withErrors([
            'username' => 'Invalid Credentials.',
        ]);


    }

    public function logout()
    {
        Auth::logout();
        return view('/login');
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
        users::where('id', $request->deactivate_user)->update(['status' => '0']);
        return redirect()->route('all-users');
    }

    public function activateUser(Request $request)
    {
        users::where('id', $request->activate_user)->update(['status' => '1']);
        return redirect()->route('all-users');
    }

    public function getAttachements(Request $request)
    {
        $user_att = users::where('id', $request->id)
        ->select('picture','cnic_front','cnic_back')
        ->get();

        return $user_att;
    }
}

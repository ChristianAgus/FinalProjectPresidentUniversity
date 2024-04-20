<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function user(Request $request)
    {
        if($request->ajax()) {
            $data = User::orderByRaw("name Desc");
            return DataTables::of($data)
                ->addColumn('edit', function ($data) {
                    $category_data = [
                        'id'         => $data->id,
                        'fullname'   => $data->name,
                        'first_name' => $data->first_name,
                        'last_name'  => $data->last_name,
                        'username'   => $data->username,
                        'password'   => $data->password,
                        'email'      => $data->email,
                        'birthdate'  => $data->birth_date,
                        'gender'     => $data->gender,
                        'address'    => $data->address,
                        'phone'     => $data->phone_number,
                        'role'       => $data->role,
                    ];
                    return "<button onclick='editModal(".json_encode($category_data).")' class='btn btn-sm btn-outline-primary' title='Edit'>Edit</button>
                    <button data-url=\"" . route('delet', $data->id) . "\" class=\"btn btn-sm btn-outline-danger btn-square delete-btn\" title=\"Delete\"><i class=\"fa fa-trash\"></i></button>";
                })
                
                ->rawColumns(['edit'])
                ->make(true);
        }
        return view('backend.akun.user');
    }

    public function profil(Request $request)
    {
        return view('backend.akun.profil');
    }
    public function delete($id)
    {
        $brand = User::findOrFail($id);
        $brand->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }  
    public function create(Request $request)
    {
        $data = $request->all();
    
        $rules = [
            'fullname'     => 'required|string',
            'firstname'    => 'required|string',
            'lastname'     => 'required|string',
            'username'     => 'required|unique:users',
            'password'     => 'required|min:6',
            'repassword'   => 'required|same:password', 
            'email'        => 'required|email|unique:users',
            'birthdate'    => 'date_format:Y-m-d|nullable',
            'address'      => 'string|nullable',
            'role'         => 'in:User,Sales,Admin|nullable',
        ];
        
    
        $validator = validator($data, $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        } else {
            try {
                DB::beginTransaction();

                $data['password'] = bcrypt($request->password);
                if ($request->password !== $request->repassword) {
                    throw new \Exception('Password and reconfirm password do not match.');
                }
                User::create([
                    'name'         => $request->fullname,
                    'first_name'   => $request->firstname,
                    'last_name'    => $request->lastname,
                    'username'     => $request->username,
                    'password'     => $data['password'],
                    'email'        => $request->email,
                    'birth_date'   => $request->birthdate,
                    'gender'       => $request->gender,
                    'address'      => $request->address,
                    'phone_number' => $request->phone,
                    'role'         => $request->role,
                ]);
    
                DB::commit();
    
                return response()->json([
                    'success' => true,
                    'message' => 'User added successfully!',
                ]);
    
            } catch (Exception $e) {
                DB::rollback();
    
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }
    
    public function update(Request $request, $id)
    {
        $data = $request->all();
    
        $rules = [
            'fullname'     => 'required|string',
            'firstname'    => 'required|string',
            'lastname'     => 'required|string',
            'username'     => 'required|unique:users,username,'.$id,
            'email'        => 'required|email|unique:users,email,'.$id,
            'birthdate'    => 'date_format:Y-m-d|nullable',
            'address'      => 'string|nullable',
            'phone'        => 'string|nullable',
            'role'         => 'in:User,Sales,Admin|nullable',
            'password'     => 'nullable|min:6',
            'repassword'   => 'nullable|same:password',
        ];
    
        $validator = validator($data, $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        } else {
            try {
                $db_user = User::where('id', $id)->first();
                $db_user->name         = $request->fullname;   
                $db_user->first_name   = $request->firstname;
                $db_user->last_name    = $request->lastname;
                $db_user->username     = $request->username;
                $db_user->email        = $request->email;
                $db_user->birth_date   = $request->birthdate;
                $db_user->gender       = $request->gender;
                $db_user->address      = $request->address;
                $db_user->phone_number = $request->phone;
                $db_user->role         = $request->role;
                if (!empty($request->password)) {
                    $db_user->password = bcrypt($request->password);
                }
    
                $db_user->save();
    
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully!',
                ]);
    
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }

    public function updateprofile(Request $request)
    {
        $id = auth()->user()->id;
    
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'name' => 'required|string',
            'username' => 'required|string',
            'gender' => 'required|string',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'phone_number' => 'required|string',
            'role' => 'required|string',
            'email' => 'required|email',
        ]);
    
        $user = User::find($id);
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ]);
        }
    
        try {
            $user->update($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile. Error: ' . $e->getMessage(),
            ]);
        }
    }
    
    
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ]);
        }
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!',
        ]);
}



}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        return view('admin.index');
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function adminLogin()
    {

        return view('admin.admin_login');
    }

    public function adminProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_profile_view', compact('profileData'));
    }

    public function adminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName(); // e.g. 23232.admin.png
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(

                'message' => 'Admin Profile Updated Successfully',
                'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    public function adminChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_change_password', compact('profileData'));
    }
    public function adminUpdatePassword(Request $request)
    {

        /// Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);


        /// Match the old password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            $notification = array(
                'message' => 'Old Password Does Not Match!',
                'alert-type' => 'error'
            );

            return back()->with($notification);
        }


        /// Update new password

        User::whereId(auth()->user()->id)->update([

            'password' => Hash::make($request->new_password),
        ]);

        $notification = array(

                'message' => 'Password changed Successfully!',
                'alert-type' => 'success'
           );

        return back()->with($notification);
    }

    public function adminViewAll()
    {
        $users = User::all();
        return view('admin.admin_view_all', compact('users'));
    }
    public function adminViewUser($id)
    {
        $user = User::find($id);
        return view('admin.admin_view_user', compact('user'));
    }

    public function adminEditUser($id)
    {
        $user = User::find($id);

        return view('admin.admin_edit_user', compact('user'));
    }

    public function adminUpdateUser(Request $request, $id)
    {
        $user = User::find($id);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/' . $user->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName(); // e.g. 23232.admin.png
            $file->move(public_path('upload/admin_images'), $filename);
            $user['photo'] = $filename;
        }

        $user->save();

        $notification = array(

                'message' => 'Admin Profile Updated Successfully',
                'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    public function adminDeleteUser($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            $notification = array(
                'message' => 'User not found',
                'alert-type' => 'error'
            );
        } else {
            $user->delete();
            $notification = array(
                'message' => 'User is deleted',
                'alert-type' => 'success'
            );
        }

        return redirect()->back()->with($notification);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();
        return view('admin.users.index', compact('users'));
    }

    public function vendors()
    {
        return view('admin.users.vendors');
    }

    public function create()
    {
        return view('admin.users.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users'
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = $image->hashName();
            $image->move(public_path('/uploads/users/'), $image_name);

            $filePath = "/uploads/users/" . $image_name;
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->tax_number = $request->tax_number;
        $user->active = $request->active == 'on' ? 1 : 0;
        $user->password = bcrypt($request->password);
        if (isset($filePath) && $filePath)
            $user->image = $filePath;
        $user->save();
        if ($request->save == 1)
            return redirect()->route('admin.users.edit', $user->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.users.index')->with('success', __('msg.created_success'));
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            if ($request->hasFile('image')) {
                $file = $user->image;
                $filename = public_path() . '' . $file;
                File::delete($filename);

                $image = $request->file('image');
                $image_name = $image->hashName();
                $image->move(public_path('/uploads/users/'), $image_name);

                $filePath = "/uploads/users/" . $image_name;
            }

            if ($request->first_name)
                $user->first_name = $request->first_name;
            if ($request->last_name)
                $user->last_name = $request->last_name;
            if ($request->phone)
                $user->phone = $request->phone;
            if ($request->email)
                $user->email = $request->email;
            if ($request->password)
                $user->password = bcrypt($request->password);
            if (isset($filePath) && $filePath)
                $user->image = $filePath;
            $user->active = $request->active == 'on' ? 1 : 0;
            $user->save();
        }
        if ($request->save == 1)
            return redirect()->route('admin.users.edit', $user->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.users.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->active = $request->active == 'true' ? 1 : 0;
            $user->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

}

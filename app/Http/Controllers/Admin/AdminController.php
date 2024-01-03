<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PermitionGroup;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function profile()
    {
        return view('admin.profile.profile');
    }

    public function updateProfile(Request $request)
    {
        $admin = admin();
        if ($request->name)
            $admin->name = $request->name;
        if ($request->email)
            $admin->email = $request->email;
        if ($request->password)
            $admin->password = bcrypt($request->password);
        $admin->save();

        return redirect()->back()->with('success', __('msg.updated_success'));
    }

    public function index()
    {
        $admins = Admin::where('parent_id',0)->whereNotIn('id',[admin()->id])->orderBy('id', 'DESC')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:admins',
        ]);
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->active = $request->active == 'on' ? 1 : 0;
        $admin->password = bcrypt($request->password);
        $admin->save();

        if ($request->save == 1)
            return redirect()->route('admin.admins.edit', $admin->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.admins.index')->with('success', __('msg.created_success'));
    }

    public function edit($id)
    {
        $admin = Admin::find($id);
        $permissions_groups = PermitionGroup::get();
        return view('admin.admins.edit', ['admin' => $admin,'permissions_groups' => $permissions_groups]);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            if ($request->name)
                $admin->name = $request->name;
            if ($request->email)
                $admin->email = $request->email;
            if ($request->password)
                $admin->password = bcrypt($request->password);
            $admin->active = $request->active == 'on' ? 1 : 0;
            $admin->save();
        }
        if ($request->save == 1)
            return redirect()->route('admin.admins.edit', $admin->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.admins.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request)
    {
        $admin = Admin::find($request->id);
        if ($admin)
            $admin->delete();
        return response()->json([
            'status' => true,
            'id' => $request->id,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $admin = Admin::find($request->id);
        if ($admin) {
            $admin->active = $request->active == 'true' ? 1 : 0;
            $admin->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function storeEmployee(Request $request)
    {
        if (!$request->emp_id){
        $admin = Admin::where('email', $request->email)->first();
            if ($admin)
                return response()->json([
                    'status' => false,
                    'msg' => 'الايميل مستخدم من قبل',
                ]);
        }
        if ($request->emp_id)
            $admin = Admin::find($request->emp_id);
        else
            $admin = new Admin();

        if ($request->name)
        $admin->name = $request->name;
        if ($request->email)
        $admin->email = $request->email;
        $admin->parent_id = $request->admin_id;
        $admin->permission_group_id = $request->permission_group_id;
        if ($request->active)
        $admin->active = $request->active == 'true' ? 1 : 0;
        if ($request->password)
            $admin->password = bcrypt($request->password);
        $admin->save();

        if (!$request->emp_id) {
            $checked = $admin->active == 1 ? 'checked' : '';
            $emp_html = '<tr class="row_' . $admin->id . '">
              <td class="text-center">' . $admin->name . '</td>
              <td class="text-center">' . $admin->email . '</td>
              <td class="text-center">
                  <div class="form-group pb-1">
                      <input type="checkbox" item_id="' . $admin->id . '" name="active"
                             class="switch active_item" id="switch' . $admin->id . '"
                          ' . $checked . '/>
                  </div>
              </td>
              <td class="text-center">
                  <button type="button" item_id="' . $admin->id . '" class="item_delete btn btn-danger">
                      <i class="la la-trash"></i></button>

                  <button type="button"
                     class="block-page ml-2 btn btn-primary item_edit" item_id="' . $admin->id . '"><i
                          class="la la-pencil"></i></button>
              </td>
          </tr>';
        }
        return response()->json([
            'status' => true,
            'id' => $admin->id,
            'emp_html' => $request->emp_id ? '' : $emp_html
        ]);
    }

    public function editEmployee(Request $request){
        $admin = Admin::find($request->emp_id);
        return response()->json([
            'status' => true,
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'active' => $admin->active,
            'permission_group_id' => $admin->permission_group_id
        ]);
    }
}

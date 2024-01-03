<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPermitions;
use App\Models\Links;
use App\Models\PermitionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermitionGroupController extends Controller
{
    public function index()
    {
        $groups = PermitionGroup::orderBy('id', 'DESC')->get();
        return view('admin.permition_group.index', compact('groups'));
    }

    public function create()
    {
        $links = Links::where('parent_id',0)->whereNotIn('id',[3,5])->get();
        return view('admin.permition_group.add',compact('links'));
    }

    public function store(Request $request)
    {
        if (PermitionGroup::count() > 0 && $request->is_default == 'on')
            DB::table('permition_groups')
                ->update(['is_default' => 0]);
        $group = new PermitionGroup();
        $group->name = $request->name;
        $group->is_default = $request->is_default == 'on' ? 1 : 0;
        $group->save();

        if ($request->permission && count($request->permission) > 0){
            foreach ($request->permission as $link){
                $perm = new AdminPermitions;
                $perm->permission_group_id = $group->id;
                $perm->link_id = $link;
                $perm->save();
            }
        }
        if ($request->save == 1)
            return redirect()->route('admin.permission_group.edit', $group->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.permission_group.index')->with('success', __('msg.created_success'));
    }

    public function edit($id)
    {
        $group = PermitionGroup::find($id);
        $links = Links::where('parent_id',0)->whereNotIn('id',[3,5])->get();
        return view('admin.permition_group.edit', compact('group','links'));
    }

    public function update(Request $request, $id)
    {
        $group = PermitionGroup::find($id);

        if ($request->is_default == 'on')
            DB::table('permition_groups')
                ->update(['is_default' => 0]);
        $group->name = $request->name;
        $group->is_default = $request->is_default == 'on' ? 1 : 0;
        $group->save();
        if ($request->permission && count($request->permission) > 0){
            if (isset($group->links_permissions) && count($group->links_permissions) > 0)
                $group->links_permissions()->delete();
            foreach ($request->permission as $link){
                $perm = new AdminPermitions;
                $perm->permission_group_id = $group->id;
                $perm->link_id = $link;
                $perm->save();
            }
        }
        if ($request->save == 1)
            return redirect()->route('admin.permission_group.edit', $group->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.permission_group.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request)
    {
        $group = PermitionGroup::find($request->id);
        if ($group) {
            $group->delete();
            $default_permission = PermitionGroup::where('is_default',1)->first();
            $default_permission_id = $default_permission ? $default_permission->id : 1;

            Admin::where('permission_group_id',$request->id)->update([
                'permission_group_id' => $default_permission_id
            ]);
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}

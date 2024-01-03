<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reason;
use Illuminate\Http\Request;

class ReasonController extends Controller
{
    public function index(){
        $reasons = Reason::orderBy('id','DESC')->get();
        return view('admin.reasons.index',compact('reasons'));
    }

    public function create(){
        return view('admin.reasons.add');
    }

    public function store(Request $request){
        $reason = new Reason();
        $reason->title_ar = $request->title_ar;
        $reason->title_en = $request->title_en;
        $reason->save();
        if ($request->save == 1)
            return redirect()->route('admin.reasons.edit', $reason->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.reasons.index')->with('success', __('msg.created_success'));
    }

    public function edit($id){
        $reason = Reason::find($id);
        return view('admin.reasons.edit',compact('reason'));
    }

    public function update(Request $request,$id){
        $reason = Reason::find($id);
        if ($reason) {
            $reason->title_ar = $request->title_ar;
            $reason->title_en = $request->title_en;
            $reason->save();
        }
        if ($request->save == 1)
            return redirect()->route('admin.reasons.edit', $reason->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.reasons.index')->with('success', __('msg.created_success'));
    }


    public function delete(Request $request){
        $reason = Reason::find($request->id);
        if ($reason){
            $reason->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdditionService;
use Illuminate\Http\Request;

class AdditionServicesController extends Controller
{
    public function index(){
        $addition_services = AdditionService::orderBy('id','DESC')->get();
        return view('admin.addition_services.index',compact('addition_services'));
    }

    public function create(){
        return view('admin.addition_services.add');
    }

    public function store(Request $request){
        $addition_service = new AdditionService();
        $addition_service->name_ar = $request->name_ar;
        $addition_service->name_en = $request->name_en;
        $addition_service->price = $request->price > 0 ? $request->price : 0;
        $addition_service->active = $request->active == 'on' ? 1 : 0;
        $addition_service->save();
        if ($request->save == 1)
            return redirect()->route('admin.addition_services.edit', $addition_service->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.addition_services.index')->with('success', __('msg.created_success'));
    }

    public function edit($id){
        $addition_service = AdditionService::find($id);
        return view('admin.addition_services.edit',compact('addition_service'));
    }

    public function update(Request $request,$id){
        $addition_service = AdditionService::find($id);
        if ($addition_service) {
            $addition_service->name_ar = $request->name_ar;
            $addition_service->name_en = $request->name_en;
            $addition_service->price = $request->price > 0 ? $request->price : 0;
            $addition_service->active = $request->active == 'on' ? 1 : 0;
            $addition_service->save();
        }
        if ($request->save == 1)
            return redirect()->route('admin.addition_services.edit', $addition_service->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.addition_services.index')->with('success', __('msg.created_success'));
    }


    public function delete(Request $request){
        $addition_service = AdditionService::find($request->id);
        if ($addition_service){
            $addition_service->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $addition_service = AdditionService::find($request->id);
        if ($addition_service) {
            $addition_service->active = $request->active == 'true' ? 1 : 0;
            $addition_service->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}

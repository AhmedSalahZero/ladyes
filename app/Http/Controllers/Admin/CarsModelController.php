<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarsModel;
use Illuminate\Http\Request;

class CarsModelController extends Controller
{
    public function index(){
        $cars_models = CarsModel::orderBy('id','DESC')->get();
        return view('admin.cars_models.index',compact('cars_models'));
    }

    public function create(){
        return view('admin.cars_models.add');
    }

    public function store(Request $request){
        $cars_model = new CarsModel();
        $cars_model->name_ar = $request->name_ar;
        $cars_model->name_en = $request->name_en;
        $cars_model->active = $request->active == 'on' ? 1 : 0;
        $cars_model->save();
        if ($request->save == 1)
            return redirect()->route('admin.cars_models.edit', $cars_model->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.cars_models.index')->with('success', __('msg.created_success'));
    }

    public function edit($id){
        $cars_model = CarsModel::find($id);
        return view('admin.cars_models.edit',compact('cars_model'));
    }

    public function update(Request $request,$id){
        $cars_model = CarsModel::find($id);
        if ($cars_model) {
            $cars_model->name_ar = $request->name_ar;
            $cars_model->name_en = $request->name_en;
            $cars_model->active = $request->active == 'on' ? 1 : 0;
            $cars_model->save();
        }
        if ($request->save == 1)
            return redirect()->route('admin.cars_models.edit', $cars_model->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.cars_models.index')->with('success', __('msg.created_success'));
    }


    public function delete(Request $request){
        $cars_model = CarsModel::find($request->id);
        if ($cars_model){
            $cars_model->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
    public function updateStatus(Request $request){
        $cars_model = CarsModel::find($request->id);
        if ($cars_model){
            $cars_model->active = $request->active == 'true' ? 1 : 0;
            $cars_model->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Onbording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OnbordingController extends Controller
{
    public function index(){
        $onbording = Onbording::orderBy('id','DESC')->get();
        return view('admin.onbording.index',compact('onbording'));
    }

    public function create(){
        return view('admin.onbording.add');
    }

    public function store(Request $request){
        if($request->hasFile('image')){
            $image = $request->file('image');
            $image_name = $image->hashName();
            $image->move(public_path('/uploads/onbording/'),$image_name);

            $filePath = "/uploads/onbording/" . $image_name;
        }
        $onbording = new Onbording();
        $onbording->title_ar = $request->title_ar;
        $onbording->title_en = $request->title_en;
        $onbording->desc_ar = $request->desc_ar;
        $onbording->desc_en = $request->desc_en;
        $onbording->active = $request->active == 'on' ? 1 : 0;
        if (isset($filePath) && $filePath)
            $onbording->image = $filePath;
        $onbording->save();
        if ($request->save == 1)
            return redirect()->route('admin.onbording.edit', $onbording->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.onbording.index')->with('success', __('msg.created_success'));
    }

    public function edit($id){
        $onbording = Onbording::find($id);
        return view('admin.onbording.edit',['onbording' => $onbording]);
    }

    public function update(Request $request,$id){
        $onbording = Onbording::find($id);
        if ($request->hasFile('image')) {
            $file = $onbording->image;
            $filename = public_path() . '' . $file;
            File::delete($filename);

            $image = $request->file('image');
            $image_name = $image->hashName();
            $image->move(public_path('/uploads/onbording/'), $image_name);

            $filePath = "/uploads/onbording/" . $image_name;
        }
        $onbording->title_ar = $request->title_ar;
        $onbording->title_en = $request->title_en;
        $onbording->desc_ar = $request->desc_ar;
        $onbording->desc_en = $request->desc_en;
        $onbording->active = $request->active == 'on' ? 1 : 0;
        if (isset($filePath) && $filePath)
            $onbording->image = $filePath;
        $onbording->save();
        if ($request->save == 1)
            return redirect()->route('admin.onbording.edit', $onbording->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.onbording.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request){
        $onbording = Onbording::find($request->id);
        if ($onbording){
            $file = $onbording->image;
            $filename = public_path() . '/' . $file;
            File::delete($filename);

            $onbording->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function updateStatus(Request $request){
        $onbording = Onbording::find($request->id);
        if ($onbording){
            $onbording->active = $request->active == 'true' ? 1 : 0;
            $onbording->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}

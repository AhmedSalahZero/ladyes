<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    public function index(){
        $sliders = Slider::orderBy('id','DESC')->get();
        return view('admin.sliders.index',compact('sliders'));
    }

    public function create(){
        return view('admin.sliders.add');
    }

    public function store(Request $request){
        if($request->hasFile('image')){
            $image = $request->file('image');
            $image_name = $image->hashName();
            $image->move(public_path('/uploads/slider/'),$image_name);

            $filePath = "/uploads/slider/" . $image_name;
        }
        $slider = new Slider();
        $slider->title_ar = $request->title_ar;
        $slider->title_en = $request->title_en;
        $slider->active = $request->active == 'on' ? 1 : 0;
        if (isset($filePath) && $filePath)
            $slider->image = $filePath;
        $slider->save();
        if ($request->save == 1)
            return redirect()->route('admin.sliders.edit', $slider->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.sliders.index')->with('success', __('msg.created_success'));
    }

    public function edit($id){
        $slider = Slider::find($id);
        return view('admin.sliders.edit',['slider' => $slider]);
    }

    public function update(Request $request,$id){
        $slider = Slider::find($id);
        if ($request->hasFile('image')) {
            $file = $slider->image;
            $filename = public_path() . '' . $file;
            File::delete($filename);

            $image = $request->file('image');
            $image_name = $image->hashName();
            $image->move(public_path('/uploads/slider/'), $image_name);

            $filePath = "/uploads/slider/" . $image_name;
        }
        $slider->title_ar = $request->title_ar;
        $slider->title_en = $request->title_en;
        $slider->active = $request->active == 'on' ? 1 : 0;
        if (isset($filePath) && $filePath)
            $slider->image = $filePath;
        $slider->save();
        if ($request->save == 1)
            return redirect()->route('admin.sliders.edit', $slider->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.sliders.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request){
        $slider = Slider::find($request->id);
        if ($slider){
            $file = $slider->image;
            $filename = public_path() . '/' . $file;
            File::delete($filename);

            $slider->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function updateStatus(Request $request){
        $slider = Slider::find($request->id);
        if ($slider){
            $slider->active = $request->active == 'true' ? 1 : 0;
            $slider->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}

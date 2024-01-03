<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function settings(){
        $setting = Settings::first();
        return view('admin.settings.edit',['setting' => $setting]);
    }

    public function update(Request $request){
        $setting = Settings::first();
        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                $file = $setting->logo;
                $filename = public_path() . '' . $file;
                File::delete($filename);
            }
            $image = $request->file('logo');
            $logo_image_name = $image->hashName();
            $image->move(public_path('/uploads/settings/'), $logo_image_name);

            $logofilePath = "/uploads/settings/" . $logo_image_name;
        }

        if ($request->hasFile('fav_icon')) {
            if ($setting->fav_icon) {
                $file = $setting->fav_icon;
                $filename = public_path() . '' . $file;
                File::delete($filename);
            }
            $image = $request->file('fav_icon');
            $fav_icon_image_name = $image->hashName();
            $image->move(public_path('/uploads/settings/'), $fav_icon_image_name);

            $fav_iconfilePath = "/uploads/settings/" . $fav_icon_image_name;
        }
        if (isset($logofilePath))
            $setting->logo = $logofilePath;
        if (isset($fav_iconfilePath))
            $setting->fav_icon = $fav_iconfilePath;
        $setting->phone = $request->phone;
        $setting->address = $request->address;
        $setting->email = $request->email;
        $setting->work_time = $request->work_time;
        $setting->whatsapp = $request->whatsapp;
        $setting->facebook = $request->facebook;
        $setting->instagram = $request->instagram;
        $setting->twitter = $request->twitter;
        $setting->seo_title = $request->seo_title;
        $setting->seo_desc = $request->seo_desc;
        $setting->seo_keyword = $request->seo_keyword;
        $setting->vat = $request->vat;
        $setting->instructions_ar = $request->instructions_ar;
        $setting->instructions_en = $request->instructions_en;
        $setting->economic_price = $request->economic_price;
        $setting->project_price = $request->project_price;
        $setting->class_price = $request->class_price;
        $setting->family_price = $request->family_price;
        $setting->skip_time_price = $request->skip_time_price;
        $setting->profit_rate = $request->profit_rate;
        $setting->user_cancel_travel_penalty = $request->user_cancel_travel_penalty;
        $setting->driver_cancel_travel_penalty = $request->driver_cancel_travel_penalty;
        $setting->save();

        return redirect()->back()->with('success',__('msg.updated_success'));
    }

    public function deleteFile(Request $request){
        $media = Media::find($request->id);
        if ($media){
            $file = $media->file_path;
            $filename = public_path() . '/' . $file;
            File::delete($filename);

            $media->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}

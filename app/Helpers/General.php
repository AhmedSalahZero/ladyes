<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;


function get_default_languages()
{
    return Config::get('app.locale');
}

function admin(){
    return auth()->guard('admin')->user();
}

function user(){
    return auth()->guard('web')->user();
}
function upload_file($file,$old_file_id ,$type,$folder,$modal,$mediable_id){
    if ($old_file_id && $old_file_id > 0){
        $old_file_id_data = \App\Models\Media::find($old_file_id);
        $oldfilename = public_path() . '' . $old_file_id_data->file_path;
        File::delete($oldfilename);
        $old_file_id_data->delete();
    }
    $image_name = $file->hashName();
    $file->move(public_path('/uploads/'.$folder."/"), $image_name);
    $filePath = "/uploads/".$folder."/". $image_name;
    $media_file = new \App\Models\Media();
    $media_file->mediable_type = $modal;
    $media_file->file_name = $file->getClientOriginalName();
    $media_file->mediable_id = $mediable_id;
    $media_file->file_path = $filePath;
    $media_file->type = $type;
    $media_file->save();

    return $filePath;
}

function user_data()
{
    $user = auth()->user();
    return [
        'id' => $user->id,
        'first_name' => $user->first_name ?? '',
        'last_name' => $user->last_name ?? '',
        'phone' => $user->phone ?? '',
        'email' => $user->email ?? '',
        'tax_number' => $user->tax_number ?? '',
        'birth_date' => $user->birth_date ?? '',
        'balance' => number_format($user->balance,2),
        'expenses' => number_format($user->expenses,2),
        'image' => $user->image ? asset('public'.$user->image) : '',
        'addition_services' => $user->addition_services_list('ar'),
    ];
}
function driver_data()
{
    $user = auth()->user();
    return [
        'id' => $user->id,
        'first_name' => $user->first_name ?? '',
        'last_name' => $user->last_name ?? '',
        'id_number' => $user->id_number ?? '',
        'car_type' => $user->car_type ?? '',
        'car_number' => $user->car_number ?? '',
        'car_color' => $user->car_color ?? '',
        'phone' => $user->phone ?? '',
        'email' => $user->email ?? '',
        'driver_image' => $user->file('driver_image') ? asset('public'.$user->file('driver_image')->file_path) : '',
        'id_image' => $user->file('id_image') ? asset('public'.$user->file('id_image')->file_path) : '',
        'driving_license' => $user->file('driving_license') ? asset('public'.$user->file('driving_license')->file_path) : '',
        'car_image' => $user->file('car_image') ? asset('public'.$user->file('car_image')->file_path) : '',
        'car_front_image' => $user->file('car_front_image') ? asset('public'.$user->file('car_front_image')->file_path) : '',
        'car_back_image' => $user->file('car_back_image') ? asset('public'.$user->file('car_back_image')->file_path) : '',
        'country' => isset($user->country) ? $user->country : null,
        'city' => isset($user->city) ? $user->city : null,
        'area' => isset($user->area) ? $user->area : null,
        'car_model_data' => isset($user->car_model_data) ? $user->car_model_data : null,
    ];
}

function send_sms($msg,$phone)
{

}


function sendToFcm($fcm, $text, $title)
{

    $header = [];
    $header[] = 'Content-Type: application/json ';
    $header[] = 'Authorization: key=AAAA1Gx2kz4:APA91bF1X4aVd4NgSq-yvmMzKwUWVdzyC9aLuHEteiwlDyZd82KBJvFus9E7CDDn9URil6cK17JpHJoM6SWh3vjhAY8-qI6X_WAYLl7VxHQKS1OZYwIrRGGgihAu897gKNy2ge40yvdC';

    $data = [
        "to" => $fcm,
        "notification" => [
            "sound" => "default",
            "body" => $text,
            "title" => $title,
            "content_available" => true,
            "priority" => "high"
        ],
        "data" => [
            "sound" => "default",
            "body" => $text,
            "title" => $title,
            "content_available" => true,
            "priority" => "high"
        ]
    ];

    $url = 'https://fcm.googleapis.com/fcm/send';

    $data = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;

}

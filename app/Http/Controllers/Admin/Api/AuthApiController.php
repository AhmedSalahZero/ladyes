<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class AuthApiController extends Controller
{
    var $lang;
    use ApiTrait;

    ################ auth #############
    public function __construct()
    {
        $this->lang = \request('lang') ? \request('lang') : 'ar';
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $user = User::where('phone', $request->phone)->first();

        if (!$user)
            return $this->errorResponse(__('msg.user_not_found'), 401);
        if ($user->active == 0)
                    return $this->errorResponse(__('msg.user_not_active'), 401);

        $user->verification_code = rand(1111,9999);
        $user->fcm_token = $request->fcm_token;
        $user->save();

        $msg = ' رمز التحقق الخاص بك هو '.$user->verification_code;
        send_sms($msg,$user->phone);
        return $this->successResponse(__('msg.code_sent_successfully'), 200);
    }

    public function registerUser(Request $request)
    {

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'phone' => 'required|unique:users',
            'email' => 'unique:users',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->fcm_token = $request->fcm_token;
        $user->email = $request->email ? $request->email : $request->phone.'@gmail.com';
        $user->birth_date = $request->birth_date;
        $user->active = 1;
        $user->role_id = 1;
        $user->save();


        return $this->successResponse(__('msg.success_register'), 200);
    }

    public function registerDriver(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'phone' => 'required|unique:users',
            'email' => 'unique:users',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);
        $driver = new User();
        $driver->first_name = $request->first_name;
        $driver->id_number = $request->id_number;
        $driver->city_id = $request->city_id;
        $driver->area_id = $request->area_id;
        $driver->fcm_token = $request->fcm_token;
        $driver->car_type = $request->car_type;
        $driver->model_id = $request->model_id;
        $driver->car_number = $request->car_number;
        $driver->car_color = $request->car_color;
        $driver->last_name = $request->last_name;
        $driver->phone = $request->phone;
        $driver->email = $request->email ? $request->email : $request->phone.'@gmail.com';
        $driver->role_id = 2;
        $driver->prod_year = $request->prod_year;
        $driver->country_id = $request->country_id;
        $driver->active = 0;
        $driver->password = bcrypt($request->password);
        $driver->save();

        if ($request->driver_image)
            upload_file($request->driver_image,0, 'driver_image', 'drivers', 'App\Models\User', $driver->id);
        if ($request->id_image)
            upload_file($request->id_image,0, 'id_image', 'drivers', 'App\Models\User', $driver->id);
        if ($request->driving_license)
            upload_file($request->driving_license,0, 'driving_license', 'drivers', 'App\Models\User', $driver->id);
        if ($request->car_image)
            upload_file($request->car_image,0, 'car_image', 'drivers', 'App\Models\User', $driver->id);
        if ($request->car_front_image)
            upload_file($request->car_front_image,0, 'car_front_image', 'drivers', 'App\Models\User', $driver->id);
        if ($request->car_back_image)
            upload_file($request->car_back_image,0, 'car_back_image', 'drivers', 'App\Models\User', $driver->id);

        return $this->successResponse(__('msg.success_register'), 200);
    }

    public function logout()
    {
        auth()->logout();

        return $this->successResponse(__('msg.logged_success'), 200);
    }

    ############### end auth ######
}

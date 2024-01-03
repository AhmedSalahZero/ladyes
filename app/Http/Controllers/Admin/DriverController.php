<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarsModel;
use App\Models\Country;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = User::where('role_id',2)->orderBy('id', 'DESC')->get();
        return view('admin.drivers.index', compact('drivers'));
    }


    public function create()
    {
        $cars_models = CarsModel::get();

        $countries = Country::where('country_id',0)->where('city_id',0)->orderBy('id', 'DESC')->get();
        return view('admin.drivers.add',compact('cars_models','countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users'
        ]);

        $driver = new User();
        $driver->first_name = $request->first_name;
        $driver->id_number = $request->id_number;
        $driver->city_id = $request->city_id;
        $driver->area_id = $request->area_id;
        $driver->car_type = $request->car_type;
        $driver->model_id = $request->model_id;
        $driver->car_number = $request->car_number;
        $driver->car_color = $request->car_color;
        $driver->last_name = $request->last_name;
        $driver->phone = $request->phone;
        $driver->email = $request->email;
        $driver->role_id = 2;
        $driver->prod_year = $request->prod_year;
        $driver->country_id = $request->country_id;
        $driver->active = $request->active == 'on' ? 1 : 0;
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


        if ($request->save == 1)
            return redirect()->route('admin.drivers.edit', $driver->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.drivers.index')->with('success', __('msg.created_success'));
    }

    public function edit($id)
    {
        $driver = User::find($id);
        $cars_models = CarsModel::get();
        $countries = Country::where('country_id',0)->where('city_id',0)->orderBy('id', 'DESC')->get();
        return view('admin.drivers.edit',compact('driver','cars_models','countries'));
    }

    public function update(Request $request, $id)
    {
        $driver = User::find($id);
        if ($driver) {
            $request->validate([
                'email' => 'required|unique:users,email,'.$driver->id,
                'phone' => 'required|unique:users,phone,'.$driver->id
            ]);

            $driver->first_name = $request->first_name;
            $driver->id_number = $request->id_number;
            $driver->city_id = $request->city_id;
            $driver->area_id = $request->area_id;
            $driver->role_id = 2;
            $driver->country_id = $request->country_id;
            $driver->car_type = $request->car_type;
            $driver->model_id = $request->model_id;
            $driver->car_number = $request->car_number;
            $driver->car_color = $request->car_color;
            $driver->last_name = $request->last_name;
            $driver->phone = $request->phone;
            $driver->email = $request->email;
            $driver->prod_year = $request->prod_year;
            $driver->active = $request->active == 'on' ? 1 : 0;
            if ($request->password)
                $driver->password = bcrypt($request->password);
            $driver->save();

            if ($request->driver_image) {
                $old_file_id = $driver->file('driver_image')->id ?? 0;
                upload_file($request->driver_image,$old_file_id, 'driver_image', 'drivers', 'App\Models\User', $driver->id);
            }
            if ($request->id_image) {
                $old_file_id = $driver->file('id_image')->id ?? 0;
                upload_file($request->id_image,$old_file_id, 'id_image', 'drivers', 'App\Models\User', $driver->id);
            }
            if ($request->driving_license) {
                $old_file_id = $driver->file('driving_license')->id ?? 0;
                upload_file($request->driving_license,$old_file_id, 'driving_license', 'drivers', 'App\Models\User', $driver->id);
            }
            if ($request->car_image) {
                $old_file_id = $driver->file('car_image')->id ?? 0;
                upload_file($request->car_image,$old_file_id, 'car_image', 'drivers', 'App\Models\User', $driver->id);
            }
            if ($request->car_front_image) {
                $old_file_id = $driver->file('car_front_image')->id ?? 0;
                upload_file($request->car_front_image,$old_file_id, 'car_front_image', 'drivers', 'App\Models\User', $driver->id);
            }
            if ($request->car_back_image) {
                $old_file_id = $driver->file('car_back_image')->id ?? 0;
                upload_file($request->car_back_image,$old_file_id, 'car_back_image', 'drivers', 'App\Models\User', $driver->id);
            }

        }
        if ($request->save == 1)
            return redirect()->route('admin.drivers.edit', $driver->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.drivers.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request)
    {
        $driver = User::find($request->id);
        if ($driver) {
            $driver->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $driver = User::find($request->id);
        if ($driver) {
            $driver->active = $request->active == 'true' ? 1 : 0;
            $driver->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function getCountryCities(Request $request){
        $country = Country::find($request->country_id);
        $city_html = '';
        if ($country)
        {
            if (isset($country->cities) && count($country->cities) > 0){
                foreach ($country->cities as $city) {
                    $selected = $city->id == $request->city_id ? 'selected' : '';
                    $city_html .= '<option '.$selected.' value="' . $city->id . '">' . $city->name . '</option>';
                }
            }
        }

        return response()->json([
            'status' => true,
            'city_html' => $city_html,
        ]);
    }
    public function getCityArea(Request $request){
        $country = Country::find($request->city_id);
        $area_html = '';
        if ($country)
        {
            if (isset($country->areas) && count($country->areas) > 0){
                foreach ($country->areas as $area) {
                    $selected = $area->id == $request->area_id ? 'selected' : '';
                    $area_html .= '<option '.$selected.' value="' . $area->id . '">' . $area->name . '</option>';
                }
            }
        }

        return response()->json([
            'status' => true,
            'area_html' => $area_html,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountriesCitiesController extends Controller
{
    public function index()
    {
        $countries = Country::where('country_id',0)->where('city_id',0)->orderBy('id', 'DESC')->get();
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.add');
    }

    public function store(Request $request)
    {

        $country = new Country();
        $country->name_ar = $request->name_ar;
        $country->name_en = $request->name_en;
        $country->currency = $request->currency;
        $country->country_id = $request->country_id ? $request->country_id : 0;
        $country->city_id = $request->city_id ? $request->city_id : 0;
        $country->save();

        if ($request->save == 1)
            return redirect()->route('admin.countries.edit', $country->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.countries.index')->with('success', __('msg.created_success'));
    }

    public function edit($id)
    {
        $country = Country::find($id);
        return view('admin.countries.edit', compact('country'));
    }

    public function areas($id)
    {
        $city = Country::find($id);
        return view('admin.countries.areas', compact('city'));
    }

    public function update(Request $request, $id)
    {
        $country = Country::find($id);
        if ($country) {
            if ($request->name_ar)
                $country->name_ar = $request->name_ar;
            if ($request->name_en)
                $country->name_en = $request->name_en;
            if ($request->currency)
                $country->currency = $request->currency;
            if ($request->country_id)
                $country->country_id = $request->country_id ? $request->country_id : 0;
            if ($request->city_id)
                $country->city_id = $request->city_id ? $request->city_id : 0;
            $country->save();
        }
        if ($request->save == 1)
            return redirect()->route('admin.countries.edit', $country->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.countries.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request)
    {
        $country = Country::find($request->id);
        if ($country)
            $country->delete();
        return response()->json([
            'status' => true,
            'id' => $request->id,
        ]);
    }

    public function addCity(Request $request)
    {

        $country = new Country();
        $country->name_ar = $request->name_ar;
        $country->name_en = $request->name_en;
        $country->country_id = $request->country_id ? $request->country_id : 0;
        $country->save();

        $html = '<tr class="city_row_'.$country->id.'">
                             <td class="text-center">
                                 '.$country->name.'
                             </td>
                             <td class="d-flex align-items-center justify-content-sm-center">
                         <span href="#" item_id="'.$country->id.'"
                               class="item_delete mr-1 btn btn-danger">
                             <i class="la la-trash"></i></span>
                             </td>
                         </tr>';
        return response()->json([
            'status' => true,
            'html' => $html
        ]);
    }
    public function addArea(Request $request)
    {

        $country = new Country();
        $country->name_ar = $request->name_ar;
        $country->name_en = $request->name_en;
        $country->city_id = $request->city_id ? $request->city_id : 0;
        $country->save();

        $html = '<tr class="city_row_'.$country->id.'">
                             <td class="text-center">
                                 '.$country->name.'
                             </td>
                             <td class="d-flex align-items-center justify-content-sm-center">
                         <span href="#" item_id="'.$country->id.'"
                               class="item_delete mr-1 btn btn-danger">
                             <i class="la la-trash"></i></span>
                             </td>
                         </tr>';
        return response()->json([
            'status' => true,
            'html' => $html
        ]);
    }
}

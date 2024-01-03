<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function index(){
        $coupons = Coupon::orderBy('id','DESC')->get();
        return view('admin.coupons.index',compact('coupons'));
    }

    public function create(){
        return view('admin.coupons.add');
    }

    public function store(Request $request){
        $request->validate([
            'code' => 'required|unique:coupons',
            'num_use' => 'required|min:1',
            'discount' => 'required|min:0|max:100',
        ]);
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->name = $request->name;
        $coupon->num_use = $request->num_use;
        $coupon->discount = $request->discount;
        $coupon->active = $request->active == 'on' ? 1 : 0;
        $coupon->st_date = $request->st_date;
        $coupon->end_date = $request->end_date;
        $coupon->save();
        if ($request->save == 1)
            return redirect()->route('admin.coupons.edit', $coupon->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.coupons.index')->with('success', __('msg.created_success'));
    }

    public function edit($id){
        $coupon = Coupon::find($id);
        return view('admin.coupons.edit',compact('coupon'));
    }

    public function update(Request $request,$id){
        $coupon = Coupon::find($id);
        if ($coupon) {
            $request->validate([
                'code' => 'required|unique:coupons,code,'.$coupon->id,
                'num_use' => 'required|min:1',
                'discount' => 'required|min:0|max:100',
            ]);
            $coupon->code = $request->code;
            $coupon->name = $request->name;
            $coupon->num_use = $request->num_use;
            $coupon->discount = $request->discount;
            $coupon->active = $request->active == 'on' ? 1 : 0;
            $coupon->st_date = $request->st_date;
            $coupon->end_date = $request->end_date;
            $coupon->save();
        }
        if ($request->save == 1)
            return redirect()->route('admin.coupons.edit', $coupon->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.coupons.index')->with('success', __('msg.created_success'));
    }


    public function delete(Request $request){
        $coupon = Coupon::find($request->id);
        if ($coupon){
            $coupon->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}

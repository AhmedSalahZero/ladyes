<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Settings;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request){
       $status_id = $request->get('status_id');
       $user_id = $request->get('user_id');
       $driver_id = $request->get('driver_id');
       $from_date = $request->get('from_date');
       $to_date = $request->get('to_date');
        $orders = Order::orderBy('id','DESC');
        if ($status_id)
            $orders = $orders->where('status_id',$status_id);
        if ($user_id)
            $orders = $orders->where('user_id',$user_id);
        if ($driver_id)
            $orders = $orders->where('driver_id',$driver_id);
        if ($from_date && $to_date)
            $orders = $orders->whereBetween('created_at',[$from_date,$to_date]);

        $orders = $orders->get();

        $drivers = User::where('role_id',2)->get();
        $users = User::get();
        return view('admin.orders.index',compact('orders','drivers','users'));
    }

    public function show($id){
        $order = Order::find($id);
        $setting = Settings::first();
        return view('admin.orders.show',compact('order','setting'));
    }
    public function delete(Request $request){
        $order = Order::find($request->id);
        if ($order){
            $order->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function updateStatus(Request $request){
        $order = Order::find($request->order_id);
        if ($order){
            $order->status_id = $request->status_id;
            $order->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
    public function sendUserEmail(Request $request){
        $order = Order::find($request->order_id);
        if ($order){
            $email = isset($order->user) ? $order->user->email : $order->email;
            $setting = Settings::first();
//            Mail::send(['html' => 'emails.order_invoice'], ['order' => $order ,'setting' => $setting], function ($message) use ($email) {
//                $message->from('no-replay@tryfateen.com', 'tryfateen.com');
//                $message->subject('طلب جديد من فطين');
//                $message->to($email);
//            });
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

}

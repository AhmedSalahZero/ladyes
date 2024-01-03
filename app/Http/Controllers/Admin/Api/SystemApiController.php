<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\AdditionService;
use App\Models\CarsModel;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Onbording;
use App\Models\Order;
use App\Models\Reason;
use App\Models\Settings;
use App\Models\Slider;
use App\Models\Transaction;
use App\Models\TravelTraking;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserNotifacation;
use App\Models\UsersCard;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class SystemApiController extends Controller
{
    use ApiTrait;

    var $user;
    var $c_date;
    var $lang;
    var $setting;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->lang = \request('lang') ? \request('lang') : 'ar';
        $this->c_date = date('Y-m-d H:i:s');
        $this->setting = Settings::first();
    }

    public function updateUserProfile(Request $request)
    {

        $user = $this->user;

        if (!$user)
            return $this->errorResponse(__('msg.user_not_found'), 401);

        if ($request->email)
            $validator = Validator::make($request->all(), [
                'email' => 'unique:users,email,' . $this->user->email,
            ]);

        if ($request->phone)
            $validator = Validator::make($request->all(), [
                'phone' => 'unique:users,phone,' . $this->user->phone,
            ]);
        if (isset($validator) && $validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = $image->hashName();
            $image->move(public_path('/uploads/users/'), $image_name);

            $filePath = "/uploads/users/" . $image_name;
        }
        if ($request->first_name)
            $user->first_name = $request->first_name;
        if ($request->last_name)
            $user->last_name = $request->last_name;
        if ($request->phone)
            $user->phone = $request->phone;
        if ($request->email)
            $user->email = $request->email;
        if ($request->birth_date)
            $user->birth_date = $request->birth_date;
        if ($request->addition_services)
            $user->addition_services = $request->addition_services;
        if (isset($filePath) && $filePath)
            $user->image = $filePath;
        $user->save();


        $data = [
            'id' => $user->id,
            'first_name' => $user->first_name ?? '',
            'last_name' => $user->last_name ?? '',
            'phone' => $user->phone ?? '',
            'email' => $user->email ?? '',
            'tax_number' => $user->tax_number ?? '',
            'birth_date' => $user->birth_date ?? '',
            'image' => $user->image ? asset('public' . $user->image) : '',
            'addition_services' => $user->addition_services_list($this->lang),
        ];
        return $this->dataResponse('Data get success', $data, 200);


    }


    public function checkVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);

        $user = User::where('phone', $request->phone)->first();
        if (!$user)
            return $this->errorResponse(__('msg.user_not_found'), 401);

        if ($request->verification_code != $user->verification_code)
            return $this->errorResponse(__('msg.code_error'), 401);
        $token = auth()->login($user);
        if ($user->role_id == 1) {
            $user->is_verify_sms = 1;
            $user->save();
        }
        $data = [
            'token' => $token,
            'user' => $user->role_id == 1 ? user_data() : driver_data()
        ];
        return $this->dataResponse(__('msg.activated_successfully'), $data, 200);
    }

    public function resendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);

        $user = User::where('phone', $request->phone)->first();
        if (!$user)
            return $this->errorResponse(__('msg.user_not_found'), 401);

        $user->verification_code = rand(1111, 9999);
        $user->save();

        $msg = ' رمز التحقق الخاص بك هو ' . $user->verification_code;
        send_sms($msg, $user->phone);

        return $this->successResponse(__('msg.code_sent_success'), 200);
    }

    public function userData()
    {
        $user = $this->user;
        $data = [
            'id' => $user->id,
            'first_name' => $user->first_name ?? '',
            'last_name' => $user->last_name ?? '',
            'phone' => $user->phone ?? '',
            'email' => $user->email ?? '',
            'tax_number' => $user->tax_number ?? '',
            'birth_date' => $user->birth_date ?? '',
            'balance' => number_format($user->balance,2),
            'expenses' => number_format($user->expenses,2),
            'image' => $user->image ? asset('public' . $user->image) : '',
            'addition_services' => $user->addition_services_list($this->lang),
        ];
        return $this->dataResponse('Data get success', $data, 200);
    }

    public function driverData()
    {
        $user = $this->user;
        $data = [
            'id' => $user->id,
            'first_name' => $user->first_name ?? '',
            'last_name' => $user->last_name ?? '',
            'id_number' => $user->id_number ?? '',
            'car_type' => $user->car_type ?? '',
            'car_number' => $user->car_number ?? '',
            'car_color' => $user->car_color ?? '',
            'phone' => $user->phone ?? '',
            'email' => $user->email ?? '',
            'driver_image' => $user->file('driver_image') ? asset('public' . $user->file('driver_image')->file_path) : '',
            'id_image' => $user->file('id_image') ? asset('public' . $user->file('id_image')->file_path) : '',
            'driving_license' => $user->file('driving_license') ? asset('public' . $user->file('driving_license')->file_path) : '',
            'car_image' => $user->file('car_image') ? asset('public' . $user->file('car_image')->file_path) : '',
            'car_front_image' => $user->file('car_front_image') ? asset('public' . $user->file('car_front_image')->file_path) : '',
            'car_back_image' => $user->file('car_back_image') ? asset('public' . $user->file('car_back_image')->file_path) : '',
            'country' => isset($user->country) ? $user->country : null,
            'city' => isset($user->city) ? $user->city : null,
            'area' => isset($user->area) ? $user->area : null,
            'car_model_data' => isset($user->car_model_data) ? $user->car_model_data : null,
        ];
        return $this->dataResponse('Data get success', $data, 200);
    }

    public function changeUserPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
            'new_password_conformation' => 'required',
        ]);
        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);

        if (!(Hash::check($request->current_password, $this->user->password)))
            return $this->errorResponse(__('msg.pass_not_match'), 401);

        if ($request->current_password == $request->new_password)
            return $this->errorResponse(__('msg.pass_equal_cu_pas'), 401);

        if ($request->new_password != $request->new_password_conformation)
            return $this->errorResponse(__('msg.pass_not_equal_cu_pas_conformation'), 401);


        $user = $this->user;
        $user->password = bcrypt($request->new_password);
        $user->save();

        return $this->successResponse(__('msg.pass_changed_success'));
    }

    public function settings()
    {
        $setting = $this->setting;
        $data = [
            'logo' => $setting->logo ? asset('public' . $setting->logo) : '',
            'phone' => $setting->phone ?? '',
            'address' => $setting->address ?? '',
            'email' => $setting->email ?? '',
            'whatsapp' => $setting->whatsapp ?? '',
            'facebook' => $setting->facebook ?? '',
            'instagram' => $setting->instagram ?? '',
            'twitter' => $setting->twitter ?? '',
            'vat' => $setting->vat ?? '',
            'instructions' => $setting->api_instructions($this->lang) ?? '',
            'economic_price' => (float)$setting->economic_price ?? '',
            'project_price' => (float)$setting->project_price ?? '',
            'class_price' => $setting->class_price ?? '',
            'family_price' => (float)$setting->family_price ?? '',
            'skip_time_price' => (float)$setting->skip_time_price ?? '',
            'profit_rate' => (float)$setting->profit_rate ?? '',
            'user_cancel_travel_penalty' => (float)$setting->user_cancel_travel_penalty ?? '',
            'driver_cancel_travel_penalty' => (float)$setting->driver_cancel_travel_penalty ?? '',
        ];

        return $this->dataResponse('Data found successfully', $data, 200);
    }


    public function sliders()
    {
        $sliders = Slider::Active()->get();

        $data = $sliders->map(function ($slider) {
            return [
                'id' => $slider->id,
                'image' => $slider->image ? asset('/public' . $slider->image) : '',
                'title' => $slider->api_title($this->lang) ?? '',
            ];
        });

        return $this->dataResponse('Slider get success', $data);
    }

    public function inBordingData()
    {
        $in_bording = Onbording::Active()->get();

        $data = $in_bording->map(function ($bording) {
            return [
                'id' => $bording->id,
                'image' => $bording->image ? asset('/public' . $bording->image) : '',
                'title' => $bording->api_title($this->lang) ?? '',
                'desc' => $bording->api_desc($this->lang) ?? '',
            ];
        });

        return $this->dataResponse('Onbording get success', $data);
    }

    public function getCountries()
    {
        $list = Country::where('country_id', 0)
            ->where('city_id', 0)->get();
        $data = $list->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->api_name($this->lang) ?? '',
            ];
        });

        return $this->dataResponse('Data get success', $data);
    }

    public function countryCities(Request $request)
    {
        $list = Country::where('country_id', $request->country_id)->get();
        $data = $list->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->api_name($this->lang) ?? '',
            ];
        });

        return $this->dataResponse('Data get success', $data);
    }

    public function cityAreas(Request $request)
    {
        $list = Country::where('city_id', $request->city_id)->get();
        $data = $list->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->api_name($this->lang) ?? '',
            ];
        });

        return $this->dataResponse('Data get success', $data);
    }

    public function carsModels(Request $request)
    {
        $list = CarsModel::Active()->get();
        $data = $list->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->api_name($this->lang) ?? '',
            ];
        });

        return $this->dataResponse('Data get success', $data);
    }

    public function additionServices()
    {
        $services = AdditionService::Active()->get();
        $data = $services->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->api_name($this->lang) ?? '',
                'price' => $item->price
            ];
        });

        return $this->dataResponse('Data get success', $data);

    }

    #################### address #################3
    public function getAddress()
    {
        $address = UserAddress::where('user_id', $this->user->id)->get();
        $data = $address->map(function ($item) {
            return [
                'id' => $item->id,
                'lat' => $item->lat ?? '',
                'lng' => $item->lng ?? '',
                'type' => $item->type ?? '',
                'full_address' => $item->full_address ?? '',
                'created_at' => $item->created_at ?? '',
            ];
        });

        return $this->dataResponse('Data get success', $data);
    }

    public function createAddress(Request $request)
    {
        $address = UserAddress::where('user_id', $this->user->id)->where('id', $request->address_id)->first();
        if (!$address)
            $address = new UserAddress();
        $address->user_id = $this->user->id;
        if ($request->lat)
            $address->lat = $request->lat;
        if ($request->lng)
            $address->lng = $request->lng;
        if ($request->type)
            $address->type = $request->type;
        if ($request->full_address)
            $address->full_address = $request->full_address;
        $address->save();

        return $this->successResponse(__('data_saved_success', [], $this->lang));
    }

    ##################### order ####################

    public function applyCoupon(Request $request)
    {
        $c_date = date('Y-m-d');
        $coupon = Coupon::Active()->where('code', $request->code)
            ->where('st_date', '<=', $c_date)
            ->where('end_date', '>=', $c_date)->first();
        if (!$coupon)
            return $this->errorResponse('لم يتم العثور على كود الخصم');
        $orderd_coupon = Order::where('coupon_id', $coupon->id)->count();
        if ($orderd_coupon >= $coupon->num_use)
            return $this->errorResponse('تم الوصول الى اعلى حد لاستخدام كوبون الخصم');


        return $this->dataResponse('تم تطبيق الخصم بنجاح', $coupon);
    }

    public function genrateOrderCode()
    {
        do {
            $code = rand(111111, 999999);
            $data = Order::where('code', $code)->first();
            if (!$data) return $code;
        } while (true);
    }

    public function genrateTransactionCode()
    {
        do {
            $code = rand(111111, 999999);
            $data = Transaction::where('code', $code)->first();
            if (!$data) return $code;
        } while (true);
    }


    public function makeTransaction(Request $request)
    {

        if ($request->type_id == 1) {
            $directData = $this->myfatorah_payment($request);
            if (isset($directData->Status) && $directData->Status == 'SUCCESS') {
                $transaction = new Transaction();
                $transaction->user_id = $this->user->id;
                $transaction->code = $this->genrateTransactionCode();
                $transaction->type_id = 1;
                $transaction->is_payment = 1;
                $transaction->price = $request->price;
                $transaction->payment = $directData->CardInfo->Brand;
                $transaction->payment_info = json_encode($directData);
                $transaction->save();
                return $this->dataResponse('payment success', $transaction);
            } else {
                return $this->errorResponse('payment failed');
            }
        } elseif ($request->type_id == 2) {
            $directData = $this->myfatorah_payment($request);
            if (isset($directData->Status) && $directData->Status == 'SUCCESS') {
                $transaction = new Transaction();
                $transaction->user_id = $this->user->id;
                $transaction->code = $this->genrateTransactionCode();
                $transaction->type_id = 2;
                $transaction->is_payment = 1;
                $transaction->price = $request->price;
                $transaction->payment = $directData->CardInfo->Brand;
                $transaction->payment_info = json_encode($directData);
                $transaction->save();
                return $this->dataResponse('payment success', $transaction);
            } else {
                return $this->errorResponse('payment failed');
            }
        } elseif ($request->type_id == 3) {
            if ($this->user->balance <= $request->price)
                return $this->errorResponse('ليس لديك رصيد كافي لاتمام العملية');
            $transaction = new Transaction();
            $transaction->user_id = $this->user->id;
            $transaction->code = $this->genrateTransactionCode();
            $transaction->type_id = 3;
            $transaction->is_payment = 1;
            $transaction->price = $request->price;
            $transaction->save();
            return $this->dataResponse('payment success', $transaction);
        } elseif ($request->type_id == 4) {
            $transaction = new Transaction();
            $transaction->user_id = $this->user->id;
            $transaction->code = $this->genrateTransactionCode();
            $transaction->type_id = 4;
            $transaction->is_payment = 1;
            $transaction->price = $request->price;
            $transaction->save();
            return $this->dataResponse('payment success', $transaction);
        } elseif ($request->type_id == 5) {
            $transaction = new Transaction();
            $transaction->user_id = $this->user->id;
            $transaction->code = $this->genrateTransactionCode();
            $transaction->type_id = 5;
            $transaction->is_payment = 1;
            $transaction->price = $request->price;
            $transaction->save();
            return $this->dataResponse('payment success', $transaction);
        }else{
            return $this->errorResponse('خطأ غير معروف');
        }
    }

    public function createOrder(Request $request)
    {
        $setting = Settings::first();

        $address = UserAddress::find($request->address_id);

        $order = new Order();
        $order->code = $this->genrateOrderCode();
        $order->user_id = $this->user->id;
        $order->status_id = 1;
        $order->address_id = $address ? $address->id : 0;
        $order->travel_type_id = $request->travel_type_id;
        if ($request->coupon_id > 0 && $request->coupon_discount > 0) {
            $order->coupon_code = $request->coupon_code;
            $order->coupon_discount = $request->coupon_discount;
            $order->coupon_id = $request->coupon_id;
        }
        $order->price = $request->price;
        $order->meters = $request->meters;
        $order->to_lat = $request->to_lat;
        $order->to_lng = $request->to_lng;
        $order->from_lat = $address ? $address->lat : $request->from_lat;
        $order->from_lng = $address ? $address->lng : $request->from_lng;
        $order->penalty_price = $request->penalty_price ?? 0;
        $order->profit_rate = $setting->profit_rate;
        $order->vat = $setting->vat;
        $order->save();

        if ($request->transaction_id){
            $transaction = Transaction::find($request->transaction_id);
            if ($transaction){
                $transaction->order_id = $order->id;
                $transaction->save();
            }
        }

        return $this->successResponse('Order created success');
    }

    public function trackOrder(Request $request){
        $trak = new TravelTraking();
        $trak->user_id = $this->user->id;
        $trak->order_id = $request->order_id;
        $trak->lat = $request->lat;
        $trak->lng = $request->lng;
        $trak->save();

        return $this->successResponse('Track add success');
    }
    public function userOrders(Request $request){
        $orders = Order::where('user_id',$this->user->id);
        if ($request->status_id)
            $orders = $orders->where('status_id',$request->status_id);

        $orders = $orders->orderBy('id','DESC')->get();

        $data = $orders->map(function ($order){
           return [
              'id' =>  $order->id,
              'code' =>  $order->code,
              'status_id' =>  $order->status_id,
              'status_name' =>  $order->status_name,
              'from_lat' =>  $order->from_lat,
              'from_lng' =>  $order->from_lng,
               'to_lat' =>  $order->to_lat,
              'to_lng' =>  $order->to_lng,
              'sub_total' =>  number_format($order->sub_total,2),
              'vat_price' =>  number_format($order->vat_price,2),
              'penalty_price' =>  number_format($order->penalty_price,2),
              'discount_price' =>  number_format($order->discount_price,2),
              'total' =>  number_format($order->total,2),
              'meters' =>  $order->meters,
              'travel_type_id' =>  $order->travel_type_id,
              'travel_type_name' =>  $order->travel_type_name,
              'driver_name' =>  $order->driver->name ?? '',
              'created_at' =>  date('d/m/Y H:i',strtotime($order->created_at)),
           ] ;
        });

        return $this->dataResponse('Data get success', $data);
    }

    public function getOrderDetails(Request $request){
        $order = Order::find($request->id);
        if (!$order)
            return $this->errorResponse('Order not found');
        $data = [
            'id' =>  $order->id,
            'code' =>  $order->code,
            'status_id' =>  $order->status_id,
            'status_name' =>  $order->status_name,
            'from_lat' =>  $order->from_lat,
            'from_lng' =>  $order->from_lng,
            'to_lat' =>  $order->to_lat,
            'to_lng' =>  $order->to_lng,
            'sub_total' =>  number_format($order->sub_total,2),
            'vat_price' =>  number_format($order->vat_price,2),
            'penalty_price' =>  number_format($order->penalty_price,2),
            'discount_price' =>  number_format($order->discount_price,2),
            'total' =>  number_format($order->total,2),
            'vat' =>  number_format($order->vat,2),
            'coupon_code' =>  number_format($order->coupon_code,2),
            'coupon_discount' =>  number_format($order->coupon_discount,2),
            'meters' =>  $order->meters,
            'travel_type_id' =>  $order->travel_type_id,
            'travel_type_name' =>  $order->travel_type_name,
            'driver_name' =>  $order->driver->name ?? '',
            'created_at' =>  date('d/m/Y H:i',strtotime($order->created_at)),
            'address' => isset($order->address) ? $order->address : null,
            'transaction' => isset($order->transaction) ? [
                'id' => $order->transaction->id,
                'code' => $order->transaction->code,
                'price' => $order->transaction->price,
                'payment' => $order->transaction->payment,
                'payment_info' => $order->transaction->payment_info ? json_decode($order->transaction->payment_info) : [],
            ] : [],
            'travel_tracking' => isset($order->travel_traking) && count($order->travel_traking) > 0 ? $order->travel_traking->map(function ($trak){
                return [
                  'id' => $trak->id,
                  'lat' => $trak->lat,
                  'lng' => $trak->lng,
                ];
            }) : [],
        ];

        return $this->dataResponse('Data get success', $data);
    }

    public function changeOrderStatus(Request $request){
        $order = Order::find($request->order_id);
        if (!$order)
            return $this->errorResponse('Order not found');
        if ($request->status_id == 5){
            $setting = Settings::first();
            $penalty_price = $this->user->role_id == 1 ? $order->total * $setting->user_cancel_travel_penalty/100 : ($this->user->role_id == 2 ? $order->total * $setting->driver_cancel_travel_penalty/100 : 0);
            $transaction = new Transaction();
            $transaction->user_id = $this->user->id;
            $transaction->code = $this->genrateTransactionCode();
            $transaction->type_id = 5;
            $transaction->is_payment = 1;
            $transaction->price = $penalty_price;
            $transaction->save();

            $order->reason_id = $request->reason_id;
            $order->save();

        }elseif ($request->status_id == 4){
            $order->complete_date = date('Y-m-d H:i:s');
            $order->save();
        }elseif ($request->status_id == 2){
            $order->approve_date = date('Y-m-d H:i:s');
            $order->save();
        }elseif ($request->status_id == 3){
            $order->start_travel_date = date('Y-m-d H:i:s');
            $order->driver_id = $this->user->id;
            $order->save();
        }

        $notify = new UserNotifacation();
        $notify->user_id = $order->user_id;
        $notify->title = 'تغيير في حالة الرحلة';
        $notify->text = $order->status_name.'تم تغيير حالة الرحلة الى ';
        $notify->save();

        if (isset($order->user) && $order->user->fcm_token)
            sendToFcm($order->user->fcm_token,$notify->text,$notify->title);

        $notify = new UserNotifacation();
        $notify->user_id = $order->driver_id;
        $notify->title = 'تغيير في حالة الرحلة';
        $notify->text = $order->status_name.'تم تغيير حالة الرحلة الى ';
        $notify->save();
        if (isset($order->driver) && $order->driver->fcm_token)
            sendToFcm($order->driver->fcm_token,$notify->text,$notify->title);

        return $this->successResponse('Data save success');
    }
    #################### cards #######################333
    public function userCards()
    {
        $user_cards = UsersCard::where('user_id', $this->user->id)->orderBy('id', 'DESC')->get();
        $data = $user_cards->map(function ($card) {
            return [
                'id' => $card->id,
                'ex_month' => $card->ex_month,
                'ex_year' => $card->ex_year,
                'holder_name' => $card->holder_name,
                'last_4number' => $card->last_4number,
                'is_default' => $card->is_default,
                'brand' => $card->brand,
                'created_at' => $card->created_at,
            ];
        });
        return $this->dataResponse('Card get success', $data, 200);
    }

    public function addUserCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required',
            'ex_month' => 'required',
            'ex_year' => 'required',
            'cvv' => 'required',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 400);

        $card_num = $request->card_number;
        $user_card = UsersCard::where('user_id', $this->user->id)->where('last_4number', substr($card_num, -4))->first();
        $brand = substr($card_num, 0, 1) == '5' ? 'Master card' : 'Visa';

        if ($request->is_default == 1)
            UsersCard::where('user_id', $this->user->id)->update(['is_default' => 0]);
        if (!$user_card)
            $user_card = new UsersCard();
        $user_card->card_number = encrypt($request->card_number);
        $user_card->ex_month = $request->ex_month;
        $user_card->ex_year = $request->ex_year;
        $user_card->cvv = $request->cvv;
        $user_card->brand = $brand;
        $user_card->holder_name = $this->user->name;
        $user_card->last_4number = substr($card_num, -4);
        $user_card->is_default = $request->is_default == 1 ? 1 : 0;
        $user_card->user_id = $this->user->id;
        $user_card->save();

        return $this->successResponse('Card saved success', 200);
    }

    public function deleteCard(Request $request)
    {
        $user_card = UsersCard::where('user_id', $this->user->id)->where('id', $request->id)->first();

        if (!$user_card)
            return $this->errorResponse('Card not found', 400);

        $user_card->delete();

        return $this->successResponse('Card deleted success', 200);
    }

    public function getNotifacationList(){
        $notifications = UserNotifacation::where('user_id',$this->user->id)->orderBy('id','DESC')->get();
        $data = $notifications->map(function ($notify){
           return [
              'id' => $notify->id,
              'title' => $notify->title,
              'text' => $notify->text,
              'created_at' => date('d/m/Y H:i',strtotime($notify->created_at)),
           ] ;
        });

        return $this->dataResponse('Data get success', $data, 200);
    }

    public function reasonsList(){
        $reasons = Reason::get();
        $data = $reasons->map(function ($item){
           return [
              'id' => $item->id,
              'title' => $item->api_title($this->lang),
           ] ;
        });

        return $this->dataResponse('Data get success', $data, 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Auth;
use Carbon\Carbon;
use Validator;
use Session;
use DB;
use JWTAuth;

class ApiUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' =>
            ['']
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderListUser()
    {
        try{
            $user = $this->guard()->user();
            $orders =Order::where('user_id',$user->id)->get(['cart','method',
                'pickup_location','pay_amount','order_number','payment_status',
                'coupon_code','coupon_discount','shipping_address']);

            foreach ($orders as $key=>$value){
                $purchase_item = unserialize(bzdecompress(utf8_decode($value->cart)));
                $orders[$key]->ordered_products = $purchase_item;
                unset($orders[$key]->cart);
            }
            return response()->json([$orders]);
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }

    }

    public function coupon(){
        $coupon = Coupon::where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->where('status',1)
            ->get(['code','type','price','times']);

        return response()->json($coupon);
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }


}
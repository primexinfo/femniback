<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Product;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Validator;
use Session;
use DB;
use JWTAuth;


class ApiOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' =>
            ['']
        ]);
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

    public function store(Request $request){

        //--- Validation Section
        $rules = [
            'ordered_products.*.product_id' => 'required',
            'ordered_products.*.product_name' => 'required',
            'ordered_products.*.product_quantity' => 'required',
            'user_id' => 'required',
            'pay_amount' => 'required',
            'payment_mathod' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'customer_address' => 'required',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        try{

            $order_number = "T&T".str_random(3).time();

            DB::transaction(function () use ($request,$order_number) {

                $order = new Order();
                $order['user_id'] = $request->user_id;
                $order['cart'] = utf8_encode(bzcompress(serialize($request->ordered_products), 9));
                $order['totalQty'] = $request->totalQty;
                $order['pay_amount'] = round($request->pay_amount );
                $order['method'] = $request->payment_mathod;
                $order['shipping_address'] = $request->shipping_address;
                $order['shipping_phone'] = $request->shipping_phone;
                $order['shipping_email'] = $request->shipping_email;
                $order['shipping_city'] = $request->shipping_city;
                $order['shipping_zip'] = $request->shipping_zip;
                $order['shipping_name'] = $request->shipping_name;
                $order['shipping_cost'] = $request->shipping_cost ?? 0;
                $order['pickup_location'] = $request->pickup_location;
                $order['customer_email'] = $request->customer_email;
                $order['customer_name'] = $request->customer_name;
                $order['customer_phone'] = $request->customer_phone;
                $order['customer_address'] = $request->customer_address;
                $order['customer_city'] = $request->customer_city;
                $order['customer_zip'] = $request->customer_zip;
                $order['order_number'] = $order_number;
                $order['coupon_code'] = $request->coupon_code;
                $order['coupon_discount'] = $request->coupon_discount;
                $order['payment_status'] = "Pending";
                $curr = Currency::first();
                $order['currency_sign'] = $curr->sign;
                $order['currency_value'] = $curr->value;
                $order['tax'] = $request->tax ?? 0;
                $order['order_note'] = $request->order_note;

                $order->save();

                $track = new OrderTrack();
                $track->title = 'Pending';
                $track->text = 'You have successfully placed your order.';
                $track->order_id = $order->id;
                $track->save();

                $notification = new Notification();
                $notification->order_id = $order->id;
                $notification->save();

                if($request->coupon_id != "")
                {
                    $coupon = Coupon::findOrFail($request->coupon_id);
                    $coupon->used++;
                    if($coupon->times != null)
                    {
                        $i = (int)$coupon->times;
                        $i--;
                        $coupon->times = (string)$i;
                    }
                    $coupon->update();
                }

                foreach($request->ordered_products as $prod)
                {
                    $x = (int)$prod['product_quantity'];
                    $product = Product::findOrFail($prod['product_id']);
                    if(!empty($prod['product_size']))
                    {
                        $temp = $product->size_qty;
                        $size = $product->size;
                        $size_index = array_search($prod['product_size'],$size);
                        $temp[$size_index] = $temp[$size_index] - $x;
                        $temp1 = implode(',', $temp);
                        $product->size_qty =  $temp1;
                        $product->update();
                    }
                    if(empty($prod['product_size']))
                    {
                        $product->stock = $product->stock -$prod['product_quantity'];
                        $product->update();
                    }
                }


            }, 2);

            return response()->json(['status' => 'success','invoice'=>$order_number], 200);
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());

        }
    }

}
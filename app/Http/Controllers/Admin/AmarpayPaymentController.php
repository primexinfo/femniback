<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Classes\GeniusMailer;

class AmarpayPaymentController extends Controller
{
    public function paymentSuccessOrFailed(Request $request){
        if($request->get('pay_status') == 'Failed') {
            return view('load.aamarPayFailed');
        }
        else{

            $amarpay = Session::get('amarpay');
            if (Session::has('currency'))
            {
                $curr = Currency::find(Session::get('currency'));
            }
            else
            {
                $curr = Currency::where('is_default','=',1)->first();
            }
            $gs = Generalsetting::findOrFail(1);
            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);
            foreach($cart->items as $key => $prod)
            {
                if(!empty($prod['item']['license']) && !empty($prod['item']['license_qty']))
                {
                    foreach($prod['item']['license_qty']as $ttl => $dtl)
                    {
                        if($dtl != 0)
                        {
                            $dtl--;
                            $produc = Product::findOrFail($prod['item']['id']);
                            $temp = $produc->license_qty;
                            $temp[$ttl] = $dtl;
                            $final = implode(',', $temp);
                            $produc->license_qty = $final;
                            $produc->update();
                            $temp =  $produc->license;
                            $license = $temp[$ttl];
                            $oldCart = Session::has('cart') ? Session::get('cart') : null;
                            $cart = new Cart($oldCart);
                            $cart->updateLicense($prod['item']['id'],$license);
                            Session::put('cart',$cart);
                            break;
                        }
                    }
                }
            }
            $order = new Order;
            $success_url = action('Front\PaymentController@payreturn');
            $item_name = $gs->title." Order";
            $item_number = str_random(4).time();
            $order['user_id'] = $amarpay['user_id'];
            $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9));
            $order['totalQty'] = $amarpay['totalQty'];
            $order['pay_amount'] = round($amarpay['total'] / $curr->value, 2) + $amarpay['shipping_cost'] + $amarpay['packing_cost'];
            $order['method'] = 'aamarPay'.','.$request->card_type;
            $order['shipping'] = $amarpay['shipping'];
            $order['pickup_location'] = $amarpay['pickup_location'];
            $order['customer_email'] = $amarpay['email'];
            $order['customer_name'] = $amarpay['name'];
            $order['shipping_cost'] = $amarpay['shipping_cost'];
            $order['packing_cost'] = $amarpay['packing_cost'];
            $order['tax'] = $amarpay['tax'];
            $order['txnid'] = $request->epw_txnid;
            $order['customer_phone'] = $amarpay['phone'];
            $order['order_number'] = 'AGR'.str_random(2).time();
            $order['customer_address'] = $amarpay['address'];
            // $order['customer_country'] = $amarpay['customer_country'];
            $order['customer_city'] = $amarpay['city'];
            $order['customer_zip'] = $amarpay['zip'];
            $order['shipping_email'] = $amarpay['shipping_email'];
            $order['shipping_name'] = $amarpay['shipping_name'];
            $order['shipping_phone'] = $amarpay['shipping_phone'];
            $order['shipping_address'] = $amarpay['shipping_address'];
            // $order['shipping_country'] = $amarpay['shipping_country'];
            $order['shipping_city'] = $amarpay['shipping_city'];
            $order['shipping_zip'] = $amarpay['shipping_zip'];
            $order['order_note'] = $amarpay['order_notes'];
            $order['coupon_code'] = $amarpay['coupon_code'];
            $order['coupon_discount'] = $amarpay['coupon_discount'];
            $order['dp'] = $amarpay['dp'];
            $order['payment_status'] = "Complete";
            $order['currency_sign'] = $curr->sign;
            $order['currency_value'] = $curr->value;
            $order['vendor_shipping_id'] = $amarpay['vendor_shipping_id'];
            $order['vendor_packing_id'] = $amarpay['vendor_packing_id'];

            if (Session::has('affilate'))
            {
                $val = $amarpay['total'] / 100;
                $sub = $val * $gs->affilate_charge;
                $user = User::findOrFail(Session::get('affilate'));
                $user->affilate_income += $sub;
                $user->update();
                $order['affilate_user'] = $user->name;
                $order['affilate_charge'] = $sub;
            }
            $order->save();

            $track = new OrderTrack;
            $track->title = 'Pending';
            $track->text = 'You have successfully placed your order.';
            $track->order_id = $order->id;
            $track->save();

            $notification = new Notification;
            $notification->order_id = $order->id;
            $notification->save();
            if($amarpay['coupon_id'] != "")
            {
                $coupon = Coupon::findOrFail($amarpay['coupon_id']);
                $coupon->used++;
                if($coupon->times != null)
                {
                    $i = (int)$coupon->times;
                    $i--;
                    $coupon->times = (string)$i;
                }
                $coupon->update();

            }

            foreach($cart->items as $prod)
            {
                $x = (string)$prod['size_qty'];
                if(!empty($x))
                {
                    $product = Product::findOrFail($prod['item']['id']);
                    $x = (int)$x;
                    $x = $x - $prod['qty'];
                    $temp = $product->size_qty;
                    $temp[$prod['size_key']] = $x;
                    $temp1 = implode(',', $temp);
                    $product->size_qty =  $temp1;
                    $product->update();
                }
            }

            foreach($cart->items as $prod)
            {
                $x = (string)$prod['stock'];
                if($x != null)
                {

                    $product = Product::findOrFail($prod['item']['id']);
                    $product->stock =  $prod['stock'];
                    $product->update();
                    if($product->stock <= 5)
                    {
                        $notification = new Notification;
                        $notification->product_id = $product->id;
                        $notification->save();
                    }
                }
            }


            Session::put('temporder',$order);
            Session::put('tempcart',$cart);

            Session::forget('cart');

            Session::forget('already');
            Session::forget('coupon');
            Session::forget('coupon_total');
            Session::forget('coupon_total1');
            Session::forget('coupon_percentage');

            //Sending Email To Buyer

        if($gs->is_smtp == 1)
        {
            $data = [
                'to' => $amarpay['email'],
                'type' => "new_order",
                'cname' => $amarpay['name'],
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'wtitle' => "",
                'onumber' => $order->order_number,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendAutoOrderMail($data,$order->id);
        }
        else
        {
            $to = $amarpay['email'];
            $subject = "Your Order Placed!!";
            $msg = "Hello ".$amarpay['name']."!\nYou have placed a new order.\nYour order number is ".$order->order_number.".Please wait for your delivery. \nThank you.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($to,$subject,$msg,$headers);
        }
            //Sending Email To Admin
        if($gs->is_smtp == 1)
        {
            $data = [
                'to' => $gs->email,
                'subject' => "New Order Recieved!!",
                'body' => "Hello Admin!<br>Your store has received a new order.<br>Order Number is ".$order->order_number.".Please login to your panel to check. <br>Thank you.",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        }
        else
        {
            $to = $gs->email;
            $subject = "New Order Recieved!!";
            $msg = "Hello Admin!\nYour store has recieved a new order.\nOrder Number is ".$order->order_number.".Please login to your panel to check. \nThank you.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($to,$subject,$msg,$headers);
        }
            return redirect($success_url);

        }

    }
}

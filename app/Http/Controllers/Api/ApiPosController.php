<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Inventory;
use App\Models\PosSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;
use DB;


class ApiPosController extends Controller

{
    public function login(Request $request){
        //--- Validation Section
        $rules = [
            'email'   => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        // Attempt to log the user in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // if successful, then redirect to their intended location
            if(Auth::guard('admin')->user()->role == 'posAdmin' || Auth::guard('admin')->user()->role == 'warehouseAdmin' || Auth::guard('admin')->user()->role == 'Administrator'){
                return response()->json(Auth::guard('admin')->user());
            }
            else{
                Auth::guard('admin')->logout();
                return response()->json(array('errors' => [ 0 => 'You have no access to login here' ]));
            }
        }

        // if unsuccessful, then redirect back to the login with the form data
        return response()->json(array('errors' => [ 0 => 'Credentials Doesn\'t Match !' ]));
    }

    public function productInitialPos(Request $request){
        //--- Validation Section
        $rules = [
            'warehouse_id'      => 'required',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        try{
            $products = Product::orderBy('id','desc')->where('type','!=','package')->take(30)->get(
                ['id','name','photo','thumbnail','price','previous_price',]
            );

            foreach ($products as $key=>$value){
                $inventory = Inventory::where('warehouse_id',$request->warehouse_id)->where('product_id',$value->id)->first();
                $products[$key]->stock = $inventory->stock ?? 0;
            }
            return response()->json($products);
        }
        catch (\Exception $exception){
            return response()->json("Not found");
        }
        //--- Logic Section Ends

    }

    public function productSearchPos(Request $request){

        //--- Validation Section
        $rules = [
            'warehouse_id'      => 'required',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        try{
            $products = Product::orWhere('name','LIKE',"%{$request->name}%")
                ->orWhere('id','LIKE',"%{$request->name}%")
                ->get(['id','name','photo','thumbnail','price','previous_price']);

            foreach ($products as $key=>$value){
                $inventory = Inventory::where('warehouse_id',$request->warehouse_id)->where('product_id',$value->id)->first();
                $products[$key]->stock = $inventory->stock ?? 0;
            }
            return response()->json($products);
        }
        catch (\Exception $exception){
            return response()->json("Not found");
        }
        //--- Logic Section Ends

    }

    public function orderList(Request $request)
    {
        DB::beginTransaction();
        try {
            $pos_no = str_random(4) . time();

            foreach ($request->sale_item as $item) {

                if ($request->admin_role == "posAdmin") {
                    $inventory = Inventory::where('warehouse_id', $request->warehouse_id)
                        ->where('product_id', $item['id'])->first();
                    if($inventory->stock >= $item['quantity']){
                        $inventory->stock = (float)$inventory->stock - (float)$item['quantity'];
                        $inventory->update();
                        DB::commit();
                    }
                    else{
                        DB::rollback();
                        return response()->json($item['pro_name']." is out of stock");
                    }
                } else {
                    $inventory = Inventory::where('warehouse_id', $request->admin_id)
                        ->where('product_id', $item['id'])->first();
                    if($inventory->stock >= $item['quantity']){
                        $inventory->stock = (float)$inventory->stock - (float)$item['quantity'];
                        $inventory->update();
                        DB::commit();
                    }
                    else{
                        DB::rollback();
                        return response()->json($item['pro_name']." is out of stock");
                    }
                }
            }

            $warehouse_name = Admin::where('id', $request->warehouse_id)->first();

            $pos = new PosSale();
            $pos->pos_no = $pos_no;
            $pos->warehouse_id = $request->warehouse_id;
            $pos->warehouse_name = $warehouse_name->name ?? "";
            $pos->admin_id = $request->admin_id;
            $pos->admin_name = $request->admin_name;
            $pos->admin_role = $request->admin_role;
            $pos->discount_type = $request->discount_type;
            $pos->discount_amount = $request->discount_amount;
            $pos->tax = $request->tax;
            $pos->grand_total = $request->grand_total;
            $pos->payment_type = $request->payment_type;
            $pos->transaction_id = $request->transaction_id;
            $pos->customer_id = $request->customer_id;
            $pos->customer_name = $request->customer_name;
            $pos->sale_item = utf8_encode(bzcompress(serialize($request->sale_item), 9));

            $pos->save();
            DB::commit();

            return response()->json($pos_no);
        }
        catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    }



}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;
use DB;


class ApiProductController extends Controller
{
    public function __construct(){
        // header('Access-Control-Allow-Origin: *');
    }
    public function category(){
        $category = Category::where('status',1)->with('subs')->get();
        return response()->json($category);
    }

    public function productAscategories(Request $request){
        if($request->category_id){

            $products = Product::with('galleries')
                ->orderBy('id','desc')
                ->where('category_id',$request->category_id)
                ->where('status',1)
                ->get(['id','name','photo','price','previous_price',
                    'colors','size','size_qty','size_price','stock',
                    'slug'
                ]);

            if($products){
                return response()->json($products);
            }
            else{
                return response()->json("Not found");
            }
        }
        elseif($request->subcategory_id){
            $products = Product::with('galleries')
                ->orderBy('id','desc')
                ->where('subcategory_id',$request->subcategory_id)
                ->where('status',1)
                ->get(['id','name','photo','price','previous_price',
                    'colors','size','size_qty','size_price','stock','slug']);

            if($products){
                return response()->json($products);
            }
            else{
                return response()->json("Not found");
            }

        }
        elseif($request->childcategory_id){
            $products = Product::with('galleries')
                ->orderBy('id','desc')
                ->where('childcategory_id',$request->childcategory_id)
                ->where('status',1)
                ->get(['id','name','photo','price','previous_price',
                    'colors','size','size_qty','size_price','stock',
                    'slug']);

            if($products){
                return response()->json($products);
            }
            else{
                return response()->json("Not found");
            }
        }
        elseif($request->product_id){
            $products = Product::with('galleries')
                ->where('id',$request->product_id)
                ->where('status',1)
                ->get(['id','name','photo','price','previous_price',
                    'colors','size','size_qty','size_price','stock','details','policy',
                    'slug']);
            if($products){
                return response()->json($products);
            }
            else{
                return response()->json("Not found");
            }

        }
    }

    public function productSearch(Request $request){
        //--- Validation Section
        $rules = [
            'name'      => 'required',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        try{
            $products = Product::where('name','LIKE',"%{$request->name}%")
                ->where('status',1)
                ->take(10)
                ->get(['id','name','thumbnail','price','previous_price',
                    'colors','size','size_qty','size_price','stock',
                    'slug']);

            return response()->json($products);
        }
        catch (\Exception $exception){
            return response()->json("Not found");
        }
        //--- Logic Section Ends
    }

    public function productNew(){
        try{
            $products = Product::orderBy('id','desc')
                ->where('status',1)
                ->take(20)->get(
                ['id','name','photo','price','previous_price',
                'colors','size','size_qty','size_price','stock',
                'slug']
            );

            return response()->json($products);
        }
        catch (\Exception $exception){
            return response()->json("Not found");
        }
    }

    public function productOnSale(){
        try{
            $products = Product::orderBy('id','desc')
                ->where('sale',1)
                ->where('status',1)
                ->get(
                    ['id','name','photo','price','previous_price',
                    'colors','size','size_qty','size_price','stock',
                    'slug']
                );

            return response()->json($products);
        }
        catch (\Exception $exception){
            return response()->json("Not found");
        }
    }

    public function allProduct(){
        $data = Product::paginate(20,
            ['id','name','photo','price','previous_price',
                'colors','size','size_qty','size_price','stock',
                'slug']);
        return response()->json($data);
    }

}
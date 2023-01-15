<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Product;
use App\Models\User;
use App\Models\Currency;
use App\Models\Campaign;
use Validator;
use DB;
use Illuminate\Support\Facades\Input;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** GET Request
    public function index()
    {
        return view('admin.campaign.index');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'discount_type'      => 'required',
            'offer'      => 'required',
            'available_to'      => 'required',
            'specific_to'      => 'required',
            'start_date'      => 'required'
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

         //--- Logic Section        
        $data = new Campaign;
        $sign = Currency::where('is_default','=',1)->first();
        $input = $request->all();

        $input['specific_to'] = implode(',', $request->specific_to);
        
       // Save Data
        $data->fill($input)->save();
        
    }
    public function dropdown($id){
        
        if($id == 1){
            $data = Category::where('status','=',1)->orderBy('id','desc')->get();
        }
        else if($id == 2){
            $data = Subcategory::where('status','=',1)->orderBy('id','desc')->get();
        }
        else if($id == 3){
            $data = Childcategory::where('status','=',1)->orderBy('id','desc')->get();
        }
        else if($id == 4){
            $data = Product::where('status','=',1)->orderBy('id','desc')->get();
        }
        else if($id == 5){
            $data = User::orderBy('id','desc')->get();;
        }
       return view('load.campaign',compact('data'));
    }
}

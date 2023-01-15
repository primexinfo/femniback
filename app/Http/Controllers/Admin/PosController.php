<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\PosSale;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Datatables;
use App\Models\AdminLanguage;
use Auth;
use DB;
use Illuminate\Support\Carbon;
use Validator;
use Illuminate\Support\Facades\Input;

class PosController extends Controller
{
    public function index(){
        return view('admin.pos.pos_admin.index');
    }

    public function datatable(){
        $datas = Admin::where('role','posAdmin')->orderBy('id')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('action', function(Admin $data) {
                //$delete = $data->id == 1 ? '':'<a href="javascript:;" data-href="' . route('admin-staff-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
                return '<div class="action-list"><a data-href="' . route('pos-admin-show',$data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a></div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function adminCreate(){
        return view('admin.pos.pos_admin.create');
    }

    public function adminStore(Request $request){
//--- Validation Section
        $rules = [
            'name'      => 'required',
            'email'      => 'required|unique:admins,email',
            'phone'      => 'required',
            'password'      => 'required',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $data = new Admin();
        $data->name = $request->name;
        $data->email  = $request->email;
        $data->phone  = $request->phone;
        $data->warehouse_id  = Auth::guard('admin')->user()->id;
        $data->role  = 'posAdmin';
        $data->password  = bcrypt($request->password);
        $data->status  = 1;

        $data->save();

        $msg = 'New Data Added Successfully.';
        return response()->json($msg);
    }

    public function adminShow($id){
        $data = Admin::findOrFail($id);
        return view('admin.pos.pos_admin.show',compact('data'));
    }

    public function saleHistory(){
        return view('admin.pos.index');
    }
    public function saleDatatable(){

        $datas = PosSale::orderBy('id','desc')->get();//--- Integrating This Collection Into Datatables
        if(Auth::guard('admin')->user()->id == 1){
            $datas = PosSale::orderBy('created_at','desc')->get();
        }
        elseif(Auth::guard('admin')->user()->role == 'warehouseAdmin'){
            $datas = PosSale::where('warehouse_id', Auth::guard('admin')->user()->id)->orderBy('created_at','desc')->get();
        }
        elseif(Auth::guard('admin')->user()->role == 'posAdmin'){
            $datas = PosSale::where('id', Auth::guard('admin')->user()->id)->orderBy('created_at','desc')->get();
        }

        return Datatables::of($datas)
            ->addColumn('created_at', function(PosSale $data) {

                return Carbon::parse($data->created_at)->format('d M, Y h:i A');
            })
            ->addColumn('action', function(PosSale $data) {
                return '<div class="action-list"><a href="' . route('pos-details-show',$data->id) . '" class="view details-width" > <i class="fas fa-eye"></i>Details</a></div>';
            })
            ->rawColumns(['created_at', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function posDetails($id){
        $pos = PosSale::findOrfail($id);
        return view('admin.pos.details',compact('pos'));
    }

    public function posReport(){

        $products = Product::get(['id','name']);

        $todays_total_orders = PosSale::whereDate('created_at', Carbon::today())
            ->orderBy('created_at','desc')->get();
        $orderedProducts=[];
        foreach ($todays_total_orders as $orders){
            $sale_item = unserialize(bzdecompress(utf8_decode($orders->sale_item)));
            for($i=0; $i<count($sale_item); $i++){
                array_push($orderedProducts, array(
                        "product_id" => $sale_item[$i]['id'],
                        "product_name" => $sale_item[$i]['pro_name'],
                        "quantity" => $sale_item[$i]['quantity'],
                        "product_price" => $sale_item[$i]['product_price'],
                        "line_total" => $sale_item[$i]['line_total'],
                        "created_at"=>$orders->created_at,
                    )
                );
            }
        }

        $result = [];
        foreach ($orderedProducts as $element) {
            $result[$element['product_name']][] = $element;
        }
        $pro = [];
        return view('admin.pos.report',compact('result','products','pro'));
    }

    public function posReportFilter(Request $request){
        $products = Product::get(['id','name']);

        if($request->start_date && $request->end_date){
            $todays_total_orders = PosSale:: whereBetween('created_at',[$request->start_date,$request->end_date])
                ->orderBy('created_at','desc')
                ->get();
        }
        else{
            $todays_total_orders = PosSale::orderBy('created_at','desc')->get();
        }


        $orderedProducts=[];
        foreach ($todays_total_orders as $orders){
            $sale_item = unserialize(bzdecompress(utf8_decode($orders->sale_item)));

            for($i=0; $i<count($sale_item); $i++){
                array_push($orderedProducts, array(
                        "product_id" => $sale_item[$i]['id'],
                        "product_name" => $sale_item[$i]['pro_name'],
                        "quantity" => $sale_item[$i]['quantity'],
                        "product_price" => $sale_item[$i]['product_price'],
                        "line_total" => $sale_item[$i]['line_total'],
                        "created_at"=>$orders->created_at,
                    )
                );
            }
        }

        $pro = [];
        if($request->product_id){
            foreach ($orderedProducts as $r){
                if(in_array($request->product_id,$r)){
                    array_push($pro,$r);
                }
            }
        }
        else{
            $pro = $orderedProducts;
        }

        $result = [];
        return view('admin.pos.report',compact('result','products','pro'));
    }

    public function AutocompltegetAutocomplte(Request $request){
        $search = $request->search;

        if($search == ''){
            $autocomplate = Product::orderby('name','desc')->select('id','name')->limit(7)->get();
        }else{
            $autocomplate = Product::orderby('name','desc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(7)->get();
        }

        $response = array();

        foreach($autocomplate as $autocomplat){
            $response[] = array("value"=>$autocomplat->id,"label"=>$autocomplat->name,"type"=>$autocomplat->type);
        }

        echo json_encode($response);
        exit;
    }
}

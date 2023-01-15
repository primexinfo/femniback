<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Inventory;
use App\Models\InventoryEntry;
use App\Models\InventoryRequests;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;

use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
class InventoryController extends Controller
{
    public function create(){
        return view('admin.inventory.create');
    }
    public function index(){
        $warehouses = Admin::Orwhere('role','Administrator')->Orwhere('role','warehouseAdmin')->get();
        return view('admin.inventory.index',compact('warehouses'));
    }
    public function store(Request $request){
        //--- Validation Section
        $messages = array(
            'product_id.required' => 'Product name is required.',
            'amount_per_quantity.required' => 'Amount per quantity is Required.',
            'quantity.required' => 'Quantity field is Required.',
            'amount.required' => 'Amount field is Required.',

        );
        $this->validate($request, array(
            'product_id'      => 'required',
            'amount_per_quantity'      => 'required',
            'quantity'      => 'required',
            'amount'      => 'required',

        ), $messages);
        //--- Validation Section Ends

        //--- Logic Section
        $invoice_no = str_random(4).time();
        DB::beginTransaction();
        try {
            $warehouse_id = Auth::guard('admin')->user()->id;
            $warehouse_name = Auth::guard('admin')->user()->name;
            if($request->product_type == 'package'){

                $product = Product::findOrFail($request->product_id);
                $package_items = unserialize(bzdecompress(utf8_decode(($product->package_items))));
                foreach($package_items as $sale_item){
                    $inventory = Inventory::where('warehouse_id',$warehouse_id)
                        ->where('product_id',$sale_item['id'])->first();
                    if(!$inventory){
                        return back()->with('warning','product has not added into Inventory');
                    }
                }
                foreach($package_items as $sale_item){
                    $inventory = Inventory::where('warehouse_id',$warehouse_id)
                        ->where('product_id',$sale_item['id'])->first();
                    if((float)$inventory->stock >= (float)$request->quantity){
                        $inventory->stock = (float)$inventory->stock - (float)$sale_item['item_quantity'];
                        $inventory->update();
                        DB::commit();
                    }
                    else{
                        DB::rollback();
                        return back()->with('warning','product has stock out');
                    }
                }

                $data = new Inventory();
                $data->warehouse_id = $warehouse_id;
                $data->warehouse_name = $warehouse_name;
                $data->product_id = $request->product_id;
                $data->product_name = $request->product_name;
                $data->stock = $request->quantity;
                $data->save();
                DB::commit();

                $inventoryEntry = new InventoryEntry();
                $inventoryEntry->inventory_id = $data->id;
                $inventoryEntry->warehouse_id = $warehouse_id;
                $inventoryEntry->invoice_no = $invoice_no;
                $inventoryEntry->warehouse_name = $warehouse_name;
                $inventoryEntry->product_name = $request->product_name;
                $inventoryEntry->product_id = $request->product_id;
                $inventoryEntry->quantity = $request->quantity;
                $inventoryEntry->amount = $request->amount;
                $inventoryEntry->amount_per_quantity = $request->amount_per_quantity;
                $inventoryEntry->save();
                DB::commit();
                return back()->with('success','Inventory added successfully');
            }
            else{
                $inventory = Inventory::where('warehouse_id',$warehouse_id)
                    ->where('product_id',$request->product_id)->first();

                if($inventory){
                    $inventory->stock = (float)$inventory->stock + (float)$request->quantity;
                    $inventory->update();
                    DB::commit();

                    $inventoryEntry = new InventoryEntry();
                    $inventoryEntry->inventory_id = $inventory->id;
                    $inventoryEntry->invoice_no = $invoice_no;
                    $inventoryEntry->warehouse_id = $warehouse_id;
                    $inventoryEntry->warehouse_name = $warehouse_name;
                    $inventoryEntry->product_name = $inventory->product_name;
                    $inventoryEntry->product_id = $inventory->product_id;
                    $inventoryEntry->quantity = $request->quantity;
                    $inventoryEntry->amount = $request->amount;
                    $inventoryEntry->amount_per_quantity = $request->amount_per_quantity;
                    $inventoryEntry->save();
                    DB::commit();

                    return back()->with('success','Product stock added successfully into inventory');

                }
                else{
                    $data = new Inventory();
                    $data->warehouse_id = $warehouse_id;
                    $data->warehouse_name = $warehouse_name;
                    $data->product_id = $request->product_id;
                    $data->product_name = $request->product_name;
                    $data->stock = $request->quantity;

                    $data->save();
                    DB::commit();

                    $inventoryEntry = new InventoryEntry();
                    $inventoryEntry->inventory_id = $data->id;
                    $inventoryEntry->warehouse_id = $warehouse_id;
                    $inventoryEntry->invoice_no = $invoice_no;
                    $inventoryEntry->warehouse_name = $warehouse_name;
                    $inventoryEntry->product_name = $request->product_name;
                    $inventoryEntry->product_id = $request->product_id;
                    $inventoryEntry->quantity = $request->quantity;
                    $inventoryEntry->amount = $request->amount;
                    $inventoryEntry->amount_per_quantity = $request->amount_per_quantity;
                    $inventoryEntry->save();
                    DB::commit();
                    return back()->with('success','Inventory added successfully');
                }
            }
        }catch (\Exception $exception){
            DB::rollback();

        }
    }

    public function datatables($status){

        $datas = Inventory::where('warehouse_id',$status)->orderBy('id','desc')->get();


        if(Auth::guard('admin')->user()->id != $status){
            //--- Integrating This Collection Into Datatables
            return Datatables::of($datas)

                ->addColumn('warehouse_name', function(Inventory $data) {
                    return $data->warehouse_name;

                })
                ->addColumn('product_name', function(Inventory $data) {
                    $product_name = Product::findOrFail($data->product_id);
                    return $product_name->name;
                })
                ->addColumn('stock', function(Inventory $data) {
                    return $data->stock;
                })
                ->addColumn('updated_at', function(Inventory $data) {
                    return Carbon::parse($data->updated_at)->format('d M, Y');
                })
                ->addColumn('action', function(Inventory $data) {
                    return '<div class="action-list">
                          
                        <a href="' . url('/inventory/stock/request/'.$data->warehouse_id.'/'.$data->product_id) . '" class=""><i class="fas fa-list"></i>Stock Request</a></div>';

                })
                ->rawColumns(['warehouse_name','product_name','stock','updated_at','action'])
                ->toJson(); //--- Returning Json Data To Client Side
        }
        else{
            //--- Integrating This Collection Into Datatables
            return Datatables::of($datas,$status)

                ->addColumn('warehouse_name', function(Inventory $data) {
                    return $data->warehouse_name;

                })
                ->addColumn('product_name', function(Inventory $data) {
                    $product_name = Product::findOrFail($data->product_id);
                    return $product_name->name;
                })
                ->addColumn('stock', function(Inventory $data) {
                    return $data->stock;
                })
                ->addColumn('updated_at', function(Inventory $data) {
                    return Carbon::parse($data->updated_at)->format('d M, Y');
                })
                ->addColumn('action', function(Inventory $data) {
                    return '<div class="action-list">
                            <a href="' . url('/inventory/details/'.$data->warehouse_id.'/'.$data->product_id) . '" class="edit" > <i class="fas fa-edit"></i>Details</a>
                        </div>';

                })
                ->rawColumns(['warehouse_name','product_name','stock','updated_at','action'])
                ->toJson(); //--- Returning Json Data To Client Side
        }

    }

    public function details($warehouse_id,$product_id){
        $inventory = InventoryEntry::where('warehouse_id',$warehouse_id)
            ->where('product_id',$product_id)->orderBy('id','desc')->paginate(20);
        return view('admin.inventory.details',compact('inventory'));
    }

    public function stockRequest($warehouse_id,$product_id){
        $inventory = Inventory::where('warehouse_id',$warehouse_id)
            ->where('product_id',$product_id)->first();
        return view('admin.inventory.stock_request',compact('inventory'));
    }
    public function stockRequestStore(Request $request){
        //--- Validation Section
        $messages = array(
            'quantity.required' => 'Quantity field is Required.',
        );
        $this->validate($request, array(
            'quantity'      => 'required',

        ), $messages);
        //--- Validation Section Ends

        //--- Logic Section
        $invoice_no = str_random(4).time();
        $data = new InventoryRequests();
        $data->warehouse_id_to = $request->warehouse_id;
        $data->invoice_no = $invoice_no;
        $data->status = 1;
        $data->warehouse_id_from = Auth::guard('admin')->user()->id;
        $data->warehouse_name_to = $request->warehouse_name;
        $data->warehouse_name_from = Auth::guard('admin')->user()->name;
        $data->product_id = $request->product_id;
        $data->product_name = $request->product_name;
        $data->quantity = $request->quantity;

        $data->save();

//        $notification = new Notification();
//        $notification->inventory_stock_request_id = $data;
//        $notification->save();

        return redirect()->route('admin-inventory-sent-stock-request')->with('success','Stock request added successfully');
    }

    public function stockRequestSent(){
        return view('admin.inventory.sentStockRequest');
    }

    public function stockRequestSentDatatable(){

        $warehouse_id = Auth::guard('admin')->user()->id;

        $datas = InventoryRequests::where('warehouse_id_from',$warehouse_id)->orderBy('id','desc')->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('created_at', function(InventoryRequests $data) {
                return Carbon::parse($data->created_at)->format('d M, Y h:i A');
            })
            ->addColumn('invoice_no', function(InventoryRequests $data) {
                return $data->invoice_no;
            })
            ->addColumn('warehouse_name', function(InventoryRequests $data) {
                return $data->warehouse_name_to;
            })
            ->addColumn('product_name', function(InventoryRequests $data) {
                return $data->product_name;
            })
            ->addColumn('quantity', function(InventoryRequests $data) {
                return $data->quantity;
            })
            ->addColumn('status', function(InventoryRequests $data) {
                if($data->status == 1){
                    return '<span class="badge badge-warning">Pending</span>';
                }
                elseif($data->status == 2){
                    return '<span class="badge badge-info">On process</span>';
                }
                elseif($data->status == 3){
                    return '<span class="badge badge-danger">Canceled</span>';
                }
                else{
                    return '<span class="badge badge-success">Received</span>';
                }
            })
            ->addColumn('action', function(InventoryRequests $data) {
                if($data->status != 1 && $data->status != 4){
                    return '<div class="action-list">
                                <a href="' . route('admin-inventory-receive-stock-request-status-sent',$data->id) . '" class="" > <i class="fas fa-edit"></i>Change status</a>
                                <a href="' . route('admin-inventory-stock-request-invoice',$data->id) . '" class="" > <i class="fas fa-download"></i>Invoice</a>
                            </div>';
                }
                elseif($data->status == 4){
                    return '<div class="action-list"><a href="' . route('admin-inventory-stock-request-invoice',$data->id) . '" class="" > <i class="fas fa-download"></i>Invoice</a></div>';
                }
                else{
                    return '<div class="action-list">
                                <a href="'.route('admin-inventory-sent-stock-request-edit',$data->id).'" class="edit" > <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-cat-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>
                                <a href="' . route('admin-inventory-stock-request-invoice',$data->id) . '" class="" > <i class="fas fa-download"></i>Invoice</a>
                            </div>';
                }

            })
            ->rawColumns(['created_at','warehouse_name','product_name','quantity','status','action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function stockRequestSentEdit($id){
        $inventoryRequests = InventoryRequests::findOrFail($id);
        return view('admin.inventory.sentStockRequestEdit',compact('inventoryRequests'));
    }

    public function stockRequestSentUpdate(Request $request,$id){
        $messages = array(
            'quantity.required' => 'Quantity field is Required.',
        );
        $this->validate($request, array(
            'quantity'      => 'required',

        ), $messages);
        //--- Validation Section Ends
        $inventoryRequests = InventoryRequests::findOrFail($id);
        $inventoryRequests->quantity = $request->quantity;
        $inventoryRequests->update();

        return redirect()->route('admin-inventory-sent-stock-request')->with('success','Stock request Updated successfully');
    }



    public function stockRequestRecieved(){
        return view('admin.inventory.receivedStockRequest');
    }
    public function stockRequestRecievedDatatable(){
        $warehouse_id = Auth::guard('admin')->user()->id;
        $datas = InventoryRequests::where('warehouse_id_to',$warehouse_id)->orderBy('id','desc')->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('created_at', function(InventoryRequests $data) {
                return Carbon::parse($data->created_at)->format('d M, Y h:i A');
            })
            ->addColumn('invoice_no', function(InventoryRequests $data) {
                return $data->invoice_no;
            })
            ->addColumn('warehouse_name', function(InventoryRequests $data) {
                return $data->warehouse_name_from;
            })
            ->addColumn('product_name', function(InventoryRequests $data) {
                return $data->product_name;
            })
            ->addColumn('quantity', function(InventoryRequests $data) {
                return $data->quantity;
            })
            ->addColumn('status', function(InventoryRequests $data) {
                if($data->status == 1){
                    return '<span class="badge badge-warning">Pending</span>';
                }
                elseif($data->status == 2){
                    return '<span class="badge badge-info">On process</span>';
                }
                elseif($data->status == 3){
                    return '<span class="badge badge-danger">Canceled</span>';
                }
                else{
                    return '<span class="badge badge-success">Received</span>';
                }

            })
            ->addColumn('action', function(InventoryRequests $data) {
                if($data->status != 4 ){
                    return '<div class="action-list">
                                <a href="' . route('admin-inventory-receive-stock-request-status',$data->id) . '" class="edit" > <i class="fas fa-edit"></i>Change status</a>
                                <a href="' . route('admin-inventory-stock-request-invoice',$data->id) . '" class="" > <i class="fas fa-download"></i>Invoice</a>
                            </div>';
                }else{
                    return '<div class="action-list">
                                <a href="' . route('admin-inventory-stock-request-invoice',$data->id) . '" class="" > <i class="fas fa-download"></i>Invoice</a>
                            </div>';
                }
            })
            ->rawColumns(['created_at','warehouse_name','product_name','quantity','status','action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function stockRequestRecievedStatus($id){
        $inventoryRequests = InventoryRequests::findOrFail($id);
        return view('admin.inventory.stockRequestRecievedStatus',compact('inventoryRequests'));
    }
    public function stockRequestRecievedStatusSent($id){
        $inventoryRequests = InventoryRequests::findOrFail($id);
        return view('admin.inventory.stockRequestRecievedStatusSent',compact('inventoryRequests'));
    }
    public function stockRequestRecievedStatusChange(Request $request,$id)
    {
        $inventoryRequests = InventoryRequests::findOrFail($id);

        if ($request->status == 2) {
            $inventory = Inventory::where('warehouse_id', Auth::guard('admin')->user()->id)
                ->where('product_id', $inventoryRequests->product_id)->first();
            $inventory->stock = (float)$inventory->stock - (float)$inventoryRequests->quantity;
            $inventory->update();
        }
        elseif($request->status == 4){

            $inventory = Inventory::where('warehouse_id', Auth::guard('admin')->user()->id)
                ->where('product_id', $inventoryRequests->product_id)->first();
            if($inventory){
                $inventory->stock = (float)$inventory->stock + (float)$inventoryRequests->quantity;
                $inventory->update();
            }
            else{

                $data = new Inventory();
                $data->warehouse_id = Auth::guard('admin')->user()->id;
                $data->warehouse_name = Auth::guard('admin')->user()->name;
                $data->product_id = $inventoryRequests->product_id;
                $data->product_name = $inventoryRequests->product_name;
                $data->stock = $inventoryRequests->quantity;

                $data->save();

                $invoice_no = str_random(4).time();
                $inventoryEntry = new InventoryEntry();
                $inventoryEntry->inventory_id = $data->id;
                $inventoryEntry->warehouse_id = Auth::guard('admin')->user()->id;
                $inventoryEntry->invoice_no = $invoice_no;
                $inventoryEntry->warehouse_name =Auth::guard('admin')->user()->name;
                $inventoryEntry->product_name = $inventoryRequests->product_name;
                $inventoryEntry->product_id = $inventoryRequests->product_id;
                $inventoryEntry->quantity = $inventoryRequests->quantity;
                //$inventoryEntry->amount = $request->amount;
                //$inventoryEntry->amount_per_quantity = $request->amount_per_quantity;
                $inventoryEntry->save();
            }
        }
        $inventoryRequests->status = $request->status;
        $inventoryRequests->update();
        if ($request->page == 1) {
            return redirect()->route('admin-inventory-receive-stock-request')->with('success', 'Stock request status change successfully');
        } else {
            return redirect()->route('admin-inventory-sent-stock-request')->with('success', 'Stock request status change successfully');
        }
    }
    public function stockRequestInvoice($id){
        $inventoryRequests = InventoryRequests::findOrFail($id);
        return view('admin.inventory.stock_request_invoice',compact('inventoryRequests'));
    }
    public function stockRequestInvoicePrint($id){
        $inventoryRequests = InventoryRequests::findOrFail($id);
        return view('admin.inventory.stock_request_invoice_print',compact('inventoryRequests'));
    }

}

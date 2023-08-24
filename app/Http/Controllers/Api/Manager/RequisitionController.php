<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\RequisitionProduct;
use App\Models\StockRequisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $region_id=$request->user()->region_id;
       if ($request->user()->account_type === 'RSM'){
          $requisitions = StockRequisition::where("status", "Waiting Approval")
             ->whereHas('user', function ($query) use ($region_id) {
                $query->where('region_id', $region_id);
             })
             ->with('user')
             ->withCount('RequisitionProducts', 'ApprovedRequisitionProducts')
             ->orderBy('id', 'DESC')
             ->get();
       }else
       $requisitions = StockRequisition::where("status","Waiting Approval")->with('user')->withCount('RequisitionProducts', 'ApprovedRequisitionProducts')
          ->orderBy('id', 'DESC')->get();
       $action="Viewed Stock requisition";
       $activity="Viewed Stock requisition which has status Waiting Approval";
       $this->activitylogs($action, $activity);

       return response()->json([
          'status' => 200,
          'success' => true,
          "message" => "All requisitions products available",
          'data' => $requisitions,
       ]);
    }
    public function history(Request $request)
    {
       $region_id=$request->user()->region_id;
       if ($request->user()->account_type === 'RSM'){
          $requisitions = StockRequisition::where("status", '!=',"Waiting Approval")
             ->whereHas('user', function ($query) use ($region_id) {
                $query->where('region_id', $region_id);
             })
             ->with('user')
             ->withCount('RequisitionProducts', 'ApprovedRequisitionProducts')
             ->orderBy('id', 'DESC')
             ->get();
       }else
       $requisitions = StockRequisition::where("status","Waiting Approval")->with('user')->withCount('RequisitionProducts', 'ApprovedRequisitionProducts')
          ->orderBy('id', 'DESC')->get();

       return response()->json([
          'status' => 200,
          'success' => true,
          "message" => "All requisitions products available",
          'data' => $requisitions,
       ]);
    }
   public function handleApproval(Request $request)
   {
      $arrayData = [];
      $selectedProducts = $request->input('selected_products', []);
      $warehouse_code = $request->input('warehouse_code');
      $user = $request->user();
      $user_code = $user->user_code;
      $business_code = $user->business_code;
      $random = Str::random(20);
      if (empty($selectedProducts)) {
         return response()->json([
            'status' => 409,
            'success' => false,
            "message" => "Not products selected",
         ]);
      }
        elseif (empty($warehouse_code)) {
         return response()->json([
            'status' => 409,
            'success' => false,
            "message" => "No Warehouse selected",
         ]);
      }else{
         foreach ($selectedProducts as $productId) {
            $product = RequisitionProduct::where('requisition_id', $request->requisition_id)->where('product_id',$productId)->get();

            if ($product) {
               if ($request->has('approve')) {
                  $product->update(['approval' => 1]);
                  StockRequisition::where('id',$request->requisition_id)->update([
                      'status'=>'Approved'
                  ]);

               } elseif ($request->has('disapprove')) {
                  $product->update(['approval' => 0]);
                   StockRequisition::where('id',$request->requisition_id)->update([
                       'status'=>'Approved'
                   ]);
               }
            }
         }
      }
      $action="Allocated products to sales person";
      $activity="Viewed Stock requisition which has status Waiting Approval";
      $this->activitylogs($action, $activity);
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Allocated products to sales person",
         'data' => $arrayData,
      ]);
   }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
   public function activitylogs($activity,$action): void
   {
      $rdm = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = $activity;
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Mobile';
      $activityLog->action =  $action;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $rdm;
      $activityLog->ip_address = session('login_ip');
      $activityLog->save();
   }
    public function approve(Request $request)
    {
       $validator           =  Validator::make($request->all(), [
          "id"   => "required|integer",
       ]);
       if ($validator->fails()) {
          return response()->json(
             [
                "status" => 401, "message" => "validation_error",
                "errors" => $validator->errors()
             ],
             403
          );
       }
       $products = RequisitionProduct::where('requisition_id', $request->id)->with('ProductInformation')->get();
       return response()->json([
          'status' => 200,
          'success' => true,
          "message" => "Requisition products",
          'data' => $products,
       ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

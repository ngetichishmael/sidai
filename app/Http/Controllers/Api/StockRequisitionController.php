<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockRequisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index()
   {
      $stockRequisitions = StockRequisition::with('requisitionProducts')->get();
      return response()->json($stockRequisitions);
   }

   public function store(Request $request)
   {
      $stockRequisition = StockRequisition::create($request->all());
      return response()->json($stockRequisition, 201);
   }

   public function show(StockRequisition $stockRequisition)
   {
      $stockRequisition->load('requisitionProducts')->where('sales_person', Auth::user()->user_code );
      return response()->json($stockRequisition);
   }

   public function update(Request $request, StockRequisition $stockRequisition)
   {
      $stockRequisition->update($request->all());
      return response()->json($stockRequisition);
   }

   public function destroy(StockRequisition $stockRequisition)
   {
      $stockRequisition->delete();
      return response()->json(null, 204);
   }
}

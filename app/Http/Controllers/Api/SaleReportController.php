<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SaleReport;
use Illuminate\Http\Request;

class SaleReportController extends Controller
{
    public function store(Request $request, $customer_id, $checking_code)
    {

        $result = SaleReport::create([
            'user_id' => $request->user()->id,
            'customer_id' => $customer_id,
            'checking_code' => $checking_code,
            'customer_ordered' => $request->customer_ordered,
            'outlet_has_stock' => $request->outlet_has_stock,
            'competitor_supplier' => $request->competitor_supplier,
            'likely_ordered_products' => $request->likely_ordered_products,
            'highest_sale_products' => $request->highest_sale_products,
        ]);
        return response()->json([
            'Data' => $result,
        ]);

    }
}

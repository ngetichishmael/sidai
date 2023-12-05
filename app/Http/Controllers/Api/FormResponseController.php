<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; 


class FormResponseController extends Controller
{
    public function store(Request $request, $customer_id, $checking_code)
    {
        $validator = Validator::make($request->all(), [
            'interested_in_new_order' => 'required|string',
            'stock_replenishment' => 'nullable|string',
            'next_step_for_customer' => 'nullable|string',
            'expiry_date_update' => 'nullable|date_format:Y-m-d',
            'pricing_accuracy' => 'required|string|in:Yes,No',
            'incorrect_pricing_product_name' => 'nullable|string',
            'incorrect_pricing_current_price' => 'nullable|string',
            'product_visible' => 'required|string|in:Yes,No',
            'progress_status' => 'required|string|in:Very poor,Average,Good,Very Good',
            'new_insights' => 'nullable|string',
            'product_visible' => 'required|string|in:Yes,No',
            'available_products' => 'array', // Add this for stock levels
            'available_products.*.product_id' => 'integer',
            'available_products.*.stock_level' => 'integer',
            'available_products.*.expiration_date' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images/responses'), $imageName);
        } else {
            $imageName = null;
        }

        $stockLevelsData = $request->input('available_products');

        // Create a FormResponse instance
        $formResponse = FormResponse::create([
            'user_id' => $request->user()->id,
            'checking_code' => $checking_code,
            'customer_id' => $customer_id,
            'image' => $imageName,
            'interested_in_new_order' => $request->input('interested_in_new_order'),
            'stock_replenishment' => $request->input('stock_replenishment'),
            'next_step_for_customer' => $request->input('next_step_for_customer'),
            'expiry_date_update' => $request->input('expiry_date_update'),
            'pricing_accuracy' => $request->input('pricing_accuracy'),
            'incorrect_pricing_product_name' => $request->input('incorrect_pricing_product_name'),
            'incorrect_pricing_current_price' => $request->input('incorrect_pricing_current_price'),
            'product_visible' => $request->input('product_visible'),
            'progress_status' => $request->input('progress_status'),
            'new_insights' => $request->input('new_insights'),
        ]);

        // Create related stock levels
        if ($stockLevelsData !== null) {
            // Create related stock levels
            foreach ($stockLevelsData as $stockData) {
                $formResponse->availableProducts()->create([
                    'user_id' => auth()->user()->id, 
                    'customer_id' => $customer_id, 
                    'product_id' => $stockData['product_id'],
                    'stock_level' => $stockData['stock_level'],
                    'expiration_date' => $stockData['expiration_date'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Form response submitted successfully.',
            'data' => $formResponse,
        ]);
    }
}

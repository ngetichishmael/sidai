<?php

use App\Models\customer\checkin;
use App\Models\products\product_information;
use Illuminate\Http\Request;


class TotalAmount
{
public function amount(Request $request,$checkinCode){
    $checkin = checkin::where('code', $checkinCode)->first();
    $request = $request->collect();
    $total=0;
    foreach ($request as $value){
        $product = product_information::join('product_price',
        'product_price.productID', '=', 'product_information.id')
       ->where('product_information.id', $value["productID"])
       ->where('product_information.business_code', $checkin->business_code)
       ->first();
       $total_amount = $value["qty"] * $product->selling_price;
       $total+=$total_amount;
    }
    return $total;
    }
}





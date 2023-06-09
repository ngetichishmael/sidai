<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReconcilationController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $mpesa = DB::select('SELECT SUM(`amount`) as Mpesa
                FROM
                    `order_payments`
                WHERE
                    `order_payments`.`payment_method` =? AND`order_payments`.`isReconcile` =? AND `order_payments`.`user_id`=?', ['PaymentMethods.Mpesa','false', $user_id]);
        $cash = DB::select('SELECT SUM(`amount`) as Cash
                FROM
                    `order_payments`
                WHERE
                    `order_payments`.`payment_method` =? AND`order_payments`.`isReconcile` =? AND `order_payments`.`user_id`=?', ['PaymentMethods.Cash', 'false',$user_id]);
        $cheque = DB::select('SELECT SUM(`amount`) as Cheque
                FROM
                    `order_payments`
                WHERE
                    `order_payments`.`payment_method` =? AND`order_payments`.`isReconcile` =? AND `order_payments`.`user_id`=?', ['PaymentMethods.Cheque','false', $user_id]);

        return response()->json([
            "success" => true,
            "message" => "Total Amount Expected",
            "Mpesa" => $mpesa,
            "Cash" => $cash,
            "Cheque" => $cheque
        ]);
    }
}

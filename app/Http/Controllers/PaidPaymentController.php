<?php

namespace App\Http\Controllers;

class PaidPaymentController extends Controller
{

    public function index()
    {
        return view('livewire.payment.paid.index');
    }
}

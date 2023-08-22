<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerCommentRequest;
use App\Http\Requests\UpdateCustomerCommentRequest;
use App\Models\CustomerComment;

class CustomerCommentController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('livewire.comment.index');
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

   /**
    * Store a newly created resource in storage.
    *
    * @param  \App\Http\Requests\StoreCustomerCommentRequest  $request
    * @return \Illuminate\Http\Response
    */
   public function store(StoreCustomerCommentRequest $request)
   {
      //
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Models\CustomerComment  $customerComment
    * @return \Illuminate\Http\Response
    */
   public function show(CustomerComment $customerComment)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\CustomerComment  $customerComment
    * @return \Illuminate\Http\Response
    */
   public function edit(CustomerComment $customerComment)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \App\Http\Requests\UpdateCustomerCommentRequest  $request
    * @param  \App\Models\CustomerComment  $customerComment
    * @return \Illuminate\Http\Response
    */
   public function update(UpdateCustomerCommentRequest $request, CustomerComment $customerComment)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\CustomerComment  $customerComment
    * @return \Illuminate\Http\Response
    */
   public function destroy(CustomerComment $customerComment)
   {
      //
   }
}

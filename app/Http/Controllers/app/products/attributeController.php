<?php

namespace App\Http\Controllers\app\finance\products;

use App\Http\Controllers\Controller;
use App\Models\finance\products\attributes;
use Auth;
use Illuminate\Http\Request;
use Limitless;
use Session;


class attributeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = attributes::where('businessID',Auth::user()->businessID)->where('parentID',0)->orderBy('id','desc')->get();
            $count = 1;
            return view('app.finance.products.attributes.index', compact('attributes','count'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);

        $attributes = new attributes;
        $attributes->name = $request->name;
        $attributes->userID = Auth::user()->id;
        $attributes->businessID = Auth::user()->businessID;
        $attributes->save();

        Session::flash('success','Attribute has been successfully added');

        return redirect()->back();
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
        $edit = attributes::where('businessID',Auth::user()->businessID)->where('id',$id)->first();
        $attributes = attributes::where('businessID',Auth::user()->businessID)->orderBy('id','desc')->get();
        $count = 1;

        return view('app.finance.products.attributes.edit', compact('attributes','count','edit'));

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
        $this->validate($request,[
            'name' => 'required',
        ]);

        $attributes = attributes::where('businessID',Auth::user()->businessID)->where('id',$id)->first();
        $attributes->name = $request->name;
        $attributes->userID = Auth::user()->id;
        $attributes->businessID = Auth::user()->businessID;
        $attributes->save();

        Session::flash('success','Attribute has been successfully added');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //delete all values
        $value = attributes::where('businessID',Auth::user()->businessID)->where('parentID',$id)->delete();

        //delete attributes
        $attributes = attributes::where('businessID',Auth::user()->businessID)->where('id',$id)->first();
        $attributes->delete();

        Session::flash('success','Attribute has been successfully deleted');

        return redirect()->back();
    }
}

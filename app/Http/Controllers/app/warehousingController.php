<?php

namespace App\Http\Controllers\app;

use App\Models\User;
use App\Models\Region;
use App\Helpers\Helper;
use App\Models\country;
use App\Models\Order_items;
use App\Models\Subregion;
use App\Models\warehousing;
use Illuminate\Support\Str;
use App\Models\activity_log;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\inventory\items;
use Illuminate\Validation\Rule;
use App\Imports\WarehouseImport;
use App\Models\warehouse_assign;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\products\product_information;

class warehousingController extends Controller
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $search = '';
   public $orderAsc = true;
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('app.warehousing.index');
   }
   public function getByRegion($regionId)
   {
      $subregions = Subregion::where('region_id', $regionId)->get();
      return response()->json($subregions);
   }
   public function import()
   {
      return view('app.warehousing.import');
   }
   public function storeWarehouse(Request $request)
   {
      $this->validate($request, [
         'upload_import' => 'required'
      ]);

      $file = request()->file('upload_import');

      Excel::import(new WarehouseImport, $file);

      Session()->flash('success', 'Warehouse imported Successfully.');

      return redirect()->route('warehousing.index');
   }

   public function allocations($warehouse_code){
      $product_informations = product_information::where('warehouse_code', $warehouse_code)->select('id')->get();
      $query = Order_items::query();

      if (Auth::user()->account_type === 'RSM') {
         $query->whereIn('order_code', $this->filter());
      }

      $allocated = $query->whereIn('productID', $product_informations)->get();

      return view('products.more',['allocated'=>$allocated]);

   }



   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      $country = country::pluck('name', 'name')->prepend('choose country');
      $regions = Region::all();
      $allsubregions = Subregion::all()->whereNotNull('id')->pluck('id');

      $managers =User::where('account_type', 'Shop-Attendee')->get();

      return view('app.warehousing.create', compact('country','managers','allsubregions','regions'));
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $this->validate($request, [
         'name' => 'required|unique:warehouse',
         'warehouse_code' => 'required|unique:warehouse',
         'region_id' => 'required',
         'subregion_id' => 'required',
      ]);
      //check if has main
      if ($request->is_main == 'Yes') {
         //$checkMain = warehousing::where('business_code', Auth::user()->business_code)->where('is_main', 'Yes')->count();
         $checkMain = warehousing::where('is_main', 'Yes')->count();
         if ($checkMain > 0) {
           // warehousing::where('business_code', Auth::user()->business_code)->where('is_main', 'Yes')->update(['is_main' => NULL]);
            warehousing::where('is_main', 'Yes')->update(['is_main' => NULL]);
         }
      }

      $warehouse = new warehousing;
      $warehouse->business_code = Auth::user()->business_code;
      $warehouse->warehouse_code = $request->warehouse_code;
      $warehouse->name = $request->name;
      $warehouse->country = 'Kenya';
      $warehouse->region_id = $request->region_id;
      $warehouse->subregion_id = $request->subregion_id;
      $warehouse->phone_number = 0000000;
      $warehouse->email = '';
      $warehouse->manager = '';
      $warehouse->status = $request->status;
      $warehouse->is_main = $request->is_main;
      $warehouse->created_by = Auth::user()->user_code;
      $warehouse->save();

      Session()->flash('success', 'Warehouse added successfully');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Adding a Warehouse';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Creating a warehouse';
      $activityLog->action = 'User '.auth()->user()->name.' Created warehouse '.$request->name;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      return redirect()->route('warehousing.index');
   }
   public function products($code)
   {
      $warehouse= warehousing::where('warehouse_code',$code)->first();
      if (!empty($warehouse)) {
         //$products = product_information::with('Inventory', 'ProductPrice', 'ProductSKU')->where('warehouse_code', $code)->paginate($this->perPage);
         session(['warehouse_code' => $warehouse->warehouse_code]);
         return view('app.warehousing.products', compact( 'warehouse'));
      }
      else{
         return redirect()->back();
      }
   }
   public function assign(Request $request)
   {
      $code = $request->query('warehouse_code');
      $warehouse = warehousing::where('warehouse_code', $code)->first();
      $shopattendee = User::where('account_type', 'shop-attendee')->get();

      return view('livewire.warehousing.assign-shop-attendee',  compact('warehouse', 'shopattendee', 'code'));
   }
   public function assignwarehouse(Request $request)
   {
      $warehouse = warehousing::where('warehouse_code', $request->warehouse)->first();
      $this->validate($request, [
         'shopattendee' => 'required|array',
         'shopattendee.*' => [
            'exists:users,user_code',
            Rule::unique('warehouse_assigns', 'manager')
               ->whereNull('warehouse_code')
               ->where(function ($query) use ($request) {
                  $query->where('warehouse_code', $request->warehouse);
               }),
         ],
         'position' => 'required|array',
         'position.*' => 'required',
      ], [
         'shopattendee.*.unique' => 'User :input is already assigned to a warehouse.',
         'position.required' => 'The position field is required.',
         'position.*.required' => 'Please specify a position for all shop attendees.',
      ]);

      $warehouseCode = $request->input('warehouse');
      $shopattendee = $request->input('shopattendee');
      $positions = $request->input('position');
      $check=warehouse_assign::where('manager',$shopattendee)->get();
      if ($check->count()>0){
         session()->flash('error', 'Could not assign, User(s) is already assigned to a warehouse');
         return redirect()->back();
      }
      $assignedUsers = [];
      foreach ($shopattendee as $key => $manager) {
         $user = User::where('user_code', $manager)->first();
         if ($user) {
            $assign = new warehouse_assign();
            $assign->manager = $user->user_code;
            $assign->warehouse_code = $warehouseCode;
            $assign->position = $positions[$key];
            $assign->created_by = Auth::user()->user_code;
            $assign->save();

            $assignedUsers[] = $user->name . ' (' . $positions[$key] . ')';
         }else{
            session()->flash('error', 'Could not assign User(s) to a warehouse');
            return redirect()->route('warehousing.index');
         }
      }

      $action = 'Warehouse ' . $warehouse->name . ' was assigned to ';
      $action .= implode(', ', $assignedUsers);

      $random=Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Assigning a user to a warehouse';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Warehouse assigning ';
      $activityLog->action = $action;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = $request->ip();
      $activityLog->save();
      session()->flash('success', 'User(s) successfully assigned to a warehouse');
      return redirect()->route('warehousing.index');
   }
   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show(Request $request)
   {
      $code=$request->query('warehouse_code');

      $attendees=warehouse_assign::where('warehouse_code',$code)->with('managers', 'user','updatedBy')->get();

      $warehouse = warehousing::where('warehouse_code',$code)->with('subregion','region')->first();
      return view('app.warehousing.view', compact('warehouse', 'attendees'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($code)
   {
      $country = country::pluck('name', 'name')->prepend('choose country');
      $edit = warehousing::where('warehouse_code', $code)->with('region', 'subregion')->first();
      $regions=Region::all();

      return view('app.warehousing.edit', compact('country', 'edit', 'regions'));
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $code)
   {
      $this->validate($request, [
         'name' => 'required',
      ]);

      //check if has main
      if ($request->is_main == 'Yes') {
         $checkMain = warehousing::where('business_code', Auth::user()->business_code)->where('warehouse_code', $code)->where('is_main', 'Yes')->count();
         if ($checkMain > 0) {
            warehousing::where('business_code', Auth::user()->business_code)->where('warehouse_code', $code)->where('is_main', 'Yes')->update(['is_main' => NULL]);
         }
      }

      $warehouse = warehousing::where('business_code', Auth::user()->business_code)->where('warehouse_code', $code)->first();
      $warehouse->business_code = Auth::user()->business_code;
      $warehouse->name = $request->name;
      $warehouse->region_id = $request->region_id ?? $warehouse->region_id;
      $warehouse->subregion_id = $request->subregion_id ?? $warehouse->subregion_id;
      $warehouse->status = $request->status ?? $warehouse->status;
      $warehouse->is_main = $request->is_main ?? $warehouse->is_main;
      $warehouse->updated_by = Auth::user()->user_code;
      $warehouse->save();

      Session()->flash('success', 'Warehouse updated successfully');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Updating a Warehouse';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Warehouse Detail Update';
      $activityLog->action = 'User '.auth()->user()->name.' Updated details for warehouse '.$request->name;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      return redirect()->route('warehousing.index');
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($code)
   {
      $checkIfMain = warehousing::where('business_code', Auth::user()->business_code)->where('warehouse_code', $code)->first();
      if ($checkIfMain->is_main != 'Yes') {
         return 'working on delete parameters';

         //recorord activity
         $activities = '<b>' . Auth::user()->name . '</b> Has <b>Deleted</b> warehouse <i> ' . $checkIfMain->name . '</i>';
         $section = 'Warehouse';
         $action = 'Update';
         $businessID = Auth::user()->business_code;
         $activityID = $checkIfMain->warehouse_code;

         Helper::activity($activities, $section, $action, $activityID, $businessID);
      } else {

         Session()->flash('warning', 'This warehouse is linked as the main warehouse, it can not the deleted');

         return redirect()->back();
      }

      return redirect()->back();
   }
}

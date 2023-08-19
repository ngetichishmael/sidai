<?php
namespace App\Helpers;
use App\Models\accounts;
use App\Models\creditnote\creditnote;
use App\Models\creditnote\creditnote_settings;
use App\Models\creditnote\invoice_creditnote;
use App\Models\currency;
use App\Models\customer\customer_group;
use App\Models\customer\customers;
use App\Models\expense\expense;
use App\Models\expense\expense_category;
use App\Models\income\category as income_category;
use App\Models\invoice\invoice_payments;
use App\Models\invoice\invoice_products;
use App\Models\invoice\invoice_settings;
use App\Models\invoice\invoices;
use App\Models\lpo\lpo_products;
use App\Models\lpo\lpo_settings;
use App\Models\payments\flow;
use App\Models\payments\payment_type;
use App\Models\products\attributes;
use App\Models\products\brand;
use App\Models\products\brand_product;
use App\Models\products\category;
use App\Models\products\product_category_product_information;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use App\Models\products\tags;
use App\Models\quotes\quote_settings;
use App\Models\quotes\quotes;
use App\Models\salesorder\salesorder_settings;
use App\Models\subscriptions\settings as subscription_settings;
use App\Models\suppliers\suppliers;
use App\Models\tax;
use App\Models\wingu\business_gateways;
use App\Models\wingu\business_payment_integrations;
use App\Models\wingu\file_manager;
use Auth;
use DB;
use Hr;
use Mail;
use Wingu;

class Finance
{
	public function __construct(){
      $this->middleware('auth');
	}

	//======================================= product   =========================================
	//=============================================================================================---->
	public static function check_account_payment_method($id){
		$check = payment_type::where('id',$id)->where('businessID',Auth::user()->businessID)->count();
		return $check;
	}

	public static function account_payment_method($id){
		$method = payment_type::where('businessID',Auth::user()->businessID)->where('id',$id)->first();
		return $method;
	}

	//check payment system
	public static function check_system_payment($id){
		$check = payment_type::where('id',$id)->where('businessID',0)->count();
		return $check;
	}

	//get payment system
	public static function system_payment($id){
		$method = payment_type::where('businessID',0)->where('id',$id)->first();
		return $method;
	}

	//product info
	public static function product($id){
		$product = product_information::where('businessID',Auth::user()->businessID)->where('id',$id)->first();
		return $product;
	}

	public static function check_product($id){
		$count = product_information::where('businessID',Auth::user()->businessID)->where('id',$id)->count();
		return $count;
	}

   //product price
	public static function price($id){
		$price = product_price::where('businessID',Auth::user()->businessID)->where('default_price','Yes')->where('productID',$id)->first();
		return $price;
	}


	//product price of specific  store
	public static function store_price($id){
		//get main branch
		$mainBranch = Hr::get_main_branch();
		if($mainBranch->id == Auth::user()->branch_id){
			$price = product_price::where('businessID',Auth::user()->businessID)->where('default_price','Yes')->where('productID',$id)->first();
		}elseif(Auth::user()->branch_id == Null || Auth::user()->branch_id == 0){
			$price = product_price::where('businessID',Auth::user()->businessID)->where('default_price','Yes')->where('productID',$id)->first();
		}else{
			$price = product_price::join('hr_branches','hr_branches.id','=','product_price.branch_id')
											->where('product_price.businessID',Auth::user()->businessID)
											->where('product_price.branch_id',Auth::user()->branch_id)
											->where('productID',$id)
											->first();
		}
		return $price;
	}

	//product category
	public static function product_category($id){
		$category = category::where('businessID',Auth::user()->businessID)->where('id',$id)->first();
		return $category;
	}

	//product inventory
	public static function inventory($id){
		$inventory = product_inventory::where('businessID',Auth::user()->businessID)->where('default_inventory','Yes')->where('productID',$id)->first();
		return $inventory;
	}

	//product inventory for store
	public static function store_inventory($id){
		$mainBranch = Hr::get_main_branch();
		if($mainBranch->id == Auth::user()->branch_id){
			$inventory = product_inventory::where('businessID',Auth::user()->businessID)->where('default_inventory','Yes')->where('productID',$id)->first();
		}elseif(Auth::user()->branch_id == Null || Auth::user()->branch_id == 0){
			$inventory = product_inventory::where('businessID',Auth::user()->businessID)->where('default_inventory','Yes')->where('productID',$id)->first();
		}else{
			$inventory = product_inventory::join('hr_branches','hr_branches.id','=','product_inventory.branch_id')
													->where('product_inventory.businessID',Auth::user()->businessID)
													->where('product_inventory.branch_id',Auth::user()->branch_id)
													->where('productID',$id)
													->first();
		}
		return $inventory;
	}

	//check if product is linked to specific store
	public static function check_product_store_link($id){
		$check = product_inventory::where('businessID',Auth::user()->businessID)->where('productID',$id)->count();
		return $check;
	}

	//products by category
	public static function products_by_category_count($id){
		$products = product_category_product_information::join('product_information','product_information.id','=','product_category_product_information.productID')
			->where('businessID',Auth::user()->businessID)
			->where('productID',$id)
			->count();

		return $products;
	}

	//products in a category
	public static function products_in_category($id){
		$count = product_category_product_information::where('categoryID',$id)->count();

		return $count;
	}

	//get products in a category
	public static function get_products_categories($id){
		$categories = product_category_product_information::join('product_category','product_category.id','=','product_category_product_information.categoryID')
									->where('productID',$id)
									->where('businessID',Auth::user()->businessID)
									->get();
		return $categories;
	}

	//products by brand
	public static function products_by_brand_count($id){
		$brands = brand_product::join('product_information','product_information.id','=','brand_product.product_id')
			->where('product_information.businessID',Auth::user()->businessID)
			->where('brands_id',$id)
			->count();

		return $brands;
	}

	//products by tags
	public static function products_by_tags_count($id){
		$tags = tags::join('product_information','product_information.id','=','product_tag.product_id')
						->where('product_information.businessID',Auth::user()->businessID)
						->where('tags_id',$id)
						->count();

		return $tags;
	}

	//get products in a tags
	public static function get_products_by_tags($id){
		$tags = tags::join('product_tag','product_tag.tags_id','=','tags.id')
						->where('businessID',Auth::user()->businessID)
						->where('product_id',$id)
						->get();

		return $tags;
	}


	//products variants
	public static function products_variants($id){
		$variables = product_information::join('product_price','product_price.productID','=','product_information.id')
                           ->join('product_attributes','product_attributes.id','=','product_information.valueID')
                           ->where('product_information.parentID',$id)
                           ->select('*','product_information.id as prodID')
                           ->get();
		return $variables;
	}

	//attributes
	public static function products_attributes($id){
		$attribute = attributes::where('id',$id)
                           ->where('businessID',Auth::user()->businessID)
                           ->first();
		return $attribute;
	}

	//check cover image
	public static function check_product_image($id){
		$check = file_manager::where('fileID',$id)->where('cover',1)->where('businessID',Auth::user()->businessID)->where('section','products')->count();
		return $check;
	}

	//get cover image
	public static function product_image($id){
		$image = file_manager::where('fileID',$id)->where('cover',1)->where('businessID',Auth::user()->businessID)->where('section','products')->first();
		return $image;
	}

	//product count
	public static function product_count(){
		$count = product_information::where('businessID',Auth::user()->businessID)->count();
		return $count;
	}

	//count invoice products
	public static function count_invoice_products($id){
		$count = invoice_products::where('productID',$id)->where('businessID',Auth::user()->businessID)->sum('quantity');
		return $count;
	}


	//count invoice pers salesperson
	public static function count_invoice_salesperson($id){
		$count = invoices::where('salesperson',$id)
					->where('businessID',Auth::user()->businessID)
					->count();
		return $count;
	}

   //inventory notifications
   // public static function inventory_notification($productID){
   //    //get product info
   //    $product = product_information::join('product_inventory','product_inventory.id','=','product_information.id')
   //                                  ->join('business','business.id','=','product_information.businessID')
   //                                  ->where('businessID',Auth::user()->businessID)
   //                                  ->where('product_information.id',$productID)
   //                                  ->first();

   //    //get inventory settings
   //    $settings = pos_settings::where('business_code',Auth::user()->code)->first();

   //    $inventroyNotification = product_inventory::where('businessID',Auth::user()->businessID)
   //                                              ->where('default_inventory','Yes')
   //                                              ->where('productID',$request->productID[$k])
   //                                              ->first();
   //    $inventroyNotification->notification = $inventroyNotification->notification + 1;
   //    $inventroyNotification->save();

   //    //send email
   //    $subject = $product->product_name.' Inventory Notification';
   //    $to = $settings->notification_email;
   //    $content = '<p>The following product needs to be restocked<br><b>Product:</b> '.$product->product_name.'<br><b>Current Stock</b> '.$product->current_stock.'<br> <b>Reorder Quantity</b> '.$product->reorder_qty.'</p><p>Login to <a href="https://cloud.winguplus.com/">winguPlus</a> to update your stock</p>';

   //    Mail::to($to)->send(new systemMail($content,$subject));

   // }

	//======================================= suppliers  =========================================
	//=============================================================================================---->
	public static function supplier($id){
		$vendor = suppliers::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
		return $vendor;
	}

	// check if client exists
	public static function check_supplier($id){
		$check = suppliers::where('id',$id)->count();
		return $check;
	}

	// check if client exists
	public static function count_suppliers(){
		$count = suppliers::where('businessID',Auth::user()->businessID)->count();
		return $count;
	}

	//======================================= brans  =========================================
	//=============================================================================================---->
	public static function brand($id){
		$brand = brand::where('businessID',Auth::user()->businessID)->where('id',$id)->first();
		return $brand;
	}

	//check brand
	public static function check_brand($id){
		$check = brand::where('businessID',Auth::user()->businessID)->where('id',$id)->count();
		return $check;
	}

	//======================================= customers  =========================================
	//=============================================================================================---->

	// check if client exists
	public static function check_client($id){
		$check = customers::where('id',$id)->where('businessID',Auth::user()->businessID)->count();
		return $check;
	}

	//client information
	public static function client($id){
		$client = customers::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
		return $client;
	}

	public static function total_client_invoices($id){
		$total = invoices::where('customerID',$id)->where('businessID',Auth::user()->businessID)->count();
		return $total;
	}

	//count customers
	public static function count_customers(){
		$count = customers::where('businessID',Auth::user()->businessID)->whereNull('category')->count();
		return $count;
	}

	//count customers
	public static function count_leads(){
		$count = customers::where('businessID',Auth::user()->businessID)->where('category','Lead')->count();
		return $count;
	}

	//check product cover
	public static function check_cover($id){
		$cover = file_manager::where('product_id',$id)->where('cover',1)->count();
		return $cover;
	}

	//check product cover
	public static function cover_image($id){
		$cover = file_manager::where('product_id',$id)->where('cover',1)->first()->image;
		return $cover;
	}

	//get tax info
	public static function tax($id){
		$tax = tax::find($id);
		return $tax;
	}

	//get currency
	public static function currency($id){
		$currency = currency::find($id);
		return $currency;
	}

	//client category
	public static function client_category($id){
		$category = customer_group::join('customer_groups','customer_groups.id','=','customer_group.groupID')
						->where('customerID',$id)
						->where('customer_groups.businessID',Auth::user()->businessID)
						->get();
		return $category;
	}

	//======================================= sales orders  =========================================
	//=============================================================================================---->
	public static function salesorder_setting(){
		$estimate = salesorder_settings::where('businessID',Auth::user()->businessID)->first();
		return $estimate;
	}

	//create settings when not created
	public static function salesorder_setting_setup(){
		$create = new salesorder_settings;
		$create->number = 0;
		$create->prefix = 'SO';
		$create->businessID = Auth::user()->businessID;
		$create->created_by = Auth::user()->id;
		$create->save();
	}


	//======================================= quotes  =========================================
	//=============================================================================================---->
	public static function quote(){
		$invoice = quote_settings::where('businessID',Auth::user()->businessID)->first();;
		return $invoice;
    }

    //create settings when not created
	public static function quote_setting_setup(){
		$create = new quote_settings;
		$create->number = 0;
		$create->prefix = 'QUOTE';
		$create->businessID = Auth::user()->businessID;
		$create->userID = Auth::user()->id;
		$create->save();
	}

	public static function count_quote(){
		$count = quotes::where('businessID',Auth::user()->businessID)->count();
		return $count;
	}

	//======================================= lpos  =========================================
	//=============================================================================================---->
	public static function lpo(){
		$invoice = lpo_settings::where('businessID',Auth::user()->businessID)->first();;
		return $invoice;
	}

	public static function lpo_count(){
		$count = lpo_settings::where('businessID',Auth::user()->businessID)->count();;
		return $count;
	}

	public static function lpo_items($id){
		$count = lpo_products::where('lpoID',$id)->count();
		return $count;
	}

	//create settings when not created
	public static function lpo_setting_setup(){
		$create = new lpo_settings;
		$create->number = 0;
		$create->prefix = 'LPO';
		$create->businessID = Auth::user()->businessID;
		$create->created_by = Auth::user()->id;
		$create->save();
	}


	//======================================= credit note  =========================================
	//=============================================================================================---->
	public static function creditnote(){
		$invoice = creditnote_settings::where('businessID',Auth::user()->businessID)->first();;
		return $invoice;
	}

	//create settings when not created
	public static function creditnote_setting_setup(){
		$create = new creditnote_settings;
		$create->number = 0;
		$create->prefix = 'CN';
		$create->businessID = Auth::user()->businessID;
		$create->userID = Auth::user()->id;
		$create->save();
	}

	//======================================= invoice  =========================================
	//=============================================================================================---->
	public static function invoice_products($id){
		$payment = invoice_products::where('invoiceID',$id)->where('businessID',Auth::user()->id)->first();
		return $payment;
	}

	//invoice payments
	public static function check_payment($id){
		$check = invoice_payments::where('invoiceID',$id)->where('businessID',Auth::user()->id)->count();
		return $check;
	}

	public static function invoice_payment($id){
		$payment = invoice_payments::where('invoiceID',$id)->where('businessID',Auth::user()->id)->first();
		return $payment;
	}

	public static function all_invoice_payments($id){
		$check = invoice_payments::where('invoiceID',$id)->where('businessID',Auth::user()->id)->get();
		return $check;
	}

	public static function invoice_creditnote($id){
		$credit = invoice_creditnote::join('creditnote','creditnote.id','=','invoice_creditnote.creditID')
					->where('invoiceID',$id)
					->where('creditnote.businessID',Auth::user()->businessID)
					->select('*','invoice_creditnote.created_at as creditnoteinvoicedate')
					->get();
		return $credit;
	}

	public static function invoice($id){
		$invoice = invoices::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
		return $invoice;
	}

	public static function invoice_settings(){
		$invoice = invoice_settings::where('businessID',Auth::user()->businessID)->first();
		return $invoice;
	}

	//create settings when not created
	public static function invoice_setting_setup(){
		$create = new invoice_settings;
		$create->number = 0;
		$create->prefix = 'INV';
		$create->businessID = Auth::user()->businessID;
		$create->userID = Auth::user()->id;
		$create->save();
	}

	//count invoice
	public static function count_invoice(){
		$count = invoices::where('businessID',Auth::user()->businessID)->count();
		return $count;
	}

	//count

	//======================================= payments   =========================================
	//=============================================================================================---->
	//check default payment method
	public static function check_default_payment_method($id){
		$check = payment_type::where('businessID',0)->where('id',$id)->count();
		return $check;
	}

	//get default payment method
	public static function default_payment_method($id){
		$method = payment_type::where('businessID',0)->where('id',$id)->first();
		return $method;
	}

	//get account payment method information
	public static function payment_method($id){
		$payment = payment_type::where('businessID',Auth::user()->businessID)->where('id',$id)->first();
		return $payment;
	}

	//check account paymemnt method
	public static function check_payment_method($id){
		$check = payment_type::where('businessID',Auth::user()->businessID)->where('id',$id)->count();
		return $check;
	}

	public static function payment_gateway($getawayID){
		$gateway = business_gateways::where('id',$getawayID)->where('businessID',Auth::user()->businessID)->first();
		return $gateway;
	}

	public static function flow($invoiceID,$payCredit,$section){
		$flow = new flow;
		$flow->invoiceID = $invoiceID;
		$flow->payment_credit_id = $payCredit;
		$flow->section = $section;
		$flow->businessID = Auth::user()->businessID;
		$flow->save();

		$success = 'done';

		return $success;
	}

	public static function delete_flow($invoiceID,$payCredit,$section){
		$flow = flow::where('invoiceID',$invoiceID)
					->where('payment_credit_id',$payCredit)
					->where('section',$section)
					->where('businessID',Auth::user()->businessID)
					->delete();

		$success = 'done';

		return $success;
	}

	//======================================= subscription   =========================================
	//=============================================================================================---->
	public static function subscription_settings(){
		$settings = subscription_settings::where('businessID',Auth::user()->businessID)->first();
		return $settings;
	}

   //======================================= attributes   =========================================
	//=============================================================================================---->
	public static function values_per_attribute($id){
		$values = attributes::where('businessID',Auth::user()->businessID)->where('parentID',$id)->count();
		return $values;
	}

	//======================================= expence  ============================================
	//=============================================================================================---->
	public static function count_expense(){
		$count = expense::where('businessID',Auth::user()->businessID)->count();
		return $count;
	}

	public static function count_expense_category(){
		$count = expense_category::where('businessID',Auth::user()->businessID)->count();
		return $count;
	}

	public static function expense_category($id){
		$category = expense_category::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
		return $category;
	}

	public static function check_expense_category($id){
		$category = expense_category::where('id',$id)
												->where('businessID',Auth::user()->businessID)
												->count();
		return $category;
	}

	//======================================= creditnote   =========================================
	//=============================================================================================---->
	//count creditnote
	public static function count_creditnote(){
		$count = creditnote::where('businessID',Auth::user()->businessID)->count();
		return $count;
	}


	//======================================= Bank and cash   =========================================
	//=============================================================================================---->
	public static function count_bank_and_cash(){
		$count = accounts::where('businessID',Auth::user()->businessID)->count();
		return $count;
	}

	//======================================= income category   =========================================
	//=============================================================================================---->

	public static function income_category($id){
		$category = income_category::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
		return $category;
	}

	public static function check_income_category($id){
		$count = income_category::where('id',$id)->where('businessID',Auth::user()->businessID)->count();
		return $count;
	}

	public static function original_income_category($id){
		$category = income_category::where('id',$id)->where('businessID',0)->first();
		return $category;
	}

	//======================================= reports   =========================================
	//=============================================================================================---->
	//check invoice income category within a period
	public static function check_invoice_in_category_by_period($id,$from,$to){
		$check = invoices::where('businessID',Auth::user()->businessID)->whereBetween('invoice_date',[$from,$to])->where('income_category',$id)->count();
		return $check;
	}

	//get invoices by income category
	public static function invoices_per_income_category($id,$from,$to){
		$invoices = invoices::where('businessID',Auth::user()->businessID)
									->whereBetween('invoice_date',[$from,$to])
									->where('income_category',$id)
									->groupby('income_category')
									->orderby('id','desc')
									->get();
		return $invoices;
	}

	//get invoices by income category
	public static function invoices_per_income_category_sum($id,$from,$to){
		$sum = invoices::where('businessID',Auth::user()->businessID)->whereBetween('invoice_date',[$from,$to])->where('income_category',$id)->sum('main_amount');
		return $sum;
	}

	//get expense by category within a period
	public static function check_expense_per_category_by_period($id,$from,$to){
		$check = expense::whereBetween('date',[$from,$to])->where('expense_category',$id)->count();
		return $check;
	}

	public static function expense_per_category($id,$from,$to){
		$expenses = expense::where('businessID',Auth::user()->businessID)
									->whereBetween('date',[$from,$to])
									->where('expense_category',$id)
									->groupby('expense_category')
									->orderby('id','desc')
									->get();
		return $expenses;
	}

	public static function expense_per_category_sum($id,$from,$to){
		$sum = expense::where('businessID',Auth::user()->businessID)->whereBetween('date',[$from,$to])->where('expense_category',$id)->sum('amount');
		return $sum;
	}

	//check if product has been sold
	public static function product_sales_report($id,$from,$to){
		$sales = invoice_products::join('invoices','invoices.id','=','invoice_products.invoiceID')
										->where('invoice_products.productID',$id)
										->whereBetween('invoice_date',[$from,$to])
										->select('invoice_products.quantity as quantity',DB::raw('sum(quantity) quantity'))
										->first();

		return $sales;
	}


	//================================ business payment integration   ==================================
	//=============================================================================================---->
	public static function check_business_payment_integrations($integration){
		$check = business_payment_integrations::where('business_code',wingu::business()->businessID)->where('integrationID',$integration)->count();
		return $check;
	}

}

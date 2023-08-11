<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\products\category;
use App\Models\products\product_information;

/**
 * @group Product categories
 *
 * APIs to manage the product categories
 * */
class productCategoriesController extends Controller
{
   /**
   * Category List
   *
   * @param $businessCode
   **/
   public function index($businessCode){
      $categories = category::where('business_code',$businessCode)->orderBy('id','desc')->get();

      $categories = category::where('business_code',$businessCode)->orderBy('id','desc')->get();

      return response()->json([
         "success" => true,
         "message" => "Category List",
         "data" => $categories
      ]);
   }

   /**
   * Products by category
   *
   * @param $category_id
   **/
   public function products_by_category($categoryID){
      $category = category::find($categoryID);
      $products = product_information::join('product_inventory','product_inventory.productID','=','product_information.id')
                  ->join('product_price','product_price.productID','=','product_information.id')
                  ->where('product_information.category',$category->name)
                  ->whereNull('parentID')
                  ->where('default_inventory','Yes')
                  ->where('default_price','Yes')
                  ->select('product_information.id as productID','product_information.created_at as date','product_price.selling_price as price','product_information.product_name as product_name','product_inventory.current_stock as stock','product_information.created_at as date','product_information.business_code as business_code','sku_code','brand','category')
                  ->get();

      return response()->json([
         "success" => true,
         "message" => "Product List",
         "products" => $products,
         "category" => $category,
      ]);
   }

   public function getCategory()
   {

      $data=category::with('ProductInformation.ProductPrice')->get();

      return response()->json([
         "success" => true,
         "message" => "Catergory with Product Information and Product Price",
         "Data" => $data,
      ]);
   }
}

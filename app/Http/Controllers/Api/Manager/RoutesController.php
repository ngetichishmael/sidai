<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Routes;
use Illuminate\Http\Request;

class RoutesController extends Controller
{
   public function getRoutes(Request $request)
   {
      $arrayData = [];
      // $datas = RouteResource::collection(
      //    Routes::withCount(['RouteSales'])->with('RouteSales.User')->get()
      // );
      $datas = Routes::withCount(['RouteSales'])->with('RouteSales.User')->get();


      foreach ($datas as $value) {
         $data["id"] = $value["id"];
         $data["name"] = $value["name"];
         $data["status"] = $value["status"];
         $data["Type"] = $value["Type"];
         $data["start_date"] = $value["start_date"];
         $data["end_date"] = $value["end_date"];
         $data["users_count"] = 0;
         $data["users"] = [];

         foreach ($value->RouteSales as $values) {
            $data["users_count"] = count($values->User);
            if (count($values->User) > 0) {
               $data["users"] = UserResource::collection($values->User);
            } else {
               $data["users"] = $this->emptyFilterUsers();
            }
         }

         array_push($arrayData, $data);
      }

      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Routes data",
         'array' => $arrayData,

      ]);
   }
   public function filterUsers($data)
   {
      $arrayData = "No users found";
      if ($data !== null) {
         $arrayData["id"] = $data["id"];
         $arrayData["name"] = $data["name"];
         $arrayData["user_code"] = $data["user_code"];
         $arrayData["email"] = $data["email"];
         $arrayData["location"] = $data["location"];
         $arrayData["fcm_token"] = $data["fcm_token"];
      }
   }
   public function emptyFilterUsers()
   { $arrayData = array();
      $arrayData[] = (object) array(
         "id" => 0,
         "name" => "No Sales Associate",
         "user_code" => "No Sales Associate",
         "email" => "No Sales Associate",
         "location" => "No Sales Associate",
         "fcm_token" => "No Sales Associate"
      );
      return $arrayData;
   }
}

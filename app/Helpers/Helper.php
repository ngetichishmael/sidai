<?php

namespace App\Helpers;

use App\Models\activity_log;
use App\Models\customer\customers;
use Illuminate\Support\Facades\Auth;

class Helper
{
   public static function get_timeago($ptime)
   {
      $estimate_time = time() - $ptime;

      if ($estimate_time < 1) {
         return 'less than 1 second ago';
      }

      $condition = array(
         12 * 30 * 24 * 60 * 60  =>  'year',
         30 * 24 * 60 * 60       =>  'month',
         24 * 60 * 60            =>  'day',
         60 * 60                 =>  'hr',
         60                      =>  'min',
         1                       =>  'sec'
      );

      foreach ($condition as $secs => $str) {
         $d = $estimate_time / $secs;

         if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? '' : '') . ' ago';
         }
      }
   }

   public static function seoUrl($string)
   {
      //Lower case everything
      $string = strtolower($string);
      //Make alphanumeric (removes all other characters)
      $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
      //Clean up multiple dashes or whitespaces
      $string = preg_replace("/[\s-]+/", " ", $string);
      //Convert whitespaces and underscore to dash
      $string = preg_replace("/[\s_]/", "-", $string);
      return $string;
   }

   public static function generateRandomString($length = 6)
   {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
         $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
   }

   /*======== get client ip =======*/
   public static function get_client_ip()
   {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
      else if (getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if (getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
      else if (getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if (getenv('HTTP_FORWARDED'))
         $ipaddress = getenv('HTTP_FORWARDED');
      else if (getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
      else
         $ipaddress = 'UNKNOWN';

      return $ipaddress;
   }

   /*======== like match =======*/
   public static function like_match($pattern, $subject)
   {
      $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
      return (bool) preg_match("/^{$pattern}$/i", $subject);
   }

   /*======== date difference =======*/
   public static function date_difference($enddate, $startdate)
   {
      // Declare two dates
      $start_date = strtotime($startdate);
      $end_date = strtotime($enddate);

      // Get the difference and divide into
      // total no. seconds 60/60/24 to get
      // number of days
      $difference = ($end_date - $start_date) / 60 / 60 / 24;

      if ($difference == 0) {
         $difference = 1;
      }

      return $difference;
   }


   /**============= Record Activity =============**/
   public static function activity($activities, $section, $action, $activityID, $businessID)
   {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
      else if (getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if (getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
      else if (getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if (getenv('HTTP_FORWARDED'))
         $ipaddress = getenv('HTTP_FORWARDED');
      else if (getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
      else
         $ipaddress = 'UNKNOWN';

      $activity = new activity_log;
      $activity->activity = $activities;
      $activity->section  = $section;
      $activity->action     = $action;
      $activity->userID   =  Auth::user()->id;
      $activity->activityID = $activityID;
      $activity->business_code = $businessID;
      $activity->ip_address = $ipaddress;;
      $activity->save();
   }

   /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
   /*::                                                                         :*/
   /*::  This routine calculates the distance between two points (given the     :*/
   /*::  latitude/longitude of those points). It is being used to calculate     :*/
   /*::  the distance between two locations using GeoDataSource(TM) Products    :*/
   /*::                                                                         :*/
   /*::  Definitions:                                                           :*/
   /*::    South latitudes are negative, east longitudes are positive           :*/
   /*::                                                                         :*/
   /*::  Passed to function:                                                    :*/
   /*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
   /*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
   /*::    unit = the unit you desire for results                               :*/
   /*::           where: 'M' is statute miles (default)                         :*/
   /*::                  'K' is kilometers                                      :*/
   /*::                  'N' is nautical miles                                  :*/
   /*::  Worldwide cities and other features databases with latitude longitude  :*/
   /*::  are available at https://www.geodatasource.com                          :*/
   /*::                                                                         :*/
   /*::  For enquiries, please contact sales@geodatasource.com                  :*/
   /*::                                                                         :*/
   /*::  Official Web site: https://www.geodatasource.com                        :*/
   /*::                                                                         :*/
   /*::         GeoDataSource.com (C) All Rights Reserved 2018                  :*/
   /*::                                                                         :*/
   /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
   public static function distance($lat1, $lon1, $lat2, $lon2, $unit)
   {
      if (($lat1 == $lat2) && ($lon1 == $lon2)) {
         return 0;
      } else {
         $theta = $lon1 - $lon2;
         $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
         $dist = acos($dist);
         $dist = rad2deg($dist);
         $miles = $dist * 60 * 1.1515;
         $unit = strtoupper($unit);

         if ($unit == "K") {
            return ($miles * 1.609344);
         } else if ($unit == "N") {
            return ($miles * 0.8684);
         } else {
            return $miles;
         }
      }

      // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
      // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
      // echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
   }


   /*
   |-----------------------------------------------------------------
   |customers
	|-----------------------------------------------------------------
   */
   // check if client exists
   public static function get_customer()
   {
      $customers = customers::where('businessID', Auth::user()->businessID)->where('status', 'Active')->get();
      return $customers;
   }
}

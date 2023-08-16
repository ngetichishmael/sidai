<?php

namespace App\Jobs;

use App\Models\suppliers\suppliers;
use App\Notifications\NewOrderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNewOrderNotificationJob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   private $order;
   private $distributor;
   private $distributorid;
   private $sales;
   private $sales_number;

//public $retries =3;
   /**
     * Create a new job instance.
     *
     * @return void
     */
   public function __construct($order,$distributor,$distributorid, $sales, $sales_number )
   {
      $this->order = $order;
      $this->distributor = $distributor;
      $this->distributorid = $distributorid;
      $this->sales = $sales;
      $this->sales_number = $sales_number;
   }

    /**
     * Execute the job.
     *
     * @return void
     */
   public function handle()
   {
      $notifiableUser = suppliers::find($this->distributorid);
      if (!$notifiableUser) {
         Log::debug('No suppliers found with ID: ' . $this->distributorid);
         return;
      }
      Log::debug("++++++++++++++++++++++".$this->order );
         $notifiableUser->notify(new NewOrderNotification($this->order, $this->distributor, $this->sales, $this->sales_number));
   }
}

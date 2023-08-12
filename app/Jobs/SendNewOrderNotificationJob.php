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

//public $retries =3;
   /**
     * Create a new job instance.
     *
     * @return void
     */
   public function __construct($order,$distributor,$distributorid )
   {
      $this->order = $order;
      $this->distributor = $distributor;
      $this->distributorid = $distributorid;
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
         $notifiableUser->notify(new NewOrderNotification($this->order, $this->distributor));
   }
}

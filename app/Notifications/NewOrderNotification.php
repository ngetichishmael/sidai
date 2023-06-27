<?php

namespace App\Notifications;

use App\Models\customers;
use App\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

       $order = Orders::find($this->orderId);
       $orderDetails = '';
      if ($order){
         $customer=customers::find($order->customerID);
         foreach ($order->items as $item) {
            $orderDetails .= $item->product_name . "\t\t" . $item->quantity . "\n";
         }

         $mapLink = 'https://www.google.com/maps?q=' . $customer->latitude . ',' . $customer->longitude;

         return (new MailMessage)
            ->subject('New Order Notification')
            ->line('A new order has been placed from the Test Shop.')
            ->line('Location: ' . $mapLink)
            ->line('Order details:')
            ->line('Name                  Quantity')
            ->line($orderDetails)
            ->line('Thank you for using Sidai! For assistance, please contact us at CRM@sidai.com or through the app chat section.');
      }
      }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

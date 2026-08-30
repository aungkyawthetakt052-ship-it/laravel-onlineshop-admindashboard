<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }



    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Order Received - #' . $this->order->id)
            ->greeting('Hello Admin')
            ->line('New Order Arrived')
            ->line('**Order ID:** #' . $this->order->id)
            ->line('**Customer:** ' . ($this->order->user->name ?? 'Guest'))
            ->line('**Total:** ' . number_format($this->order->total_price ?? 0) . ' Ks')
            ->action('View order', url('/admin/order/detail/' . $this->order->id))
            ->line('Thankyou');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message'  => 'New order #' . $this->order->id . ' was arrived',
        ];
    }
}

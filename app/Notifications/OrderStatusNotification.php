<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $status
    ) {}

    public function via(object $notifiable): array
    {
        // Database + Mail နှစ်ခုလုံး ပို့ချင်ရင်
        return ['database', 'mail'];

        // Database ပဲ ပို့ချင်ရင်
        // return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = ucfirst($this->status);

        return (new MailMessage)
            ->subject("Order #{$this->order->id} Status Updated")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your order status has been changed.")
            ->line("**Order ID:** #{$this->order->id}")
            ->line("**New Status:** {$statusText}")
            ->line("**Product:** " . ($this->order->product->name ?? 'N/A'))
            ->line("**Total:** " . number_format($this->order->total_price, 2) . " Bahts")
            ->action('View Order', url('/admin/order/detail/' . $this->order->id))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id'   => $this->order->id,
            'status'     => $this->status,
            'message'    => "Order #{$this->order->id} status changed to {$this->status}",
            'product'    => $this->order->product->name ?? 'N/A',
            'total'      => $this->order->total_price,
        ];
    }
}
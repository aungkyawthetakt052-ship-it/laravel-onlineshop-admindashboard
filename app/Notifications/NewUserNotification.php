<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;   // ← ဒီလိုင်း ထည့် //For faster register//
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification implements ShouldQueue  // ← implements ShouldQueue
{
    use Queueable;

    protected User $newUser;

    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New User Registered')
            ->greeting('Hello')
            ->line('New user was registered')
            ->line('**Name:** ' . $this->newUser->name)
            ->line('**Email:** ' . $this->newUser->email)
            ->action('View users', url('/users'))
            ->line('Thank you');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->newUser->id,
            'message' => 'New user: ' . $this->newUser->name . ' was registered',
        ];
    }
}
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class notificationUser extends Notification
{
    use Queueable;

    protected $user = null;
    protected $message = null;

    protected $preview = null;
    protected $sender = null;

    protected $action = null;
    protected $url = null;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        User $user,
        $message = null,
        $previewId,
        $senderId,
        $action = '',
        $url = '/')
    {
        $this->user = $user;
        $this->message = $message;
        $this->preview = $previewId;
        $this->sender = $senderId;
        $this->action = $action;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->user->name . ',')
            ->line($this->message)
            ->action($this->action, url($this->url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->message,
            'preview' => $this->preview,
            'sender' => $this->sender,
            'url' => $this->url,
        ];
    }
}

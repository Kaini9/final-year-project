<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected string $type;
    protected string $message;
    protected string $url;
    protected ?int $actorId;
    protected ?string $actorName;

    public function __construct(string $type, string $message, string $url, ?int $actorId = null, ?string $actorName = null)
    {
        $this->type = $type;
        $this->message = $message;
        $this->url = $url;
        $this->actorId = $actorId;
        $this->actorName = $actorName;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
            'url' => $this->url,
            'actor_id' => $this->actorId,
            'actor_name' => $this->actorName,
        ];
    }
}

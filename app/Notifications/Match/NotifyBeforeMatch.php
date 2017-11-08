<?php

namespace App\Notifications\Match;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\MailMessage;
use App\Models\Match;

class NotifyBeforeMatch extends Notification  implements ShouldQueue {
    use Queueable;

    protected $match;

    /**
     * Create a new notification instance.
     *
     * @param Match $match
     */
    public function __construct(Match $match) {
        $this->match = $match;
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
        return (new MailMessage)
            ->language($notifiable->language)
            ->subject(__('mail/notifyBeforeMatch.subject', [], $notifiable->language))
            ->greeting(__('mail/global.hello', [], $notifiable->language) . ',')
            ->line(__('mail/notifyBeforeMatch.body', [
                'url' => action('Match\MatchController@showMatch', $this->match),
                'match' => $this->match->name,
            ], $notifiable->language))
            ->salutation(__('mail/global.dontReply', [], $notifiable->language));
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

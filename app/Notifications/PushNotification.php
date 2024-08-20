<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class PushNotification extends Notification
{
    use Queueable;
    public string $imageLink;
    public string $title;

    public function __construct(string $imageLink, string $title){
        $this->imageLink = 'https://ippu.org/storage/banners/'.$imageLink;
        $this->title = $title;
    }
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
                title: $this->title,
                body: 'Tap here to see more details.',
                image: $this->imageLink,
            )))
            ->data(['data1' => 'value', 'data2' => 'value2'])
            ->custom([
                'android' => [
                    'notification' => [
                        'color' => '#0A0A0A',
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
                'apns' => [
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
            ]);
    }
}

<?php

namespace App\Notifications;

use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

class roomAlert extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }
    public function toFcm($notifiable)
    {

        // return  CloudMessage::new()
        //     ->withNotification(\Kreait\Firebase\Messaging\Notification::create(
        //         'Some Notification title',
        //         'Some Notification content',
        //     ))
        //     ->withDefaultSounds();
        return  FcmMessage::create()
            // ->setData(['data' => 'value'])
            // ->setData(['sound' => 'customsound'])
            // ->setData(['click_action' => 'FLUTTER_NOTIFICATION_CLICK'])

            ->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios'))
                    ->setPayload(['aps' => ['sound' => 'customsound.aiff']])
                    ->setHeaders(['apns-priority' => '10'])
            )
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('SOS ALERT')

                ->setBody("NEW ALERT FOR ROOM {$this->room->name}."))

            ->setAndroid(
                AndroidConfig::create()
                    // ->setData(['click_action' => 'FLUTTER_NOTIFICATION_CLICK' ])
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))

                    ->setNotification(AndroidNotification::create()->setChannelId('high_importance_channel2')->setSound("customsound")->setColor('#0A0A0A'))
                //     // )->setApns(
                //     //     ApnsConfig::create()
                //     //         ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios'))
            );
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

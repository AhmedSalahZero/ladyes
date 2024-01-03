<?php
//use Kreait\Firebase\Factory;
//use Kreait\Firebase\Messaging\CloudMessage;
//use Kreait\Firebase\Messaging\Notification;
//use Kreait\Firebase\Auth;
//use Kreait\Firebase\Messaging;
use Illuminate\Support\Facades\Config;
class AppNotifications
{

    function sendToFcm($fcm, $text, $title)
    {

        $header = [];
        $header[] = 'Content-Type: application/json ';
        $header[] = 'Authorization: key=AAAAOy0AzWw:APA91bHfCIggvszKDf-9zqKyblUgerkfic1TASxJNiO7DpMCDhxDN9HkHNmt6ohNMPWOqQ4OUirOckL9R1UQ9CSH0FoCatQMdFeFr-ynJ93tm94uQIzMlOSh5K3BZbT6tyt0nrtRg4ji';

        $data = [
            "to" => $fcm,
            "notification" => [
                "sound" => "default",
                "body" => $text,
                "title" => $title,
                "content_available" => true,
                "priority" => "high"
            ],
            "data" => [
                "sound" => "default",
                "body" => $text,
                "title" => $title,
                "content_available" => true,
                "priority" => "high"
            ]
        ];

        $url = 'https://fcm.googleapis.com/fcm/send';

        $data = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;

    }

//    function __construct()
//    {
//        $this->factory = (new Factory())->withServiceAccount(__DIR__ . '/audiolaby-84683-b3c0bfb923bb.json');
//
//    }
//
//    function sendNotify($text, $title)
//    {
//        $notification = [
//            "title" => $title,
//            "body" => $text,
//        ];
//
//        $data = [];
//
//        $this->to_topic('all', $notification, $data);
//
//        return response()->json(['msg' => 'successfully send']);
//    }
//
//    function to_topic($topic, $notification, $data)
//    {
//        $this->factory->createAuth();
//        $messaging = $this->factory->createMessaging();
//        $message = CloudMessage::fromArray([
//            'topic' => $topic,
//            'notification' => $notification, // optional
//            'data' => $data, // optional
//        ]);
//
//        $sendReport = $messaging->send($message);
//
//        return $sendReport;
//
//    }



}

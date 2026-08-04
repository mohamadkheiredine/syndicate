<?php

namespace App\Traits;


use App\Helpers\OneSignalHelper;
use App\Models\PushInbox;
use App\Models\UserPushInbox;
use Illuminate\Database\Eloquent\Collection;
use App\Helpers\FilesHelper;
use Illuminate\Support\Facades\Auth;

trait HasPushNotification
{
    public function sendPush(
        Collection $users,
        string     $subject,
        string     $message,
        string     $image = null,
                   $additionalData = []
    )
    {
        $return_success = [];
        $return_error = [];
        $player_ids = [];
        $data = [];
        $info = [];

        if (!empty($additionalData)) {
            $data = $additionalData;
        } else {
            $data = [
                "type" => "normal",
                "reference_id" => null
            ];
        }

        if ($image) {
            $data['image_url'] = FilesHelper::getImageFullUrl($image);
            $info['big_picture'] = FilesHelper::getImageFullUrl($image);
        }

        // Set badge
        $info['ios_badgeType'] = 'Increase';
        $info['ios_badgeCount'] = '1';

        $headings = [
            "en" => $subject
        ];
        $contents = [
            "en" => $message
        ];

        $info['headings'] = $headings;
        $info['contents'] = $contents;
        $info['data'] = $data;
        $info['player_ids'] = $users->pluck('userPush.player_id');
        $info['filter'] = [];

        $this->saveUserPush($users, $subject, $message, $image, !empty($additionalData) ? $additionalData['type'] : null);
//        $info['data']['notification_id'] = $userPush->id;
        $result = OneSignalHelper::oneSignal($info);
        if (!empty($result['error'])) {
            return $result['error'];
        }

        return null;
    }

    private function saveUserPush($users, $subject, $message, $image = null, $type = 'single'): void
    {
        foreach ($users as $user) {
            $pushInbox = PushInbox::create([
                'subject' => $subject,
                'message' => $message,
                'image' => $image,
                'type' => $type
            ]);
            UserPushInbox::create([
                'user_id' => $user->id,
                'push_inbox_id' => $pushInbox->id
            ]);
        }
    }
}

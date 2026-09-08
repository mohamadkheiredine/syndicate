<?php

namespace App\Helpers;
use Config;

class OneSignalHelper
{
    /**
     * Player ID Validation
     *
     */
    static function validate_player_id($player_id){
        // 12345678-1234-1234-1234-123456789123
        $chunks = explode('-',$player_id);
        if(count($chunks) == 5 && strlen($chunks[0]) == 8 && strlen($chunks[1]) == 4 && strlen($chunks[2]) == 4 && strlen($chunks[3]) == 4 && strlen($chunks[4]) == 12){
            return True;
        }
        return False;
    }

    /**
     * Get OneSignal user details
     *
     */
    static function oneSignalViewUser($player_id){
        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Config::get('services.onesignal.user_url').'/'.$player_id.'?app_id='.Config::get('services.onesignal.app_id'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $err = curl_errno($ch);
            curl_close($ch);
            if($err) {
                $result['error'] = 'Curl Error: '.$err.' - Failed to Send Push';
            } else {
                if($http_status != 200){
                    $response = json_decode($response);
                    $result['error'] = $response->errors[0];
                } else {
                    $result = json_decode($response,true);
                }
            }
        } catch (\Exception $ex){
            $result['error'] = 'Exception Error: '.$ex->getMessage().' - Failed to Send Push';
        }
        return $result;
    }

    /**
     * OneSignal function
     *
     */
    static function oneSignal($info){
        $headings = $info['headings'];
        $contents = $info['contents'];
        $player_ids = $info['player_ids'];
        $included_segments = !isset($info['included_segments']) ? [] : $info['included_segments'];
        $data = $info['data'];
        $result = [];

        $fields = array(
            'app_id' => Config::get('services.onesignal.app_id'),
            'headings' => $headings,
            'contents' => $contents,
            'include_player_ids' => $player_ids,
            'included_segments' => $included_segments
            // 'send_after' => Carbon::now('UTC')->addDays(1)->format('Y-m-d H:i:s'),
            // 'send_after' => Carbon::now('UTC')->addHours(2)->format('Y-m-d H:i:s'),
        );
        // if(isset($info['send_after'])){
        //     $fields['send_after'] = $info['send_after'];
        // }
        if(!empty($info['data'])){
            $fields['data'] = $info['data'];
        }
        if(isset($info['big_picture'])){
            $fields['big_picture'] = $info['big_picture'];
        }
        // ios_attachments / ios_badgeType / ios_badgeCount are deliberately
        // NOT forwarded - the real live old CMS (mobilesyndicate.net) has no
        // iOS badge at all, and syndicate-website-master's own oneSignal()
        // never sent these either.
        $fields = json_encode($fields);
        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Config::get('services.onesignal.url'));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                'Authorization: Basic '.Config::get('services.onesignal.rest_api_key')));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_errno($ch);

            curl_close($ch);
            if ($err) {
                $result['error'] = 'Curl Error: '.$err.' - Failed to Send Push';
            } else {
                $result = self::parse_push_response($response);
            }
        } catch (\Exception $ex) {
            $result['error'] = 'Exception Error: '.$ex->getMessage().' - Failed to Send Push';
        }
        return $result;
    }

    /**
     * OneSignal parse response function
     *
     */
    private static function parse_push_response($response){
        $response = json_decode($response,true);
        $response = (array) $response;
        $data = [];
        $data['status'] = '';
        $data['message'] = '';
        $data['debugger'] = '';
        if(isset($response['id'])){
            if(!empty($response['id'])){ // 200 ok
                $data['status'] = 'OK';
                $data['message'] = 'Push Sent Successfully';
                $data['debugger'] = 'Push Sent Successfully';
            } else { // 200 no subscribed players
                $data['status'] = 'OK_WITH_ERRORS';
                if(isset($response['errors'])){
                    $data['message'] = isset($response['errors'][0]) ? $response['errors'][0] : 'All included players are not subscribed';
                    $data['debugger'] = isset($response['errors'][0]) ? $response['errors'][0] : 'All included players are not subscribed';
                }
            }
        } else {
            if(isset($response['errors'])){
                if(!empty($response['errors']['invalid_player_ids'])){ // 200 invalid player ids
                    $data['status'] = 'OK_WITH_ERRORS';
                    $data['message'] = 'Some Users are Invalid';
                    $data['debugger'] ='Some Users are Invalid';
                } else { // 400
                    $data = [];
                    $data['error'] = isset($response['errors'][0]) ? $response['errors'][0] : 'Invalid Notification Content';
                }
            } else { // 400
                $data = [];
                $data['error'] = 'Unknown Error';
            }
        }
        return $data;
    }
}

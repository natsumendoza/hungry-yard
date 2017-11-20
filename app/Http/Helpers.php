<?php
/**
 * Created by PhpStorm.
 * User: jhomar
 * Date: 10/30/17
 * Time: 1:26 AM
 */

namespace App\Http;
use Illuminate\Support\Facades\Auth;
use App\Notification;
use Illuminate\Support\Facades\Session;


class Helpers
{
    public static function storeNotification($data)
    {
        //TODO MOVE TO COMMON NOTIF STORE
        date_default_timezone_set('Asia/Manila');
        $notification           = array();
        $notification['from']   = Auth::user()->id;
        $notification['to']     = $data['to'];
        $notification['action'] = $data['action'];
        $notification['read_flag']  = config('constants.ENUM_NO');
        Notification::create($notification);
    }


    public static function getNotifications()
    {
        if (!Auth::guest())
        {
            if(Auth::user()->isOwner() OR Auth::user()->isCustomer())
            {
                Session::put('newNotifs', 0);
                $unreadNotifications = Notification::where([
                    ['to', Auth::user()->id],
                    ['read_flag', config('constants.ENUM_NO')]
                ])
                    ->get()->toArray();
                if (!empty($unreadNotifications)) {
                    Session::put('newNotifs', count($unreadNotifications));
                }

                $notifications = Notification::where('to', Auth::user()->id)
                    ->limit(5)
                    ->orderBy('created_at', 'desc')
                    ->get()->toArray();

                Session::put('notifications', $notifications);
            }
        }
    }
}
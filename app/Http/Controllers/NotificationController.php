<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotificationsData(Request $request)
    {
        // Get all unread notifications for the current user.
        $notifications = Notification::where('receiver_id', 4506385)->orderBy('created_at', 'desc')->get();
        $countUnread = Notification::
            where('status', 'unread')
            ->where('receiver_id', 4506385)
            ->count();
        // Initialize the dropdown content HTML.
        $dropdownHtml = '<form action="' . route('markAsRead') . '" method="POST" class="float-right">
                ' . csrf_field() . '
                <button type="submit" class="btn">Mark as Read</button>
            </form>';
        
        foreach ($notifications as $key => $not) {
            $id = $not->hiring_id;
            $icon = "<i class='mr-2 {$not->icon}'></i>";
            $time = "<span class='float-right text-muted text-sm'>
                        {$not->created_at->diffForHumans()}
                    </span>";

            // Determine the background color based on read/unread status.
            $backgroundColor = $not->read_status === 'unread' ? '#FCF0C1' : '#fffff';  // Change color here as needed

            switch($not->type) {
                case 'application':
                    $dropdownHtml .= 
                        "<a href='" . route('applications.view', ['hiringID' => $id]) . "' class='dropdown-item' style='background-color: {$backgroundColor}; word-wrap: break-word; text-align: justify; margin-bottom: 10px;'>
                            <p>{$not->message}{$time}</p>
                        </a>";
                    break;
                case 'reminder':
                    $dropdownHtml .= 
                        "<p class='dropdown-item' style='background-color: {$backgroundColor}; word-wrap: break-word; text-align: justify; margin-bottom: 10px;'>
                            <p>{$not->message}{$time}</p>
                        </p>";
                    break;
            }

            if ($key < count($notifications) - 1) {
                $dropdownHtml .= "<div class='dropdown-divider'></div>";
            }
        }

        // If there are no notifications, show a default message.
        if (count($notifications) == 0) {
            $dropdownHtml .= "<a href='#' class='dropdown-item text-center'>
                                No new notifications
                            </a>";
        }

        // Return the new notification data.
        return response()->json([
            'label' => $countUnread,
            'label_color' => 'light',
            'icon_color' => 'dark',
            'dropdown' => $dropdownHtml,
        ]);
    }


    public function markAsRead(Request $request)
    {
        $userType = Auth::user()->usertype;
        
        if($userType === 'hr' || $userType === 'admin'){
            $notifications = Notification::where('receiver_id', 4506385)
                                    ->where('status', 'unread')
                                    ->get();
  
            // Update the status of each notification to 'read'.
            foreach ($notifications as $notification) {
                $notification->status = 'read';
                $notification->save();
            }
        } else{
            $notifications = Notification::where('receiver_id', Auth::user()->id)
            ->where('status', 'unread')
            ->get();
            
            // Update the status of each notification to 'read'.
            foreach ($notifications as $notification) {
            $notification->status = 'read';
            $notification->save();
            }
        }
        return redirect()->back();
    }

    public function ApplicantsNotif()
    {
        // Fetch all notifications for the authenticated user
        $notifications = Notification::where('receiver_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Count unread notifications
        $countNotifUnread = Notification::where('receiver_id', Auth::user()->id)
                                        ->where('status', 'unread')
                                        ->count();

        // Prepare data to be returned as JSON
        $data = [
            'notifications' => $notifications,
            'unread_count' => $countNotifUnread,
        ];

        // Return JSON response
        return response()->json($data);
    }

}

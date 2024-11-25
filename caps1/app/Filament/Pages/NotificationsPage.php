<?php

namespace App\Filament\Pages;

use App\Mail\RegisterMail;
use App\Models\Notification;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NotificationsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-bell-alert';

    protected static string $view = 'filament.pages.notifications-page';

    public static function getNavigationBadge(): ?string
    {
        return Notification::count(); // Directly use the Notification model
    }


    public function getNotifications()
    {
        $user = Auth::user();
        return $user ? $user->notifications : collect();
    }

    public function approveNotification($notificationId)
    {
        // Retrieve the notification by ID
        $notification = Auth::user()->notifications->find($notificationId);


        // Decode the notification data to access registration_data
        $notificationData = $notification->data;

        // Extract the email from the registration_data
        $email = $notificationData['registration_data']['email'];

        // Extract the email from the registration_data
        $usrname = $notificationData['registration_data']['username'];

        //extract the student_id too
        $studentId = $notificationData['registration_data']['student_id'];


        // Example: Retrieve the user with this student_id
        $newAccountData = User::where('student_id', $studentId)->update(['status' => 'active']);

        // Send an email to the extracted email address
        Mail::to($email)->send(new RegisterMail(['usrname' => $usrname]));

        // Mark the notification as read
        $notification->delete();

        // Set a session flash message
        return redirect()->back();
    }

    public function rejectNotification($notificationId)
    {
        // Retrieve the notification directly by ID for the authenticated user
        $notification = Auth::user()->notifications->find($notificationId);

        if ($notification) {
            // Decode the notification data to access registration_data
            $notificationData = $notification->data;

            // Extract the student_id from the registration_data
            $studentId = $notificationData['registration_data']['student_id'] ?? null;

            if ($studentId) {
                // Update the user's status to 'rejected'
                User::where('student_id', $studentId)->update(['status' => 'rejected']);
            }

            // Mark the notification as read and delete it
            $notification->markAsRead();
            $notification->delete();

            // Set a session flash message
            session()->flash('message', 'Notification rejected successfully.');
        } else {
            // Handle the case where the notification wasn't found
            session()->flash('error', 'Notification not found.');
        }

        // Redirect to the appropriate page
        return redirect()->back();
    }
}

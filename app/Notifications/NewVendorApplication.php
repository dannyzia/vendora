<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVendorApplication extends Notification implements ShouldQueue
{
    use Queueable;

    public $vendor;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Vendor Application: ' . $this->vendor->shop_name)
            ->greeting('Hello Admin!')
            ->line('A new vendor application has been submitted by **' . $this->vendor->user->name . '** for the shop **' . $this->vendor->shop_name . '**.')
            ->line('Please review the application in the admin panel.')
            ->action('Review Application', url('/admin/vendors/applications/' . $this->vendor->id))
            ->line('Thank you!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_vendor_application',
            'vendor_id' => $this->vendor->id,
            'shop_name' => $this->vendor->shop_name,
            'message' => 'A new vendor application has been submitted.',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'vendor_id' => $this->vendor->id,
            'shop_name' => $this->vendor->shop_name,
        ];
    }
}

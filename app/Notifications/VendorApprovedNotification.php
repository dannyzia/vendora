<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('🎉 Your Vendor Application has been Approved!')
            ->greeting('Congratulations ' . $notifiable->name . '!')
            ->line('Great news! Your vendor application for **' . $this->vendor->shop_name . '** has been approved.')
            ->line('You can now complete your shop profile and start selling on Vendora.')
            ->action('Complete Your Profile', url('/vendor/onboarding/complete'))
            ->line('Thank you for joining the Vendora marketplace!')
            ->line('If you have any questions, feel free to contact our support team.');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'vendor_approved',
            'vendor_id' => $this->vendor->id,
            'shop_name' => $this->vendor->shop_name,
            'message' => 'Your vendor application has been approved!',
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

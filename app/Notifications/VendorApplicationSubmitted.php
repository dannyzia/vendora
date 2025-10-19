<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorApplicationSubmitted extends Notification implements ShouldQueue
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
            ->subject('We Have Received Your Vendor Application')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for submitting your vendor application for **' . $this->vendor->shop_name . '**.')
            ->line('Our team will review your application and get back to you within 24-48 hours.')
            ->line('You can check the status of your application in your vendor dashboard.')
            ->action('View Dashboard', url('/vendor/dashboard'))
            ->line('Thank you for your patience!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'vendor_application_submitted',
            'vendor_id' => $this->vendor->id,
            'shop_name' => $this->vendor->shop_name,
            'message' => 'Your vendor application has been submitted and is under review.',
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

<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $vendor;
    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vendor $vendor, string $reason)
    {
        $this->vendor = $vendor;
        $this->reason = $reason;
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
            ->subject('Update on Your Vendor Application')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for your interest in becoming a vendor on Vendora.')
            ->line('Unfortunately, we are unable to approve your application for **' . $this->vendor->shop_name . '** at this time.')
            ->line('**Reason:**')
            ->line($this->reason)
            ->line('You can reapply by updating your information and resubmitting your application.')
            ->action('Update Application', url('/vendor/onboarding/application'))
            ->line('If you have any questions or need clarification, please contact our support team.')
            ->line('We appreciate your understanding.');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'vendor_rejected',
            'vendor_id' => $this->vendor->id,
            'shop_name' => $this->vendor->shop_name,
            'reason' => $this->reason,
            'message' => 'Your vendor application has been rejected.',
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
            'reason' => $this->reason,
        ];
    }
}

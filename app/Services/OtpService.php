<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Generate and send OTP to identifier (email or phone)
     */
    public function sendOtp(string $identifier, string $type = 'email', string $purpose = 'vendor_onboarding'): array
    {
        try {
            // Check rate limiting (max 5 per day)
            $todayCount = DB::table('otp_verifications')
                ->where('identifier', $identifier)
                ->where('purpose', $purpose)
                ->whereDate('created_at', today())
                ->count();

            if ($todayCount >= 5) {
                return [
                    'success' => false,
                    'message' => 'Daily OTP limit reached. Please try again tomorrow.',
                ];
            }

            // Check cooldown (60 seconds between resends)
            $lastOtp = DB::table('otp_verifications')
                ->where('identifier', $identifier)
                ->where('purpose', $purpose)
                ->latest()
                ->first();

            if ($lastOtp && $lastOtp->last_resend_at && now()->diffInSeconds($lastOtp->last_resend_at) < 60) {
                $remainingSeconds = 60 - now()->diffInSeconds($lastOtp->last_resend_at);
                return [
                    'success' => false,
                    'message' => "Please wait {$remainingSeconds} seconds before requesting another OTP.",
                ];
            }

            // Generate 6-digit OTP
            $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP in database
            $otpRecord = DB::table('otp_verifications')->insert([
                'identifier' => $identifier,
                'type' => $type,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5), // 5 minutes validity
                'attempts' => 0,
                'resend_count' => $lastOtp ? $lastOtp->resend_count + 1 : 0,
                'last_resend_at' => now(),
                'purpose' => $purpose,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send OTP based on type
            if ($type === 'email') {
                $this->sendEmailOtp($identifier, $otp, $purpose);
            } elseif ($type === 'sms') {
                $this->sendSmsOtp($identifier, $otp, $purpose);
            } elseif ($type === 'whatsapp') {
                $this->sendWhatsAppOtp($identifier, $otp, $purpose);
            }

            Log::info("OTP sent to {$identifier} via {$type}", [
                'otp' => $otp, // Remove in production!
                'purpose' => $purpose,
            ]);

            return [
                'success' => true,
                'message' => "OTP sent successfully to {$identifier}",
                'expires_at' => now()->addMinutes(5)->toIso8601String(),
            ];

        } catch (\Exception $e) {
            Log::error('OTP send failed', [
                'identifier' => $identifier,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
            ];
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $identifier, string $code, string $purpose = 'vendor_onboarding'): array
    {
        try {
            // Get latest OTP record
            $otpRecord = DB::table('otp_verifications')
                ->where('identifier', $identifier)
                ->where('purpose', $purpose)
                ->whereNull('verified_at')
                ->latest()
                ->first();

            if (!$otpRecord) {
                return [
                    'success' => false,
                    'message' => 'No OTP found. Please request a new one.',
                ];
            }

            // Check if expired
            if (now()->greaterThan($otpRecord->expires_at)) {
                return [
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.',
                ];
            }

            // Check max attempts (3)
            if ($otpRecord->attempts >= 3) {
                return [
                    'success' => false,
                    'message' => 'Maximum attempts exceeded. Please request a new OTP.',
                ];
            }

            // Increment attempt counter
            DB::table('otp_verifications')
                ->where('id', $otpRecord->id)
                ->increment('attempts');

            // Verify OTP code
            if ($otpRecord->otp !== $code) {
                $remainingAttempts = 3 - ($otpRecord->attempts + 1);
                return [
                    'success' => false,
                    'message' => "Invalid OTP. {$remainingAttempts} attempts remaining.",
                ];
            }

            // Mark as verified
            DB::table('otp_verifications')
                ->where('id', $otpRecord->id)
                ->update([
                    'verified_at' => now(),
                    'updated_at' => now(),
                ]);

            // Update user phone_verified_at if email verification
            if ($otpRecord->type === 'email' && $purpose === 'vendor_onboarding') {
                User::where('email', $identifier)->update([
                    'email_verified_at' => now(),
                ]);
            }

            Log::info("OTP verified successfully for {$identifier}");

            return [
                'success' => true,
                'message' => 'OTP verified successfully!',
            ];

        } catch (\Exception $e) {
            Log::error('OTP verification failed', [
                'identifier' => $identifier,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Verification failed. Please try again.',
            ];
        }
    }

    /**
     * Send OTP via Email
     */
    private function sendEmailOtp(string $email, string $otp, string $purpose): void
    {
        Mail::raw(
            "Your Vendora verification code is: {$otp}\n\n" .
            "This code will expire in 5 minutes.\n" .
            "Do not share this code with anyone.\n\n" .
            "If you didn't request this code, please ignore this email.",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your Vendora Verification Code');
            }
        );
    }

    /**
     * Send OTP via SMS (SSL Wireless)
     * TODO: Implement when SSL Wireless credentials are available
     */
    private function sendSmsOtp(string $phone, string $otp, string $purpose): void
    {
        // TODO: Implement SSL Wireless SMS API
        Log::info("SMS OTP would be sent to {$phone}: {$otp}");
        
        /*
        // Example SSL Wireless implementation:
        $url = 'https://smsplus.sslwireless.com/api/v3/send-sms';
        $data = [
            'api_token' => config('services.ssl_wireless.api_token'),
            'sid' => config('services.ssl_wireless.sid'),
            'sms' => "Your Vendora verification code is: {$otp}. Valid for 5 minutes.",
            'msisdn' => $phone,
            'csms_id' => uniqid(),
        ];
        
        Http::post($url, $data);
        */
    }

    /**
     * Send OTP via WhatsApp
     * TODO: Implement when WhatsApp API is available
     */
    private function sendWhatsAppOtp(string $phone, string $otp, string $purpose): void
    {
        // TODO: Implement WhatsApp Business API
        Log::info("WhatsApp OTP would be sent to {$phone}: {$otp}");
        
        /*
        // Example WhatsApp implementation (using Twilio):
        $twilio = new \Twilio\Rest\Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        
        $twilio->messages->create(
            "whatsapp:{$phone}",
            [
                'from' => 'whatsapp:' . config('services.twilio.whatsapp_number'),
                'body' => "Your Vendora verification code is: {$otp}\nValid for 5 minutes.\nDo not share this code."
            ]
        );
        */
    }

    /**
     * Clean up old/expired OTPs (run via scheduled task)
     */
    public function cleanupExpiredOtps(): void
    {
        DB::table('otp_verifications')
            ->where('expires_at', '<', now()->subHours(24))
            ->delete();

        Log::info('Cleaned up expired OTPs');
    }
}
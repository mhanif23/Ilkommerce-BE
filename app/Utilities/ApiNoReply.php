<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ApiNoReply {
    protected $base_url;
    protected $username;
    protected $password;

    public function __construct(){
        $this->base_url = env('URL_MAIL_SSO'); // This might be used for another purpose
        $this->username = env('MAIL_USERNAME');
        $this->password = env('MAIL_PASSWORD');
    }

    public function sendMail($receiver='', $subject='', $content=''){
        try {
            Mail::send([], [], function ($message) use ($receiver, $subject, $content) {
                $message->to($receiver)
                        ->subject($subject)
                        ->setBody($content, 'text/html');
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });

            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Failed to send email', [
                'error' => $e->getMessage(),
                'receiver' => $receiver,
                'subject' => $subject,
                'content' => $content,
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

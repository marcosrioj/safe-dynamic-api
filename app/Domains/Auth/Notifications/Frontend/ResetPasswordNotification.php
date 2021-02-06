<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: ResetPasswordNotification.php
 */
namespace App\Domains\Auth\Notifications\Frontend;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class ResetPasswordNotification.
 */
class ResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $returnUrl;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token, $returnUrl = '')
    {
        $this->token = $token;
        $this->returnUrl = $returnUrl;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (!empty($this->returnUrl)) {
            $parsedUrl = parse_url($this->returnUrl);
            if (empty($parsedUrl['path'])) {
                $this->returnUrl .= '/';
            }
            $separator = (empty($parsedUrl['query'])) ? '?' : '&';
            $this->returnUrl .= $separator . "token=$this->token&email=" . $notifiable->getEmailForPasswordReset();
        } else {
            $this->returnUrl = route('frontend.auth.password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]);
        }

        return (new MailMessage)
            ->subject(__('Reset Password Notification'))
            ->line(__('You are receiving this email because we received a password reset request for your account.'))
            ->action(__('Reset Password'), $this->returnUrl)
            ->line(__('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(__('If you did not request a password reset, no further action is required.'));
    }
}

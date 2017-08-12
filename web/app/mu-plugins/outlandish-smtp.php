<?php
/**
 * Plugin Name: Outlandish SMTP Plugin
 * Plugin URI: https://outlandish.com/
 * Description: Provides a number of different ways to set how WordPress sends emails
 * Version: 1.0.0
 * Author: Outlandish
 * Author URI: https://outlandish.com/
 * License: MIT License
 */

if (env('SES_SMTP_USER') && env('SES_SMTP_PASS')) {
    // Send using SES
    add_action('phpmailer_init', function($phpmailer) {
        $phpmailer->isSMTP();
        $phpmailer->Host     = env('SES_SMTP_HOST') ?: 'email-smtp.eu-west-1.amazonaws.com';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port     = env('SES_SMTP_PORT') ?: '25';
        $phpmailer->Username = env('SES_SMTP_USER');
        $phpmailer->Password = env('SES_SMTP_PASS');
    });
} else if (env('MAILTRAP_USER') && env('MAILTRAP_PASS')) {
    // Use mailtrap
    add_action('phpmailer_init', function($phpmailer) {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = env('MAILTRAP_USER');
        $phpmailer->Password = env('MAILTRAP_PASS');
    });
} else if (env('SMTP_HOST')) {
    // Use SMTP_* env variables
    add_action('phpmailer_init', function($phpmailer) {
        $phpmailer->isSMTP();
        $phpmailer->Host       = env('SMTP_HOST');
        $phpmailer->Port       = env('SMTP_PORT') ?: '25';
        $phpmailer->SMTPSecure = env('SMTP_SECURE') ?: '';
        if (env('SMTP_USER') && env('SMTP_PASS')) {
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = env('SMTP_USER');
            $phpmailer->Password = env('SMTP_PASS');
        }
    });
}

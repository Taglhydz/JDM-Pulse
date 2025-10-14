<?php

use App\Mail\ResetPasswordMail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('send-reset-password-mail', function () {
    Mail::to('tom.vaillant.vt@gmail.com')->send(new ResetPasswordMail("Jon"));
})->purpose('Send reset password mail');
<?php

// namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\Mail;
// use Mailtrap\Bridge\Transport\MailtrapTransportFactory;
// use Symfony\Component\Mailer\Transport\Dsn;

// class MailtrapServiceProvider extends ServiceProvider
// {
//     /**
//      * Register services.
//      */
//     public function register(): void
//     {
//         //
//     }

//     /**
//      * Bootstrap services.
//      */
//     public function boot(): void
//     {
//         Mail::extend('mailtrap-sdk', function () {
//             $config = $this->app['config']->get('mail.mailers.mailtrap-sdk', []);
//             $apiKey = $config['api_key'] ?? env('MAILTRAP_API_KEY');
            
//             return (new MailtrapTransportFactory())->create(
//                 new Dsn(
//                     'mailtrap+api',
//                     $config['host'] ?? env('MAILTRAP_HOST', 'send.api.mailtrap.io'),
//                     null,
//                     null,
//                     null,
//                     ['api_key' => $apiKey]
//                 )
//             );
//         });
//     }
// }
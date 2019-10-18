<?php

namespace ComplainDesk\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use ComplainDesk\MailHandler;
use ComplainDesk\Mailers\AppMailer;
use BeyondCode\Mailbox\Facades\Mailbox;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        if ($this->app->environment() == 'production') {
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Mailbox::to('support@inbound.mouka-support.com', MailHandler::class, AppMailer::class);
    }
}

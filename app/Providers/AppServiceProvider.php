<?php

namespace App\Providers;

use App\Contracts\MailServerInterface;
use App\Models\Language;
use App\Models\Position;
use App\Repositories\GmailProviderRepository;
use App\Repositories\OutlookProviderAdapter;
use App\Repositories\SMTPProviderAdapter;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MailServerInterface::class, function ($app) {
            $position = Position::find(request()->position);
            $language = Language::find(request()->language);
            $mail_service = $language->mail_service ?? $position->mail_service;
            switch ($mail_service) {
                case 'gmail':
                    return new GmailProviderRepository();
                case 'outlook':
                    return new OutlookProviderAdapter();
                case 'smtp':
                    return new SMTPProviderAdapter();
                default:
                    abort(403);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::after(function (JobProcessed $event) {
            //Add the code that sends a websocket that the job is done
            Cache::put('queue', $event->job->resolveName());
        });
    }
}

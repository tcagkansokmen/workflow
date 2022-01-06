<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use App\Models\AssemblyMessage;
use App\Models\BriefComment;
use App\Models\BriefDesignComment;
use App\Models\ExplorationDesignComment;
use App\Models\ExplorationMessage;
use App\Models\OfferComment;
use App\Models\PrintingMessage;
use App\Models\ProductionMessage;

use App\Observers\Messages\AssemblyMessageObserver;
use App\Observers\Messages\BriefCommentObserver;
use App\Observers\Messages\BriefDesignCommentObserver;
use App\Observers\Messages\ExplorationDesignCommentObserver;
use App\Observers\Messages\ExplorationMessageObserver;
use App\Observers\Messages\OfferCommentObserver;
use App\Observers\Messages\PrintingMessageObserver;
use App\Observers\Messages\ProductionMessageObserver;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Contracts\View\Factory $factory)
    {
        AssemblyMessage::observe(AssemblyMessageObserver::class);
        BriefComment::observe(BriefCommentObserver::class);
        BriefDesignComment::observe(BriefDesignCommentObserver::class);
        ExplorationDesignComment::observe(ExplorationDesignCommentObserver::class);
        ExplorationMessage::observe(ExplorationMessageObserver::class);
        OfferComment::observe(OfferCommentObserver::class);
        PrintingMessage::observe(PrintingMessageObserver::class);
        ProductionMessage::observe(ProductionMessageObserver::class);

        $factory->composer('*', 'App\Http\View\Composers\AuthenticatedComposer');
        Carbon::setLocale('tr');
        setlocale(LC_ALL, "tr_TR.UTF-8");
        if (env('APP_ENV') === 'production') {
            \URL::forceScheme('https');
        }

    }
}

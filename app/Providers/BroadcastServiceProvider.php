<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param Request $request
     * @return void
     */
    public function boot(Request $request)
    {
        $request->hasHeader('accept') == 'application/json' ? $guard= 'auth:api' : $guard = 'web';

        Broadcast::routes(['middleware' => [$guard]]);

        require base_path('routes/channels.php');
    }
}

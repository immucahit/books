<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Log;
use App\Events\Logging as EventLogging;

class Logging
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EventLogging $event)
    {
        Log::create([
            'user_id' => $event->user,
            'table_name' => $event->model->getTable(),
            'action' => $event->action,
            'data' => $event->model->toArray()
        ]);
    }
}

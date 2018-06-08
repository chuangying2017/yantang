<?php

namespace App\Listeners\Comments;

use App\Services\Comments\Event\CommentIsCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentOperation
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
     * @param  CommentIsCreated  $event
     * @return void
     */
    public function handle(CommentIsCreated $event)
    {
        //
    }
}

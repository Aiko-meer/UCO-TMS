<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notification feed
    |--------------------------------------------------------------------------
    | window_days limits how far back the feed looks, so a newly created account
    | is not handed every request ever made. poll_seconds is how often the bell
    | refreshes itself; set it to 0 to disable polling (e.g. when Reverb is up).
    */

    'window_days' => env('NOTIFICATIONS_WINDOW_DAYS', 60),

    'limit' => env('NOTIFICATIONS_LIMIT', 15),

    'poll_seconds' => env('NOTIFICATIONS_POLL_SECONDS', 45),

];

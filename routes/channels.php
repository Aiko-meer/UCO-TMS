<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin.socmed', function ($user) {
    return $user->role === 'admin'; // change to match your app
});

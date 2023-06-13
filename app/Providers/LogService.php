<?php

namespace App\Providers;

use App\Models\Log;

class LogService
{
    public function logAction($user, $action, $description = null)
    {
        $log = new Log();
        $log->user_id = $user->id;
        $log->user_name = $user->name;
        $log->action = $action;
        $log->description = $description;
        $log->save();
    }
}

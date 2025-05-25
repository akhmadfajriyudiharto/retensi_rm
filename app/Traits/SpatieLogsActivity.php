<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait SpatieLogsActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = new LogOptions;
        $logOptions->logAll();
        $logOptions->logOnlyDirty();
        $originalUrl = request()->header('Referer', 'URL not available');

        $logOptions->useLogName('model-changes')
            ->setDescriptionForEvent(function (string $eventName) use ($originalUrl) {
                return "{$eventName} on {$originalUrl}";
            });

        return $logOptions;
    }
}

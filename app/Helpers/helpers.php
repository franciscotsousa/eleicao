<?php

use Carbon\Carbon;

function validateLastUpdateDate($dt): string
{
    $lastUpdateDate = Carbon::parse($dt);

    if ($lastUpdateDate->format('d') > 9) {
        $lastUpdateDate = $lastUpdateDate->format('Y-m-d');
    } else {
        $lastUpdateDate = $lastUpdateDate->format('Y-d-m');
    }
    return $lastUpdateDate;
}

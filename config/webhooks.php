<?php

return [
    'type' => env('WEBHOOK_TYPE', 'jobstatus'),
    'key' => env('WEBHOOK_KEY'),
    'secret' => env('WEBHOOK_SECRET')
];

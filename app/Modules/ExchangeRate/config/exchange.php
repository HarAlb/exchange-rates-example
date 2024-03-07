<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Present list of external services
    |--------------------------------------------------------------------------
    | Now we have only sberbank integration
    | delay
    */
    'sber' => [
        'url' => 'http://www.cbr.ru/scripts/XML_daily.asp',
        'delay' => '12 Hours',
        'path' => 'App\Modules\ExchangeRate\src\Services\\'
    ],
];

<?php

namespace App\Modules\ExchangeRate\src\Contract;

use App\Modules\ExchangeRate\src\ConfigDto;

interface ConfigurableContract
{
    public function getConfig() : ConfigDto;
}

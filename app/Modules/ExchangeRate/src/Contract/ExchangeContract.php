<?php

namespace App\Modules\ExchangeRate\src\Contract;

use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Support\Collection as SupportCollection;
use Illuminate\Http\Client\Response;

interface ExchangeContract
{
    public function get() : Collection|SupportCollection;

    public function transform(Response $response): SupportCollection;

    public function allowGetFromExternalService(): bool;

    public function detectAllowToGetFromExternalService(): void;
}

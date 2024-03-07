<?php

namespace App\Modules\ExchangeRate\src\Services;

use App\Modules\ExchangeRate\src\ConfigDto;
use App\Modules\ExchangeRate\src\Contract\ConfigurableContract;
use App\Modules\ExchangeRate\src\Contract\ExchangeContract;
use App\Modules\ExchangeRate\src\Models\Exchange;
use App\Modules\ExchangeRate\src\Models\ExchangeType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class SberExchangeService implements ExchangeContract, ConfigurableContract
{
    const TYPE = 'sber';

    private bool $allowGetFromExternalService;

    public function __construct(private readonly ConfigDto $config)
    {
        $this->allowGetFromExternalService = false;
    }

    public function get(): Collection|SupportCollection
    {
        $this->detectAllowToGetFromExternalService();

        if ($this->allowGetFromExternalService) {
            $exchanges = $this->transform(
                Http::get($this->getConfig()->getUrl())
            );

            $this->insert($exchanges);

            return $exchanges;
        }

        return Exchange::query()->with('exchangeType')
            ->whereRaw('DATE_FORMAT(last_update, "%Y-%m-%d") = "' . date('Y-m-d') . '"')
            ->get();
    }

    public function detectAllowToGetFromExternalService(): void
    {
        $lastDate = Exchange::query()->orderByDesc('id')->value('last_update');

        if (!$lastDate) {
            $this->allowGetFromExternalService = true;
        }

        if ($lastDate && $this->config->getDelay()) {
            $finishedDatetimeForDelay = date('Y-m-d H:i:s', strtotime($this->config->getDelay()));
            if ($lastDate > $finishedDatetimeForDelay) {
                $this->allowGetFromExternalService = true;
            }
        }
    }

    public function getConfig(): ConfigDto
    {
        return $this->config;
    }

    public function insert(Collection|SupportCollection $collection): void
    {
        Exchange::insert($collection->toArray());
    }

    public function transform(Response $response): SupportCollection
    {
        /** @var $xmlData \SimpleXMLElement */
        $xmlData = simplexml_load_string($response->body()) or die ("Error: Cannot create object");
        $insertInto = collect([]);
        $type = ExchangeType::query()->where('name', self::TYPE)->first();
        foreach ($xmlData->children() as $child) {
            $exchange = new Exchange();
            $insertInto->add(
                $exchange->fill([
                    'name' => (string) $child->Name,
                    'exchangeType' => $type,
                    'exchange_type_id' => $type->id,
                    'number_code' => (string)$child->NumCode,
                    'char_code' => (string)$child->CharCode,
                    'v_unit_rate' => (string) $child->VunitRate,
                    'last_update' => date('Y-m-d H:i:s')
                ])
            );
        }

        return $insertInto;
    }

    public function allowGetFromExternalService(): bool
    {
        return $this->allowGetFromExternalService;
    }
}

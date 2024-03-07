<?php

namespace App\Modules\ExchangeRate\src;

use App\Modules\ExchangeRate\src\Contract\ExchangeContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Detector
{
    private ExchangeContract $exchangeContract;

    public function __construct()
    {
        $type = request()->route('type');

        $config = $this->getConfiguration($type);

        $this->assignExchangeContract($type, $config);
    }

    /**
     * @return ExchangeContract
     */
    public function getExchangeContract(): ExchangeContract
    {
        return $this->exchangeContract;
    }

    public function getConfiguration(string $type): array
    {
        $existsConfig = config("exchange.$type");

        throw_if(!$existsConfig, new NotFoundHttpException('External integration not found'));

        return $existsConfig;
    }

    public function assignExchangeContract(string $type, array $config)
    {
        $path = preg_replace('@\\\\$@', '',$config['path']) ?? 'App\Modules\ExchangeRate\src\Services';

        $className = $path . '\\' . ucfirst($type) . 'ExchangeService';

        $this->exchangeContract = new $className(
            new ConfigDto(...$config)
        );
    }
}

<?php

namespace App\Modules\ExchangeRate\src;

final class ConfigDto
{
    private readonly string $url;
    private readonly ?string $delay;
    private readonly ?string $path;

    public function __construct(string $url, ?string $delay = null, ?string $path = null)
    {
        $this->url = $url;
        $this->delay = $delay;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getDelay(): ?string
    {
        return $this->delay;
    }
}

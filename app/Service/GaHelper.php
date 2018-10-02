<?php

namespace App\Service;

use Illuminate\Support\Facades\Cookie;
use Ramsey\Uuid\Uuid;
use Irazasyed\LaravelGAMP\Facades\GAMP;

class GaHelper
{
    public function clientId()
    {
        if ($clientId = Cookie::get('clientId')) {
            return $clientId;
        }

        $clientId = Uuid::uuid4()->toString();
        Cookie::queue('clientId', $clientId, 1440);

        return $clientId;
    }

    public function pageView(string $name = null)
    {
        $name = $name ?: url()->full();

        return GAMP::setClientId($this->clientId())
            ->setDocumentPath($name)
            ->sendPageview();
    }
}

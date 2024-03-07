<?php

namespace App\Modules\ExchangeRate\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ExchangeRate\src\Detector;
use Illuminate\Http\JsonResponse;

final class ExchangeController extends Controller
{
    public function index(Detector $detector): JsonResponse
    {
        return response()->json(
            $detector->getExchangeContract()->get()
        );
    }
}

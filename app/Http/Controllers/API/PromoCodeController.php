<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class PromoCodeController extends Controller
{
    use ApiResponse;

    public function validateCode($code)
    {
        $promo = PromoCode::where('code', $code)->first();

        if (!$promo || !$promo->isValid()) {
            return $this->error('Promo code is invalid or expired.');
        }

        return $this->success($promo, 'Promo code is valid.');
    }
}

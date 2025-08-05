<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class PaymentCardController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $cards = PaymentCard::where('user_id', Auth::id())->get();
        return $this->success($cards);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'card_number' => 'required|string',
            'cardholder_name' => 'required|string',
            'expiry_month' => 'required|string',
            'expiry_year' => 'required|string',
            'cvv' => 'required|string',
        ]);

        $data['user_id'] = Auth::id();
        $card = PaymentCard::create($data);
        return $this->success($card, 'Card added.');
    }

    public function show($id)
    {
        $card = PaymentCard::where('user_id', Auth::id())->findOrFail($id);
        return $this->success($card);
    }

    public function update(Request $request, $id)
    {
        $card = PaymentCard::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'card_number' => 'sometimes|string',
            'cardholder_name' => 'sometimes|string',
            'expiry_month' => 'sometimes|string',
            'expiry_year' => 'sometimes|string',
            'cvv' => 'sometimes|string',
        ]);

        $card->update($data);
        return $this->success($card, 'Card updated.');
    }

    public function destroy($id)
    {
        $card = PaymentCard::where('user_id', Auth::id())->findOrFail($id);
        $card->delete();
        return $this->success([], 'Card deleted.');
    }
}

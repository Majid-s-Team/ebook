<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class DeliveryAddressController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $addresses = DeliveryAddress::where('user_id', Auth::id())->get();
        return $this->success($addresses);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
        ]);

        $data['user_id'] = Auth::id();
        $address = DeliveryAddress::create($data);
        return $this->success($address, 'Address added.');
    }

    public function show($id)
    {
        $address = DeliveryAddress::where('user_id', Auth::id())->findOrFail($id);
        return $this->success($address);
    }

    public function update(Request $request, $id)
    {
        $address = DeliveryAddress::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'address_line1' => 'sometimes|string',
            'address_line2' => 'nullable|string',
            'city' => 'sometimes|string',
            'state' => 'sometimes|string',
            'postal_code' => 'sometimes|string',
            'country' => 'sometimes|string',
        ]);

        $address->update($data);
        return $this->success($address, 'Address updated.');
    }

    public function destroy($id)
    {
        $address = DeliveryAddress::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        return $this->success([], 'Address deleted.');
    }
}

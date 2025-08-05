<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success(DeliveryAddress::where('user_id', Auth::id())->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address_line1' => 'required',
            'address_line2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
        ]);

        $data['user_id'] = Auth::id();
        $address = DeliveryAddress::create($data);
        return $this->success($address, 'Address saved.');
    }

    public function destroy($id)
    {
        $address = DeliveryAddress::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        return $this->success([], 'Address deleted.');
    }
}
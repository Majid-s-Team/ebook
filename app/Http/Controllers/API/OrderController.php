<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, Cart, CartItem, PaymentCard};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
use App\Traits\NotifiesUsers;

class OrderController extends Controller
{
    use ApiResponse,NotifiesUsers;

    public function index(Request $request)
    {
        $query = Order::with(['items.book', 'address', 'card'])
            ->where('user_id', Auth::id());

        if ($request->filled('status')) {
            $validStatuses = ['pending', 'completed', 'cancelled'];
            if (in_array($request->status, $validStatuses)) {
                $query->where('status', $request->status);
            } else {
                return $this->error('Invalid status filter.', 422);
            }
        }

        $orders = $query->latest()->get();

        return $this->success($orders);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'delivery_address_id' => 'required|exists:delivery_addresses,id',
            'card_id' => 'required|exists:payment_cards,id'
        ]);

        $cart = Cart::with('items')->where('user_id', Auth::id())->firstOrFail();
        $card = PaymentCard::where('id', $data['card_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $order = Order::create([
            'user_id' => Auth::id(),
            'delivery_address_id' => $data['delivery_address_id'],
            'promo_code_id' => $cart->promo_code_id,
            'total_amount' => $cart->total,
            'card_id' => $card->id,
            'discount' => $cart->discount,
            'status' => 'pending'
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $item->book_id,
                'quantity' => $item->quantity,
                'price' => $item->price
            ]);
        }

        $cart->items()->delete();
        $cart->update(['subtotal' => 0, 'discount' => 0, 'total' => 0]);
        $this->notifyUser(
            Auth::id(),
            'Order Placed',
            'Your order #' . $order->id . ' has been placed successfully.',
            'order'
        );
        return $this->success($order->load('items.book'), 'Order placed successfully.');
    }

    public function show($id)
    {
        $order = Order::with('items.book', 'address', 'card')->where('user_id', Auth::id())->findOrFail($id);
        return $this->success($order);
    }
}

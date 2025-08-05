<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Cart, CartItem, PromoCode, Book};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class CartController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $cart = Cart::with('items.book')->firstOrCreate(['user_id' => Auth::id()]);
        return $this->success($cart);
    }

    public function addItem(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $book = Book::find($data['book_id']);

        $item = $cart->items()->updateOrCreate(
            ['book_id' => $book->id],
            ['quantity' => $data['quantity'], 'price' => $book->discounted_price]
        );

        $this->recalculateCart($cart);
        return $this->success($cart->load('items.book'), 'Item added to cart.');
    }

    public function removeItem(Request $request)
    {
        $data = $request->validate(['book_id' => 'required|exists:books,id']);

        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->where('book_id', $data['book_id'])->delete();
            $this->recalculateCart($cart);
        }

        return $this->success($cart->load('items.book'), 'Item removed.');
    }

    public function applyPromo(Request $request)
    {
        $data = $request->validate(['code' => 'required|string']);
        $promo = PromoCode::where('code', $data['code'])->first();

        if (!$promo || !$promo->isValid()) {
            return $this->error('Invalid or expired promo code.');
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cart->promo_code_id = $promo->id;
        $this->recalculateCart($cart);
        return $this->success($cart->load('items.book'), 'Promo applied.');
    }

    protected function recalculateCart($cart)
    {
        $subtotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $discount = 0;
        if ($cart->promoCode && $cart->promoCode->isValid()) {
            $promo = $cart->promoCode;
            $discount = $promo->type === 'percentage'
                ? ($subtotal * $promo->value / 100)
                : $promo->value;
        }

        $cart->update([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'delivery_fee' => 0,
            'total' => max($subtotal - $discount, 0),
        ]);
    }

    public function updateItemQuantity(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart) {
            return $this->error('Cart not found.');
        }

        $item = $cart->items()->where('book_id', $data['book_id'])->first();

        if (!$item) {
            return $this->error('Item not found in cart.');
        }

        if ($data['quantity'] === 0) {
            $item->delete();
            $message = 'Item removed from cart.';
        } else {
            $item->update([
                'quantity' => $data['quantity'],
                'price' => $item->book->discounted_price
            ]);
            $message = 'Item quantity updated.';
        }

        $this->recalculateCart($cart);
        return $this->success($cart->load('items.book'), $message);
    }

}

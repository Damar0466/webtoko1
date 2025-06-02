<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $akunId = Session::get('akun_id');
        $carts = Cart::with('product')->where('akun_id', $akunId)->get();
        $total = $carts->sum('total_price');
        
        return view('customer.cart', compact('carts', 'total'));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $akunId = Session::get('akun_id');
        $product = Product::find($request->product_id);

        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak cukup tersedia!');
        }

        $existingCart = Cart::where('akun_id', $akunId)
                           ->where('product_id', $request->product_id)
                           ->first();

        if ($existingCart) {
            $newQuantity = $existingCart->quantity + $request->quantity;
            
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Stok tidak cukup tersedia!');
            }
            
            $existingCart->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'akun_id' => $akunId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Produk Ditambahkan Ke Keranjang!');
    }

    public function update(Request $request, Cart $cart)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($cart->product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak cukup tersedia!');
        }

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Keranjang Berhasil Diperbarui!');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->back()->with('success', 'Item dihapus dari keranjang!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashierController extends Controller
{
    private const CART_SESSION_KEY = 'cashier.cart';

    public function index(Request $request): View
    {
        [$cart, $total] = $this->getCartAndTotal($request);
        $cartRaw = $request->session()->get(self::CART_SESSION_KEY, []);
        $cartQuantities = [];
        foreach ($cartRaw as $item) {
            $cartQuantities[(int) $item['product_id']] = (int) $item['quantity'];
        }

        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        return view('cashier.index', [
            'cart' => $cart,
            'total' => $total,
            'products' => $products,
            'cartQuantities' => $cartQuantities,
        ]);
    }

    public function addByBarcode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
        ]);

        $product = Product::where('barcode', $validated['barcode'])->first();

        if (!$product) {
            return redirect()
                ->route('cashier.index')
                ->with('error', 'Produk dengan barcode tersebut tidak ditemukan.');
        }

        return $this->addProductToCart($request, $product);
    }

    public function addByProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        return $this->addProductToCart($request, $product);
    }

    public function removeItem(Request $request, int $productId): RedirectResponse
    {
        $cart = $request->session()->get(self::CART_SESSION_KEY, []);
        unset($cart[(string) $productId]);
        $request->session()->put(self::CART_SESSION_KEY, $cart);

        return redirect()
            ->route('cashier.index')
            ->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function pay(Request $request): RedirectResponse
    {
        [$cart, $total] = $this->getCartAndTotal($request);

        if (empty($cart)) {
            return redirect()
                ->route('cashier.index')
                ->with('error', 'Keranjang kosong, tidak bisa melakukan pembayaran.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,transfer,qris',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        if ((float) $validated['paid_amount'] < (float) $total) {
            return redirect()
                ->route('cashier.index')
                ->with('error', 'Jumlah bayar kurang dari total transaksi.');
        }

        try {
            $transaction = DB::transaction(function () use ($cart, $total, $validated) {
                $invoiceCode = $this->generateInvoiceCode();
                $paidAmount = (float) $validated['paid_amount'];
                $changeAmount = $paidAmount - (float) $total;

                $transaction = Transaction::create([
                    'invoice_code' => $invoiceCode,
                    'user_id' => auth()->id(),
                    'total_amount' => $total,
                    'payment_method' => $validated['payment_method'],
                    'paid_amount' => $paidAmount,
                    'change_amount' => $changeAmount,
                    'transaction_date' => now(),
                ]);

                foreach ($cart as $item) {
                    $product = Product::query()->lockForUpdate()->find($item['product_id']);

                    if (!$product) {
                        throw new \RuntimeException("Produk {$item['name']} tidak ditemukan.");
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new \RuntimeException("Stok {$product->name} tidak mencukupi.");
                    }

                    $subtotal = $item['price'] * $item['quantity'];

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $subtotal,
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }

                return $transaction;
            });
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('cashier.index')
                ->with('error', $e->getMessage());
        }

        $request->session()->forget(self::CART_SESSION_KEY);

        return redirect()
            ->route('cashier.transactions.show', $transaction)
            ->with('success', 'Pembayaran berhasil diproses.');
    }

    public function showTransaction(Transaction $transaction): View
    {
        $transaction->load('details.product', 'user');

        return view('cashier.show', [
            'transaction' => $transaction,
        ]);
    }

    public function showReceipt(Transaction $transaction): View
    {
        $transaction->load('details.product', 'user');

        return view('cashier.receipt', [
            'transaction' => $transaction,
        ]);
    }

    public function exportReceipt(Transaction $transaction)
    {
        $transaction->load('details.product', 'user');

        $html = view('cashier.receipt', [
            'transaction' => $transaction,
            'isExport' => true,
        ])->render();

        $filename = 'struk-' . $transaction->invoice_code . '.html';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function getCartAndTotal(Request $request): array
    {
        $cart = array_values($request->session()->get(self::CART_SESSION_KEY, []));
        $total = 0;

        foreach ($cart as &$item) {
            $item['subtotal'] = $item['price'] * $item['quantity'];
            $total += $item['subtotal'];
        }

        unset($item);

        return [$cart, $total];
    }

    private function generateInvoiceCode(): string
    {
        do {
            $code = 'INV-' . now()->format('YmdHis') . '-' . random_int(100, 999);
        } while (Transaction::where('invoice_code', $code)->exists());

        return $code;
    }

    private function addProductToCart(Request $request, Product $product): RedirectResponse
    {
        if ($product->stock < 1) {
            return redirect()
                ->route('cashier.index')
                ->with('error', "Stok {$product->name} habis.");
        }

        $cart = $request->session()->get(self::CART_SESSION_KEY, []);
        $productId = (string) $product->id;
        $currentQty = isset($cart[$productId]) ? (int) $cart[$productId]['quantity'] : 0;

        if ($currentQty >= (int) $product->stock) {
            return redirect()
                ->route('cashier.index')
                ->with('error', "Jumlah {$product->name} di keranjang sudah mencapai stok.");
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'price' => (float) $product->price,
                'quantity' => 1,
            ];
        }

        $request->session()->put(self::CART_SESSION_KEY, $cart);

        return redirect()
            ->route('cashier.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }
}

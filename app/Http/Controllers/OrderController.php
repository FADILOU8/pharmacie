<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private function getPharmacy(): Pharmacy
    {
        $pharmacy = Auth::user()->currentPharmacy();

        if (!$pharmacy) {
            abort(404);
        }

        return $pharmacy;
    }

    public function index()
    {
        $pharmacy = $this->getPharmacy();
        $orders = Order::with('supplier')
            ->where('pharmacy_id', $pharmacy->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $pharmacy = $this->getPharmacy();
        $suppliers = Supplier::where('pharmacy_id', $pharmacy->id)->get();

        return view('orders.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $pharmacy = $this->getPharmacy();

        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'medicines' => 'required|array|min:1',
            'medicines.*.dci' => 'nullable|string|max:255',
            'medicines.*.name' => 'required|string|max:255',
            'medicines.*.form' => 'nullable|string|max:255',
            'medicines.*.dosage' => 'nullable|string|max:100',
            'medicines.*.lot_number' => 'required|string|max:100',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.unit_price' => 'required|numeric|min:0',
            'medicines.*.expiration_date' => 'nullable|date',
        ]);

        $supplier = Supplier::where('id', $data['supplier_id'])
            ->where('pharmacy_id', $pharmacy->id)
            ->first();

        if (!$supplier) {
            abort(403);
        }

        $totalAmount = 0;
        foreach ($data['medicines'] as $medicine) {
            $totalAmount += $medicine['quantity'] * $medicine['unit_price'];
        }

        Order::create([
            'pharmacy_id' => $pharmacy->id,
            'supplier_id' => $supplier->id,
            'order_date' => $data['order_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'medicines' => $data['medicines'],
        ]);

        return redirect()->route('orders.index')->with('success', 'Commande fournisseur créée avec succès.');
    }

    public function show(Order $order)
    {
        $pharmacy = $this->getPharmacy();

        if ($order->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        $order->load('supplier');

        return view('orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        $pharmacy = $this->getPharmacy();

        if ($order->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Vous ne pouvez supprimer qu’une commande en attente.');
        }

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Commande supprimée.');
    }

    public function receive(Request $request, Order $order)
    {
        $pharmacy = $this->getPharmacy();

        if ($order->pharmacy_id !== $pharmacy->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Cette commande a déjà été traitée.');
        }

        foreach ($order->medicines as $item) {
            $existingMedicine = Medicine::where('pharmacy_id', $pharmacy->id)
                ->where('supplier_id', $order->supplier_id)
                ->where('name', $item['name'])
                ->where('lot_number', $item['lot_number'])
                ->first();

            if ($existingMedicine) {
                $existingMedicine->quantity += $item['quantity'];
                $existingMedicine->unit_price = $item['unit_price'];
                $existingMedicine->expiration_date = $item['expiration_date'] ?? $existingMedicine->expiration_date;
                $existingMedicine->save();
            } else {
                Medicine::create([
                    'pharmacy_id' => $pharmacy->id,
                    'supplier_id' => $order->supplier_id,
                    'dci' => $item['dci'] ?? null,
                    'name' => $item['name'],
                    'form' => $item['form'] ?? null,
                    'dosage' => $item['dosage'] ?? null,
                    'lot_number' => $item['lot_number'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'expiration_date' => $item['expiration_date'] ?? null,
                ]);
            }
        }

        $order->status = 'received';
        $order->delivery_date = $order->delivery_date ?? now()->toDateString();
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Commande marquée comme reçue et le stock a été mis à jour.');
    }
}

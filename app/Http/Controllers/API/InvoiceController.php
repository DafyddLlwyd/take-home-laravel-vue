<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function show()
    {
        $invoices = Invoice::with('order')->paginate(5);

        return response()->json($invoices);
    }



    public function invoice(Request $request, $orderId)
    {
        // save invoice to storage
        $order = Order::with('items', 'user', 'items.product')->findOrFail($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // delete old invoice entry if exists
        $oldInvoice = Invoice::where('order_id', $order->id)->delete();

        // create invoice
        $invoice = new Invoice();
        $invoice->order_id = $order->id;
        $invoice->amount = $order->total_price;
        $invoice->status = 'pending';
        $invoice->save();

        // update status to invoiced
        $order->status = 'invoiced';
        $order->save();

        $pdf = PDF::loadView('pdf.invoice', compact('order'));

        $filePath = 'pdfs/' . $order->id . '_invoice.pdf';
        try {
            Storage::disk('public')->put($filePath, $pdf->output());
        } catch (\Exception $e) {
            // Log the exception
            Log::error($e->getMessage());
            // Handle the exception accordingly
        }

        return response()->json([
            'downloadUrl' => Storage::disk('public')->url($filePath),
        ]);
    }

    public function download(Request $request, $id)
    {
        $order = Order::with('items', 'user', 'items.product')->findOrFail($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $filename = $order->id . '_invoice.pdf';

        $filePath = 'pdfs/' . $filename;
        return Storage::disk('public')->download($filePath);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class ReceiptController extends Controller
{
    public function generateReceipt(Request $request)
    {
        $data = $request->all();
        
        // Calculate change if cash payment
        $change = 0;
        if ($data['paymentMethod'] === 'cash') {
            $change = $data['cashAmount'] - $data['total'];
        }

        // Generate receipt number (you might want to use a more sophisticated method)
        $receiptNumber = 'RCP-' . date('Ymd') . '-' . rand(1000, 9999);

        $pdf = PDF::loadView('pos.receipt', [
            'items' => $data['items'],
            'subtotal' => $data['subtotal'],
            'tax' => $data['tax'],
            'total' => $data['total'],
            'paymentMethod' => $data['paymentMethod'],
            'cashAmount' => $data['cashAmount'] ?? 0,
            'change' => $change,
            'receiptNumber' => $receiptNumber
        ]);

        return $pdf->download('receipt.pdf');
    }
}
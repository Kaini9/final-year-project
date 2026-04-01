<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Verification;

class KhaltiController extends Controller
{
    /**
     * Initiate the Khalti ePayment request
     */
    public function initiate(Verification $verification)
    {
        // Khalti requires amount in paisa. Rs 200 = 20000 paisa
        $amount = 20000;
        
        $response = Http::withHeaders([
            'Authorization' => 'Key ' . env('KHALTI_SECRET_KEY', 'live_secret_key_68798bf2bc424263a2afb6e6fb1c448a'),
            'Content-Type' => 'application/json'
        ])->post('https://a.khalti.com/api/v2/epayment/initiate/', [
            'return_url' => route('khalti.callback'),
            'website_url' => config('app.url'),
            'amount' => $amount,
            // Prefixing with VT (Verification Ticket)
            'purchase_order_id' => 'VT-' . $verification->id,
            'purchase_order_name' => "Blue Tick Verification: " . $verification->user->name,
            'customer_info' => [
                'name' => $verification->user->name,
                'email' => $verification->user->email,
                'phone' => '9800000001', // Example generic format
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $verification->update([
                'khalti_pidx' => $data['pidx']
            ]);
            return redirect($data['payment_url']);
        }

        return redirect()->route('profile.settings')->with('error', 'Error initiating Khalti payment. Please try again later.');
    }

    /**
     * Handle the Khalti Payment Return (Callback)
     */
    public function callback(Request $request)
    {
        $pidx = $request->query('pidx');
        $status = $request->query('status'); // Completed, Pending, User canceled

        if ($status !== 'Completed') {
            return redirect()->route('profile.settings')->with('error', 'Khalti Payment was canceled or failed.');
        }

        // Verify the transaction strictly with Khalti Lookup API
        $response = Http::withHeaders([
            'Authorization' => 'Key ' . env('KHALTI_SECRET_KEY', 'live_secret_key_68798bf2bc424263a2afb6e6fb1c448a'),
            'Content-Type' => 'application/json'
        ])->post('https://a.khalti.com/api/v2/epayment/lookup/', [
            'pidx' => $pidx
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['status'] === 'Completed') {
                $verification = Verification::where('khalti_pidx', $pidx)->firstOrFail();
                $verification->update([
                    'payment_status' => 'paid',
                ]);
                
                return redirect()->route('khalti.receipt', $verification)
                    ->with('status', 'Payment successful! Your verification application is now securely submitted for admin review.');
            }
        }

        return redirect()->route('profile.settings')->with('error', 'Payment verification failed. If money was deducted, please contact support.');
    }

    /**
     * Show the Payment Receipt
     */
    public function receipt(Verification $verification)
    {
        // Only allow viewing if payment is paid
        if ($verification->payment_status !== 'paid') {
            return redirect()->route('profile.settings')->with('error', 'No receipt available for unpaid applications.');
        }

        return view('verifications.receipt', compact('verification'));
    }

    /**
     * Download the Payment Receipt as PDF
     */
    public function downloadReceipt(Verification $verification)
    {
        if ($verification->payment_status !== 'paid') {
            abort(403, 'Unauthorized. Receipt not available for unpaid applications.');
        }

        // Generate PDF from view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('verifications.receipt-pdf', compact('verification'));
        
        // Define clean filename
        $filename = 'Receipt_FashionConnect_VT' . $verification->id . '.pdf';

        return $pdf->download($filename);
    }
}

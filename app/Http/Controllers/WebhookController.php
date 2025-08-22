<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    //
    public function verifyWebhook(Request $request)
    {
        $verify_token = 'test_token';
        
        // Facebook verification (GET)
        $hub_mode = $request->query('hub_mode');
        $hub_challenge = $request->query('hub_challenge');
        $hub_verify_token = $request->query('hub_verify_token');

        if ($hub_mode === 'subscribe' && $hub_verify_token === $verify_token) {
            \Log::info('Facebook webhook verification succeeded');
            return response($hub_challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        return response()->json(['status' => 'Webhook verified successfully']);
    }

    public function handleWebhook(Request $request)
    {
       
        $input = $request->json()->all();
        \Log::info('Facebook POST payload: ' . json_encode($input));
        
        return response()->json(['status' => 'Webhook handled successfully']);
    }
}

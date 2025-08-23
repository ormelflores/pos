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
        // Get raw POST body
        $payload = $request->getContent();
        \Log::info('Facebook request body: ' . $payload);

        // Optional: validate X-Hub-Signature-256
        $signature = $request->header('X-Hub-Signature-256');

        $app_secret = "9717bb8af8a79155f363aa40b3d8d21f"; // set your App Secret in .env

        if (!$signature || !hash_equals(
            hash_hmac('sha256', $payload, $app_secret),
            str_replace('sha256=', '', $signature)
        )) {
            \Log::warning('Warning - request header X-Hub-Signature-256 not present or invalid');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        \Log::info('Request header X-Hub-Signature-256 validated');

        // Process Facebook updates here
        // Example: store updates in a log or database
        // received_updates.unshift equivalent: prepend to array or store in DB
        // For demo, just log
        \Log::info('Received Facebook updates: ' . $payload);

        // Respond 200 OK
        return response()->json(['status' => 'OK'], 200);
    }
}

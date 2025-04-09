<?php

namespace App\Http\Controllers;

use App\Models\AiResponse;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AiResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $message = $request->message .', give me output in very short under 20 words';
        $aiKey  = env('DEEP_SEEK_AI_KEY');
        $client = new Client();
        $response = $client->post('https://openrouter.ai/api/v1/chat/completions', [
            'headers' =>  [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '. $aiKey,
            ],
            'json' => [
                'model' => 'deepseek/deepseek-r1:free',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $message,
                    ]
                ]
            ]
        ]);
        $body = $response->getBody();
        $data = json_decode($body,true);
        $result = $data['choices'][0]['message']['content'];
        if(isset($result) && !empty($result) && $result != null && $result != ''){
            return response()->json([
                'message' =>  $result ,
            ]);
        }else{
            return response()->json([
                'message' => 'No expected result' ,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AiResponse $aiResponse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AiResponse $aiResponse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AiResponse $aiResponse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AiResponse $aiResponse)
    {
        //
    }
}

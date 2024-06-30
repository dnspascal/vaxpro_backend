<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class SmsService
{
    protected $apiKey;
    protected $secretKey;

    public function __construct($apiKey = '7d4051b698413530', $secretKey = 'MmNmOGQ1MzY5MzNkMmFmNDY4ODlhMmI5YTVlNDgyYzU3MzRjYzI4YjZiMTNmOTk5N2EwZWU1NDkwYjA4ZjM1Mg==')
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function sendSms($postData)
    {
        $postData = [
            'source_addr' => 'VaxPro',
            'encoding' => 0,
            'schedule_time' => '',
            'message' => $postData["message"],
            'recipients' => [
                [
                    'recipient_id' => '1', 
                    'dest_addr' => $postData['recipient']
                ]
            ]
        ];

        $url = 'https://apisms.beem.africa/v1/send';

        $client = new Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode("$this->apiKey:$this->secretKey"),
                    'Content-Type' => 'application/json',
                ],
                'json' => $postData,
                'verify' => false // This disables SSL verification
            ]);

            $responseBody = $response->getBody()->getContents();
            return response()->json([
                'success' => true,
                'response' => json_decode($responseBody)
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $responseBody = $response->getBody()->getContents();
                return response()->json([
                    'success' => false,
                    'response' => json_decode($responseBody)
                ], $response->getStatusCode());
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function send__multiple_recipient()
    {

        // $receipients = [];

        // for ($i = 0; $i < count($recipient_array); $i++)
        //     array_push($receipients, array('recipient_id' => $i, 'dest_addr' => $recipient_array[$i]));



        $postData = array(
            'source_addr' => 'INFO',
            'encoding' => 0,
            'schedule_time' => '',
            'message' => "HELLO WORLD",
            'recipients' => [array('recipient_id' => '1', 'dest_addr' => '255745884099'), array('recipient_id' => '2', 'dest_addr' => '255658004980')]
        );

        $ch = curl_init('https://apisms.beem.africa/v1/send');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("$this->apiKey:$this->secretKey"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response_data = [];
        // Send  request
        $response = curl_exec($ch);

        $response_array = json_decode($response);

        if ($response_array->code == 100) {
            //it is successful
            $response_data = ["status" => "1", "message" => $response_array->message, "request_id" => $response_array->request_id];
        } else
            $response_data = ["status" => "0", "message" => "Not successful", "request_id" => "0"];



        return $response_data;
    }


    public function sms_oasis($postData)
    {


        $client = new Client();
        $options = [
            'multipart' => [
                [
                    'name' => 'to',
                    'contents' => $postData['recipient']
                ],
                [
                    'name' => 'message',
                    'contents' => $postData['message']
                ]
            ]
        ];
        $request = new Request('POST', "https://api.oasistech.co.tz/v3/sms/send");
        $res = $client->sendAsync($request, $options)->wait();
       
        return response()->json($res->getBody(), 200);
    }
}

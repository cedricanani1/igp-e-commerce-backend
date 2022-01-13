<?php

namespace App\Services;

use Illuminate\Http\Request;

class UserService
{
    public static function get($token) {
        if(!$token) {
            return (object) [
                'success' => false,
                'error' => 'Missing token.'
            ];
        }
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', 'http://192.168.1.3:8004/api/auth/user', [
                'headers' => [
                    'Authorization' => $token,
                    'Accept' => 'application/json',
                ]
            ]);
            $user = json_decode($response->getBody()->getContents())->user;
            $data = (object) [
                'success' => true,
                'user' => $user
            ];
            return $data;

        } catch (ClientException $e) {
            return (object) ['success' => false, 'error' => $e];
        }
    }
}

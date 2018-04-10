<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class ApiController extends BaseController
{
    protected $apiUrl;
    function __construct()
    {
        $this->apiUrl = env('MOCK_API_URL');
    }

    public function getAll()
    {
        try {
            $return = $this->getAllInfo();

        } catch (\Exception $exception) {
            return response()->json($exception, 500);
        }

        $responseData = [
            'totalResults' => count($return),
            'response' => $return
        ];

        return response()->json($responseData, 200);
    }

    /**
     * @return array
     */
    private function getAllInfo()
    {
        $client = new Client();

        try {
            $res = $client->request('GET', $this->apiUrl . 'getinfo', []);
        } catch (BadResponseException $e) {

            $arr = [
                'success' => false,
                'message' => 'Something went wrong',
                'details' => $e->getMessage()
            ];

            return response()->json($arr, 400);
        }

        $body = json_decode($res->getBody()->getContents());
        return $body;

    }
}

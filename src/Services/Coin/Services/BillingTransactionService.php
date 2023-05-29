<?php

namespace Usoft\Ufit\Services\Coin\BillingTransaction\Services;

use App\Models\BillingTransaction;
use Carbon\Carbon;
use Usoft\Ufit\Services\Curl\Services\CurlService;

class BillingTransactionService
{
    private BillingTransaction $model = BillingTransaction::class;

    public function getTotal($data)
    {
        $from_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $to_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        $params = [
            'page' => (int)1,
            'limit' => (int)10,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ];
        $params = array_merge($params, $data);
        if (array_key_exists('merchant_id', $params)) {
            $params['merchant_id'] = (int)$params['merchant_id'];
        }
        if (array_key_exists('user_id', $params)) {
            $params['user_id'] = (int)$params['user_id'];
        }
        try {
            $curlService = (new CurlService())->setHeader(['Accept: */*', "Content-Type: application/json"]);
            $response = $curlService
                ->setParams($params)
                ->post($curlService->getUrl('filter'))
                ->getResponse();
        } catch (\Throwable $th) {
            throw $th;
        }
        $filtered_response = array_intersect_key($response, array_flip([
            'total_items', 'total_amount', 'debit_total', 'credit_total'
        ]));
        return $filtered_response;
    }

    public function getClientTransactions($data)
    {
        $params = [];
        $params = array_merge($params, $data);
        if (array_key_exists('page', $params)) {
            $params['page'] = (int)$params['page'];
        }
        if (array_key_exists('limit', $params)) {
            $params['limit'] = (int)$params['limit'];
        }
        if (array_key_exists('merchant_id', $params)) {
            $params['merchant_id'] = (int)$params['merchant_id'];
        }
        if (array_key_exists('user_id', $params)) {
            $params['user_id'] = (int)$params['user_id'];
        }

        if (array_key_exists('debit', $params)) {
            $params['debit'] = (bool)$params['debit'];
        }

        try {
            $curlService = (new CurlService())->setHeader(['Accept: */*', "Content-Type: application/json"]);
            $response = $curlService
                ->setParams($params)
                ->post($curlService->getUrl('filter'))
                ->getResponse();
        } catch (\Throwable $th) {
            throw $th;
        }
        return $response;
    }



    public function getDashboardTransactions($data)
    {
        $from_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $to_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        $params = [
            'from_date' => $from_date,
            'to_date' => $to_date,
        ];
        $params = array_merge($params, $data);
        if (array_key_exists('page', $params)) {
            $params['page'] = (int)$params['page'];
        }
        if (array_key_exists('limit', $params)) {
            $params['limit'] = (int)$params['limit'];
        }
        if (array_key_exists('merchant_id', $params)) {
            $params['merchant_id'] = (int)$params['merchant_id'];
        }
        if (array_key_exists('user_id', $params)) {
            $params['user_id'] = (int)$params['user_id'];
        }
        if (array_key_exists('type', $data)) {
            $params['type'] = (string)$data['type'];
        }
        if (array_key_exists('debit', $params)) {
            $params['debit'] = ($params['debit']) ? true : false;
        }
        try {
            $curlService = (new CurlService())->setHeader(['Accept: */*', "Content-Type: application/json"]);
            $response = $curlService
                ->setParams($params)
                ->post($curlService->getUrl('filter'))
                ->getResponse();
        } catch (\Throwable $th) {
            throw $th;
        }
        return $response;
    }
}

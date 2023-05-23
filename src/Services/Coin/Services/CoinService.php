<?php

namespace Usoft\Coin\Curl\Services;

use Illuminate\Support\Facades\Log;
use Usoft\Coin\Coin\BillingTransaction\Services\BillingTransactionService;
use Usoft\Coin\Coin\Exceptions\CoinException;
use Usoft\Ufit\Abstracts\Service;


class CoinService extends Service
{

    private CurlService $curlService;
    private array $data;

    const TYPE_ORDER = 'orders';
    const TYPE_PURCHASE = 'products_users';

    public static function getCoinTypes()
    {
        return [
            self::TYPE_ORDER,
            self::TYPE_PURCHASE
        ];
    }
    /**
     * Class constructor.
     */
    public function __construct($data = [])
    {
        $this->curlService = new CurlService();
        $this->data = $data;
    }

    public function create($data = [])
    {
        $curlService = (new CurlService())->setHeader(['Accept: */*', "Content-Type: application/json"]);
        $data['transaction_id']=null;
        try {
            $params = [
                'merchant_id' => (int)$data['merchant_id'],
                'user_id' => (int)$data['user_id'],
                'debit' => ($data['debit'])?true:false, //bu rasxod
                'status' => (int)$data['status'], //tolov otdi
                'amount' => (int)$data['amount'], //amount to minus
                'type' => $data['type'],
                'data' => [
                    'relation_id' => $data['relation_id'],
                    'relation_type' => $data['relation_type'],
                    'type' => $data['data_type'],
                ],
            ];
            $data['transaction_id'] = $curlService
                ->setParams($params)
                ->post($curlService->getUrl('billing'))
                ->getResponse('id');
        } catch (\Throwable $th) {
            if ($data['transaction_id']) {
                try {
                    $params = [
                        'merchant_id' => (int)$data['merchant_id'],
                        'user_id' => (int)$data['user_id'],
                        'debit' => ($data['debit'])?false:true, //bu rasxod
                        'status' => (int)$data['status'], //tolov otdi
                        'amount' => - (int)$data['amount'], //amount to minus
                        'type' => $data['type'],
                        'data' => [
                            'relation_id' => $data['relation_id'],
                            'relation_type' => $data['relation_type'],
                            'type' => $data['data_type'],
                        ],
                    ];
                    $data['transaction_id'] = $curlService
                        ->setParams($params)
                        ->post($curlService->getUrl('billing'))
                        ->getResponse('id');
                } catch (\Throwable $th) {
                }
            }
            throw $th;
        }
    }


    public function afterCreate(){
        $data=$this->data;
        (new BillingTransactionService)->create([
            'user_id' => $data['user_id'],
            'merchant_id' => $data['user_id'],
            'transaction_id' => $data['user_id'],
            'relation_type' => $data['user_id'],
            'relation_id' => $data['user_id'],
            'amount' => $data['user_id'],
        ]);
    }

    public function makePayment()
    {
        $order = $this->get();
        $amount = $order->grand_total;
        $type = Monitoring::TYPE_SHOP;
        $data_type = Monitoring::DATA_TYPE_ORDER;
        $curlService = (new CurlService())->setHeader(['Accept: */*', "Content-Type: application/json"]);
        $params = [
            'merchant_id' => (int)$order->merchant_id,
            'user_id' => (int)$order->user_id,
            'debit' => false, //bu rasxod
            'status' => 1, //tolov otdi
            'amount' => -$amount, //amount to minus
            'type' => $type,
            'data' => [
                'relation_id' => $order->id,
                'relation_type' => 'orders',
                'type' => $data_type,
            ],
        ];
        $transaction_id = null;
        try {
            $transaction_id = $curlService
                ->setParams($params)
                ->post($curlService->getUrl('billing'))
                ->getResponse('id');
            $this->update([
                'payment_status' => Order::PAYMENT_STATUS_PAID
            ]);
        } catch (\Throwable $th) {
            if ($transaction_id) {
                try {
                    $params = [
                        'merchant_id' => (int)$order->merchant_id,
                        'user_id' => (int)$order->user_id,
                        'debit' => true, //bu rasxod
                        'status' => 1, //tolov otdi
                        'amount' => $amount, //amount to minus
                        'type' => $type,
                        'data' => [
                            'relation_id' => $order->id,
                            'relation_type' => 'orders',
                            'type' => $data_type,
                        ],
                    ];
                    $transaction_id = $curlService
                        ->setParams($params)
                        ->post($curlService->getUrl('billing'))
                        ->getResponse('id');
                } catch (\Throwable $th) {
                }
            }
            throw $th;
        }
        (new BillingTransactionService)->create([
            'user_id' => $order->user_id,
            'merchant_id' => $order->merchant_id,
            'transaction_id' => $transaction_id,
            'relation_type' => 'orders',
            'relation_id' => $order->id,
            'amount' => $amount,
        ]);
        return $this;
    }
}

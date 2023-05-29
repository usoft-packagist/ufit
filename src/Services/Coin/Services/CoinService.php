<?php

namespace Usoft\Coin\Curl\Services;

use Usoft\Coin\Coin\BillingTransaction\Services\BillingTransactionService;
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
}

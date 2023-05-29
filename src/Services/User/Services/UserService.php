<?php

namespace Usoft\Ufit\Services\User\Services;

use Usoft\Models\User;
use Usoft\Ufit\Services\Curl\Services\CurlService;
use Usoft\Ufit\Abstracts\Service;

class UserService extends Service
{
    protected $model = User::class;
    protected $balance = 0;

    public function getUserId()
    {
        return $this->model->user_id;
    }
    private function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    public function getBalance()
    {
        $params = [
            'merchant_id' => (int)$this->model->merchant_id,
            'user_id' => (int)$this->model->user_id
        ];
        $curlService = (new CurlService())->setHeader(['Accept: */*', "Content-Type: application/json"]);
        $balance = $curlService
            ->setParams($params)
            ->post($curlService->getUrl('balance'))
            ->getResponse('balance');
        if (env('APP_DEBUG', false) == true) {
            $balance = 154329;
        }
        $this->setBalance($balance);
        return $this->balance;
    }
}

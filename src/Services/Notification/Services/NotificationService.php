<?php

namespace Usoft\Ufit\Services\Notification\Services;

use Illuminate\Support\Facades\Log;
use Usoft\Ufit\Services\Curl\Services\CurlService;
use Usoft\Ufit\Services\Notification\Exceptions\NotificationException;
use Usoft\Ufit\Services\Notification\Jobs\StoreNotificationJob;
use Usoft\Ufit\Abstracts\Service;

class NotificationService  extends Service
{
    protected CurlService $curlService;
    protected array $data;

    const TYPE_ORDER = 'orders';
    const TYPE_PURCHASE = 'products_users';

    public static function getNotificationTypes()
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
        if (isset($data) && count($data) > 0) {
            $this->data = $data;
        }
        try {
            $this->curlService
                ->setHeader(['Accept: */*', "Content-Type: application/json"])
                ->setParams($this->data)
                ->post($this->curlService->getUrl('notification'))
                ->getResponse();
        } catch (\Exception $e) {
            Log::error('NOTIFICATION ERROR: ' . $e->getMessage() . ' with data:' . implode(',', $this->data) . ' full stack trace:' . $e->getTraceAsString());
            throw new NotificationException($e->getMessage(), 400);
        }
        return true;
    }

    public function sendNotificationJob()
    {
        $this->createJob($this->data);
        return $this;
    }
}

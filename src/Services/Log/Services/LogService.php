<?php

namespace Usoft\Ufit\Services\Log\Services;

use Usoft\Models\User;
use Carbon\Carbon;
use Usoft\RabbitMq\Services\SendService;
use Illuminate\Support\Facades\Log;

class LogService
{
    private User $user;
    public function setUser($user)
    {
        if ($user) {
            $this->user = $user;
        } else {
            Log::error("User not set! On setting user");
        }
        return $this;
    }

    public function getUser()
    {
        if (!isset($this->user)) {
            Log::error("User not set! On getting user");
        }
        return $this->user;
    }

    public function setUserById($user_id = null, $merchant_id = null)
    {
        if (empty($user_id)) {
            $user_id = request()->user_id ?? null;
        }
        if (empty($merchant_id)) {
            $merchant_id = request()->merchant_id ?? null;
        }
        if ($user = User::where('user_id', $user_id)->where('merchant_id', $merchant_id)->first()) {
            $this->setUser($user);
        } else {
            Log::error("User not found! ID: {$user_id} & MERCHANT_ID: {$merchant_id}");
        }
        return $this;
    }

    public function create($data = [], $type = 'step')
    {
        if ($user = $this->getUser()) {
            $attributes = [
                'user_id' => $user->user_id,
                'merchant_id' => $user->merchant_id,
                'action' => $type,
                'phone' => $user->phone,
                'data' => $data,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            try {
                (new SendService())->queue_declare('logger', $attributes);
            } catch (\Exception $exception) {
                Log::error("Rabbit Send Logger {$type} {$exception->getMessage()}");
            }
        }
        return $this;
    }
}

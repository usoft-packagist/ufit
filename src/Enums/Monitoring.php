<?php

namespace Usoft\Ufit\Enums;

class Monitoring
{
    const TYPE_LEVEL = 'level';
    const TYPE_ADS = 'ads';
    const TYPE_REFERRAL = 'referral';
    const TYPE_PREMIUM = 'premium';
    const TYPE_SHOP = 'shop';
    const TYPE_VOTE = 'vote';

    public static function getTypes()
    {
        return [
            self::TYPE_LEVEL,
            self::TYPE_ADS,
            self::TYPE_REFERRAL,
            self::TYPE_PREMIUM,
            self::TYPE_SHOP,
            self::TYPE_VOTE,
        ];
    }

    public static function getTypeTranslations()
    {
        return [
            self::TYPE_LEVEL => [
                'en' => 'Level',
                'uz' => 'Daraja',
                'ru' => 'Уровень'
            ],
            self::TYPE_ADS => [
                'en' => 'ADS',
                'uz' => 'Reklama',
                'ru' => 'Реклама'
            ],
            self::TYPE_REFERRAL => [
                'en' => 'Referral',
                'uz' => 'Yo\'naltiruvchi dastur',
                'ru' => 'Реферальная программа'
            ],
            self::TYPE_PREMIUM => [
                'en' => 'Premium',
                'uz' => 'Premium',
                'ru' => 'Премиум'
            ],
            self::TYPE_SHOP => [
                'en' => 'Shop',
                'uz' => 'Do\'kon',
                'ru' => 'Магазин'
            ],
            self::TYPE_VOTE => [
                'en' => 'Vote',
                'uz' => 'Ovoz berish',
                'ru' => 'Голосовать'
            ],
        ];
    }

    const DATA_TYPE_LEVEL_1 = 'level_1';
    const DATA_TYPE_LEVEL_2 = 'level_2';
    const DATA_TYPE_LEVEL_3 = 'level_3';
    const DATA_TYPE_ADS = 'ads';
    const DATA_TYPE_REGISTRATION = 'registration';
    const DATA_TYPE_TRIPLE = 'triple';
    const DATA_TYPE_ORDER = 'order';
    const DATA_TYPE_VOICE_PACKAGE = 'voice_package';
    const DATA_TYPE_LEVEL = 'level';
    const DATA_TYPE_INTERNET = 'internet';
    const DATA_TYPE_PAYNET = 'paynet';
    const DATA_TYPE_SMS = 'sms';
    const DATA_TYPE_QUESTION = 'question';

    public static function getDataTypes()
    {
        return [
            self::DATA_TYPE_LEVEL_1,
            self::DATA_TYPE_LEVEL_2,
            self::DATA_TYPE_LEVEL_3,
            self::DATA_TYPE_ADS,
            self::DATA_TYPE_REGISTRATION,
            self::DATA_TYPE_TRIPLE,
            self::DATA_TYPE_ORDER,
            self::DATA_TYPE_VOICE_PACKAGE,
            self::DATA_TYPE_LEVEL,
            self::DATA_TYPE_INTERNET,
            self::DATA_TYPE_PAYNET,
            self::DATA_TYPE_SMS,
            self::DATA_TYPE_QUESTION,
        ];
    }

    public static function getDataTypeTranslations()
    {
        return [
            self::DATA_TYPE_LEVEL_1 => [
                'en' => 'level - 1',
                'uz' => 'daraja - 1',
                'ru' => 'уровень - 1'
            ],
            self::DATA_TYPE_LEVEL_2 => [
                'en' => 'level - 2',
                'uz' => 'daraja - 2',
                'ru' => 'уровень - 2'
            ],
            self::DATA_TYPE_LEVEL_3 => [
                'en' => 'level - 3',
                'uz' => 'daraja - 3',
                'ru' => 'уровень - 3'
            ],
            self::DATA_TYPE_ADS => [
                'en' => 'ADS',
                'uz' => 'Reklama',
                'ru' => 'Реклама'
            ],
            self::DATA_TYPE_REGISTRATION => [
                'en' => 'Registration',
                'uz' => 'Ro\'yxatdan o\'tish',
                'ru' => 'Регистрация'
            ],
            self::DATA_TYPE_TRIPLE => [
                'en' => 'Triple',
                'uz' => 'Uch baravar',
                'ru' => 'Тройное'
            ],
            self::DATA_TYPE_ORDER => [
                'en' => 'Order',
                'uz' => 'Buyurtma',
                'ru' => 'Заказ'
            ],
            self::DATA_TYPE_VOICE_PACKAGE => [
                'en' => 'Voice package',
                'uz' => 'Ovozli to\'plam',
                'ru' => 'Голосовой пакет'
            ],
            self::DATA_TYPE_LEVEL => [
                'en' => 'Level',
                'uz' => 'Daraja',
                'ru' => 'Уровень'
            ],
            self::DATA_TYPE_INTERNET => [
                'en' => 'Internet',
                'uz' => 'Internet',
                'ru' => 'Интернет'
            ],
            self::DATA_TYPE_PAYNET => [
                'en' => 'Paynet',
                'uz' => 'Hisobni to\'ldirish',
                'ru' => 'Баланс пополнение'
            ],
            self::DATA_TYPE_SMS => [
                'en' => 'SMS',
                'uz' => 'SMS',
                'ru' => 'СМС'
            ],
            self::DATA_TYPE_QUESTION => [
                'en' => 'Question',
                'uz' => 'Savol',
                'ru' => 'Вопросы'
            ],
        ];
    }
}

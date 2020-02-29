<?php namespace app\lib\services;

use yii\db\Query;

class CacheService
{
    const KEY_PREFIX_NEWS = 'news_';

    public static function getOrSetNews($idUser, callable $function)
    {
        return \Yii::$app->getCache()->getOrSet(static::KEY_PREFIX_NEWS . $idUser, $function);
    }

    public static function prependNewsToCachesSubscribers($idUser, $message)
    {
        $cache = \Yii::$app->getCache();
        $value = $cache->get(static::KEY_PREFIX_NEWS . $idUser) ?: '';
        $value = $message . $value;
        $cache->set(static::KEY_PREFIX_NEWS . $idUser, $value);
    }
}

<?php

namespace app\lib\services;

use yii\base\BaseObject;
use yii\db\Query;

class PagesUserService extends BaseObject
{
    public function findListPages(int $length): array
    {
        return (new Query())->select(['id', 'first_name', 'surname', 'city', 'auth_key'])
            ->from('user')
            ->limit($length)
            ->all();
    }
}

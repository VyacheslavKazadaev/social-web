<?php
namespace app\lib\helpers;

class DateHelper extends \DateTime
{
    const FORMAT_DB = 'Y-m-d H:i:s';

    public function formatDB()
    {
        return $this->format(static::FORMAT_DB);
    }
}

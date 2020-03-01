<?php
return [
    'class' => \yii\queue\amqp_interop\Queue::class,
//    'host' => 'localhost',
//    'port' => 5672,
//    'user' => 'guest',
//    'password' => 'guest',
    'queueName' => 'queue',
    'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
    'as log' => \yii\queue\LogBehavior::class,
    'ttr' => 5 * 60, // Max time for job execution
    'attempts' => 3, // Max number of attempts
    // или
//    'dsn' => 'amqp://guest:guest@localhost:5672/%2F',
    // или
//    'dsn' => 'amqp:',
];

<?php
return [
    'class' => \mikemadisonweb\rabbitmq\Configuration::class,
    'connections' => [
        [
            // You can pass these parameters as a single `url` option: https://www.rabbitmq.com/uri-spec.html
            'host' => 'localhost',
            'port' => '5672',
            'user' => 'guest',
            'password' => 'guest',
            'vhost' => '/',
        ]
        // When multiple connections is used you need to specify a `name` option for each one and define them in producer and consumer configuration blocks
    ],
    'exchanges' => [
        [
            'name' => 'exch_posts',
            'type' => 'direct'
            // Refer to Defaults section for all possible options
        ],
    ],
    'queues' => [
        [
            'name' => 'posts',
            // Queue can be configured here the way you want it:
            //'durable' => true,
            //'auto_delete' => false,
        ],
    ],
    'bindings' => [
        [
            'queue' => 'posts',
            'exchange' => 'exch_posts',
            'routing_keys' => ['post_key'],
        ],
    ],
    'producers' => [
        [
            'name' => 'posts',
        ],
    ],
    'consumers' => [
        [
            'name' => 'posts',
            // Every consumer should define one or more callbacks for corresponding queues
            'callbacks' => [
                // queue name => callback class name
                'posts' => \app\queue\jobs\AddPostJob::class,
            ],
        ],
    ],
];

<?php

namespace NBLabs;

use Kirby\Cms\App as Kirby;
require_once __DIR__ . '/class.php';

Kirby::plugin('nblabs/dragndrop', [
    // plugin magic happens here
   
    'routes' => function ($kirby) {
        return [
          [
            'pattern' => "dragndrop",
            'action' => function () use ($kirby) {
             $jsonGenerator = new JsonGenerator($kirby);
             return $jsonGenerator->createJSON();
            },
            'method' => 'POST|GET'
          ]
        ];
      }
   ,
    'fields' => [
        'dragndrop' => [
        ]
      ]
]);

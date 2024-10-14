<?php

namespace NBLabs;

use Kirby\Cms\App as Kirby;
require_once __DIR__ . '/class.php';
require_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('nblabs/scorm-generator', [
    // plugin magic happens here
   
    'routes' => function ($kirby) {
        return [
          [
            'pattern' => 'generate-scorm',
            'action' => function () use ($kirby) {
                $scormGenerator = new ScormGenerator($kirby);
                $s = $scormGenerator->generateScormZip();
                return ($s); 
            },
            'method' => 'POST|GET'
          ]
        ];
      }
   ,
    'fields' => [
        'scorm' => [
        ]
      ]
]);

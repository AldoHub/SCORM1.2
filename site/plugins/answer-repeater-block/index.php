<?php

Kirby::plugin('cookbook/repeater-block', [
  'blueprints' => [
    'blocks/repeater' => __DIR__ . '/blueprints/blocks/repeater.yml',
   
  ],
  'snippets' => [
    'blocks/repeater' => __DIR__ . '/snippets/blocks/repeater.php',
  ],
]);
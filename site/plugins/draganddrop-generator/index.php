<?php

Kirby::plugin('cookbook/draganddrop-generator', [
  'blueprints' => [
    'blocks/draganddrop' => __DIR__ . '/blueprints/blocks/draganddrop.yml',
   
  ],
  'snippets' => [
    'blocks/draganddrop' => __DIR__ . '/snippets/blocks/draganddrop.php',
  ],
]);
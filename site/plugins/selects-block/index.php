<?php

Kirby::plugin('cookbook/selects-block', [
  'blueprints' => [
    'blocks/selects' => __DIR__ . '/blueprints/blocks/selects.yml',
   
  ],
  'snippets' => [
    'blocks/selects' => __DIR__ . '/snippets/blocks/selects.php',
  ],
]);
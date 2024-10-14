<?php

Kirby::plugin('cookbook/cwriter', [
  'blueprints' => [
    'blocks/cwriter' => __DIR__ . '/blueprints/blocks/cwriter.yml',
   
  ],
  'snippets' => [
    'blocks/cwriter' => __DIR__ . '/snippets/blocks/cwriter.php',
  ],
]);
<?php

Kirby::plugin('cookbook/embed-generator', [
  'blueprints' => [
    'blocks/embed' => __DIR__ . '/blueprints/blocks/embed.yml',
   
  ],
  'snippets' => [
    'blocks/embed' => __DIR__ . '/snippets/blocks/embed.php',
  ],
]);
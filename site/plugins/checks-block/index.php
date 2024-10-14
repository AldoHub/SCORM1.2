<?php

Kirby::plugin('cookbook/checks-block', [
  'blueprints' => [
    'blocks/checks' => __DIR__ . '/blueprints/blocks/checks.yml',
   
  ],
  'snippets' => [
    'blocks/checks' => __DIR__ . '/snippets/blocks/checksphp',
  ],
]);
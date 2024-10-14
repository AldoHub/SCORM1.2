<?php

Kirby::plugin('cookbook/radios-block', [
  'blueprints' => [
    'blocks/radios' => __DIR__ . '/blueprints/blocks/radios.yml',
   
  ],
  'snippets' => [
    'blocks/radios' => __DIR__ . '/snippets/blocks/radios.php',
  ],
]);
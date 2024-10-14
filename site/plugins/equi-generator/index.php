<?php

Kirby::plugin('cookbook/equi-generator', [
  'blueprints' => [
    'blocks/equi' => __DIR__ . '/blueprints/blocks/equi.yml',
   
  ],
  'snippets' => [
    'blocks/equi' => __DIR__ . '/snippets/blocks/equi.php',
  ],
]);
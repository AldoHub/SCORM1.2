<?php

Kirby::plugin('cookbook/texts-block', [
  'blueprints' => [
    'blocks/texts' => __DIR__ . '/blueprints/blocks/texts.yml',
   
  ],
  'snippets' => [
    'blocks/texts' => __DIR__ . '/snippets/blocks/texts.php',
  ],
]);
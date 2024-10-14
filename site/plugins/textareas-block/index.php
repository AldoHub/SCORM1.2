<?php

Kirby::plugin('cookbook/textareas-block', [
  'blueprints' => [
    'blocks/textareas' => __DIR__ . '/blueprints/blocks/textareas.yml',
   
  ],
  'snippets' => [
    'blocks/textareas' => __DIR__ . '/snippets/blocks/textareas.php',
  ],
]);
<?php

Kirby::plugin('cookbook/carousel-generator', [
  'blueprints' => [
    'blocks/carousel' => __DIR__ . '/blueprints/blocks/carousel.yml',
   
  ],
  'snippets' => [
    'blocks/carousel' => __DIR__ . '/snippets/blocks/carousel.php',
  ],
]);
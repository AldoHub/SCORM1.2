<?php

Kirby::plugin('cookbook/sections-block', [
  'blueprints' => [
    'blocks/sections' => __DIR__ . '/blueprints/blocks/sections.yml'
  ],
  'snippets' => [
    'blocks/sections' => __DIR__ . '/snippets/blocks/sections.php',
  ],
]);
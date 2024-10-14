<?php

namespace NBLabs;

use Kirby\Cms\App as Kirby;

require_once __DIR__ . '/class.php';
#add the static-file-generator and change the namespaces to be found
require_once($_SERVER['DOCUMENT_ROOT'] . "/site/plugins/static-site-generator/class.php" );
require_once($_SERVER['DOCUMENT_ROOT'] . "/site/plugins/static-site-generator/media.class.php" );

Kirby::plugin('nblabs/zip-generator', [
    // plugin magic happens here
   
    'routes' => function ($kirby) {
        return [
          [
            'pattern' => "generate-zip",
            'action' => function () use ($kirby) {
              
            /*
            $outputFolder ='./static';
            $baseUrl = '/';
            $preserve = [];
            $skipMedia = false;
            $skipTemplates = ['home'];
            */

            $outputFolder = $kirby->option('d4l.static_site_generator.output_folder', './static');
            $baseUrl = $kirby->option('d4l.static_site_generator.base_url', '/');
            $preserve = $kirby->option('d4l.static_site_generator.preserve', []);
            $skipMedia = $kirby->option('d4l.static_site_generator.skip_media', false);
            $skipTemplates = array_diff($kirby->option('d4l.static_site_generator.skip_templates', []), ['home']);
          
            $pages = $kirby->site()->index()->filterBy('intendedTemplate', 'not in', $skipTemplates);
           
            $staticSiteGenerator = new StaticSiteGenerator($kirby, null, $pages);
            $staticSiteGenerator->skipMedia($skipMedia);
            $list = $staticSiteGenerator->generate($outputFolder, $baseUrl, $preserve);
            $zipGenerator = new ZipGenerator($kirby);
            $res = $zipGenerator->createZip();
            
            return $res;
             
            },
            'method' => 'POST|GET'
          ]
        ];
      }
   ,
    'fields' => [
        'test' => [
        ]
      ]
]);

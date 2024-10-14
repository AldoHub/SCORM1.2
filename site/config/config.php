<?php

return [
  'debug' => true,
  'languages' => true,
  'd4l' => [
     'static_site_generator' => [
       'endpoint' => "generate-static-site", # set to a string to use the built-in webhook, e.g. when using the blueprint field
       'output_folder' => './static/', # you can specify an absolute or relative path
       'base_url' => '/', # if the static site is not mounted to the root folder of your domain, change accordingly here
       'preserve' => [],
       'skip_media' => false,
     ]
   ]
];
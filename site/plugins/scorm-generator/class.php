<?php

namespace NBLabs;

use Error;
use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Pages;
use Kirby\Toolkit\Dir;
use Kirby\Toolkit\F;
use Whoops\Exception\ErrorException;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;


class ScormGenerator
{
 
    


  public function __construct(App $kirby)
  {
  
  }

  public function generateScormZip(){
    $config = [
        "title" => "Shell Quiz",
        "identifier" => "shell_quiz",
        "version" => "2004 3rd Edition",
        "source" => "./temp",
        "destination" => "./temp",
        "masteryScore" => 70,
        "startingPage" => "shared/launchpage.html",
        "organization" => "Shell_Organization",
        "metadataDescription" => "shell quiz",
    ]; 
    $packager = new \Romanpravda\Scormpackager\Packager($config);
    $packager->buildPackage();

    return "done";
  }


}

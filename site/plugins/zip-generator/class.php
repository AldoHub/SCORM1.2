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




class ZipGenerator
{
   
  public function __construct(App $kirby)
  {
  
  }

  public function createZip(){
       
        $rootPath = realpath("./static");
        $zip = new ZipArchive;
        $zip->open("./replaced_static.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
    
        foreach ($files as $name => $file){
            #Skip directories (they would be added automatically)
            
            if (!$file->isDir())
            {
                #Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
        
                #Add current file to archive
                $zip->addFile($filePath, $relativePath);

              
            }
        }
        
        #Zip archive will be created only after closing object
        $zip->close();
        //extract the zip into a the temp folder
        $target_file = "./replaced_static.zip";
        if($zip->open($target_file) === TRUE){
          $zip->extractTo("." . DIRECTORY_SEPARATOR ."temp" . DIRECTORY_SEPARATOR);
          $zip->close();
        
        ###---- read the contents of the dirs
        #get the name of the folder
        $path_parts = pathinfo($target_file);
        $filename = $path_parts["filename"];
        $filepath = "." . DIRECTORY_SEPARATOR ."temp";
        
        ###---- delete the files/folders here
        #we know the folder and file we need to remove, lets do it manually for now;
        unlink("./temp/error/index.html");
        rmdir("./temp/error");
        unlink("./temp/index.html");
       
        #scan the dir and its contents and change the media to assets
        ZipGenerator::dirsSearch($filepath);


         #assets zip
         $z0 = realpath(".". DIRECTORY_SEPARATOR . "/temp/assets" );
         $zip->open("assets.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
         
         $files = new RecursiveIteratorIterator(
             new RecursiveDirectoryIterator($z0),
             RecursiveIteratorIterator::LEAVES_ONLY
         );
      
         foreach ($files as $name => $file){
             #Skip directories (they would be added automatically)
             if (!$file->isDir())
             {
                 #Get real and relative path for current file
                 $filePath = $file->getRealPath();
                 $relativePath = substr($filePath, strlen($rootPath) -1);
          
                 #Add current file to archive
                 $zip->addFile($filePath, $relativePath);
                 
                
             }
 
             
         }
         $zip->close();
      
        #media zip
        $z1 = realpath(".". DIRECTORY_SEPARATOR . "/temp/media/pages/" . $_POST["url"] );
        $zip->open("media.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($z1),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
     
        foreach ($files as $name => $file){
            #Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                #Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) -1);
         
                #Add current file to archive
                $zip->addFile($filePath, $relativePath);
                
               
            }

            
        }
        $zip->close();

        #pages
        $z2 = realpath(".". DIRECTORY_SEPARATOR . "/temp/" . $_POST["url"] . "/" );
        $zip->open("quiz.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($z2),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
     
        foreach ($files as $name => $file){
            #Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                #Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) - 1);
         
                #Add current file to archive
                $zip->addFile($filePath, $relativePath);
                
               
            }

            
        }
       
        $zip->close();

        $target_assets = "./assets.zip";
        if($zip->open($target_assets) === TRUE){
          $zip->extractTo("." . DIRECTORY_SEPARATOR ."temp2" . DIRECTORY_SEPARATOR);
          $zip->close();
        }
     

        $target_media = "./media.zip";
        if($zip->open($target_media) === TRUE){
          $zip->extractTo("." . DIRECTORY_SEPARATOR ."temp2" . DIRECTORY_SEPARATOR);
          $zip->close();
        }
     

        $target_page = "./quiz.zip";
        if($zip->open($target_page) === TRUE){
          $zip->extractTo("." . DIRECTORY_SEPARATOR ."temp2" . DIRECTORY_SEPARATOR);
          $zip->close();
        }


        #try to zip the whole folder now
        $rootPath = realpath(".". DIRECTORY_SEPARATOR ."temp2" );
        $zip->open("./replaced_static.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );


        ###----get the scorm contents and process them as needed
        $scormPath = realpath(".". DIRECTORY_SEPARATOR ."scorm");
        $launchPage = realpath(".". DIRECTORY_SEPARATOR ."scorm" . DIRECTORY_SEPARATOR . "launchpage.txt");
        $imsmanifest = realpath(".". DIRECTORY_SEPARATOR ."scorm" . DIRECTORY_SEPARATOR . "imanifest.txt");

        ###----move the shared folder into the temp folder   
        $sharedPath = realpath(".". DIRECTORY_SEPARATOR ."scorm" . DIRECTORY_SEPARATOR . "shared" );

        $shared = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sharedPath));
        $newSharedFolder = "." . DIRECTORY_SEPARATOR . "temp2" . DIRECTORY_SEPARATOR . "shared";

        #make the shared folder inside the temp if doesnt exist
        if(!file_exists($newSharedFolder)){
            mkdir($newSharedFolder, 0777, true);
        }

        #loop through the files and copy them
        foreach($shared as $s){     
            if(is_file($s)){
            copy($s , $newSharedFolder . DIRECTORY_SEPARATOR . basename($s) );         
            }
        }

        #print_r($_POST);   
        ###----get the file contents for launchpage and change the score
        $launchPageContents = file_get_contents($launchPage);
        $launchPageContents = str_replace("#score", $_POST["score"], $launchPageContents);
        $launchPageContents = str_replace("#project", $_POST["url"], $launchPageContents);
        $launchPageNewPath = "." . DIRECTORY_SEPARATOR . "temp2" . DIRECTORY_SEPARATOR . "shared" . DIRECTORY_SEPARATOR . "launchpage.html";
        #make the new html file
        #folder must exist first, or will throw an Exception
        file_put_contents($launchPageNewPath, $launchPageContents);

        ###----get the file contents for imanifest and change the data
        $imsmanifestContents = file_get_contents($imsmanifest);
        $imsmanifestContents = str_replace("#title", $_POST["title"], $imsmanifestContents);
        $imsmanifestContents = str_replace("#courseID", $_POST["id"], $imsmanifestContents);
        $imsmanifestContents = str_replace("#organization", $_POST["organization"], $imsmanifestContents);
        #$imsmanifestContents = str_replace("#organizationID", $_POST["organizationID"], $imsmanifestContents);
        $imsmanifestContents = str_replace("#resourceID", "resource_1", $imsmanifestContents);
        $imsmanifestContents = str_replace("#itemID", "item_1", $imsmanifestContents);
       

        $imsmanifestNewPath = "." . DIRECTORY_SEPARATOR . "temp2" . DIRECTORY_SEPARATOR . "imsmanifest.xml";
        #make the new imsmanifest with the changed data
        file_put_contents($imsmanifestNewPath, $imsmanifestContents);

        #package it and delete the remains


        foreach ($files as $name => $file){
            #Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                #Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
        
                #Add current file to archive
                $zip->addFile($filePath, $relativePath);
                
            
            }

            
        }


        #Zip archive will be created only after closing object
        $zip->close();



        /*

        #try to zip the whole folder now
        $rootPath = realpath(".". DIRECTORY_SEPARATOR ."temp" );
        $zip->open("./replaced_static.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
     
     
        ###----get the scorm contents and process them as needed
        $scormPath = realpath(".". DIRECTORY_SEPARATOR ."scorm");
        $launchPage = realpath(".". DIRECTORY_SEPARATOR ."scorm" . DIRECTORY_SEPARATOR . "launchpage.txt");
        $imsmanifest = realpath(".". DIRECTORY_SEPARATOR ."scorm" . DIRECTORY_SEPARATOR . "imanifest.txt");

        ###----move the shared folder into the temp folder   
        $sharedPath = realpath(".". DIRECTORY_SEPARATOR ."scorm" . DIRECTORY_SEPARATOR . "shared" );
     
        $shared = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sharedPath));
        $newSharedFolder = "." . DIRECTORY_SEPARATOR . "temp" . DIRECTORY_SEPARATOR . "shared";
        
        #make the shared folder inside the temp if doesnt exist
        if(!file_exists($newSharedFolder)){
            mkdir($newSharedFolder, 0777, true);
        }
     
        #loop through the files and copy them
        foreach($shared as $s){     
            if(is_file($s)){
               copy($s , $newSharedFolder . DIRECTORY_SEPARATOR . basename($s) );         
            }
        }

        #print_r($_POST);   
        ###----get the file contents for launchpage and change the score
        $launchPageContents = file_get_contents($launchPage);
        $launchPageContents = str_replace("#score", $_POST["score"], $launchPageContents);
        $launchPageNewPath = "." . DIRECTORY_SEPARATOR . "temp" . DIRECTORY_SEPARATOR . "shared" . DIRECTORY_SEPARATOR . "launchpage.html";
        #make the new html file
        #folder must exist first, or will throw an Exception
        file_put_contents($launchPageNewPath, $launchPageContents);

        ###----get the file contents for imanifest and change the data
        $imsmanifestContents = file_get_contents($imsmanifest);
        $imsmanifestContents = str_replace("#title", $_POST["title"], $imsmanifestContents);
        $imsmanifestContents = str_replace("#courseID", $_POST["id"], $imsmanifestContents);
        $imsmanifestContents = str_replace("#organization", $_POST["organization"], $imsmanifestContents);
        #$imsmanifestContents = str_replace("#organizationID", $_POST["organizationID"], $imsmanifestContents);
        $imsmanifestContents = str_replace("#resourceID", "resource_1", $imsmanifestContents);
        $imsmanifestContents = str_replace("#itemID", "item_1", $imsmanifestContents);
        
        $imsmanifestNewPath = "." . DIRECTORY_SEPARATOR . "temp" . DIRECTORY_SEPARATOR . "imsmanifest.xml";
        #make the new imsmanifest with the changed data
        file_put_contents($imsmanifestNewPath, $imsmanifestContents);

        #package it and delete the remains
       
     
        foreach ($files as $name => $file){
            #Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                #Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
         
                #Add current file to archive
                $zip->addFile($filePath, $relativePath);
                
               
            }

            
        }
   
     
        #Zip archive will be created only after closing object
        $zip->close();
        
        */
        
        $download_zip  = "." . DIRECTORY_SEPARATOR . "replaced_static.zip";

        #is time to download the file
        header('Content-Disposition: attachment; filename="replaced_static.zip"');
        readfile("./replaced_static.zip"); 
        

        ###----remove the zip and temp folder contents;
        #remove the zip
       
        unlink("./replaced_static.zip");
        unlink("./media.zip");
        unlink("./quiz.zip");
        unlink("./assets.zip");
        #do a recursive call to remove the static folder and its contents    
        $static_folder = '.' . DIRECTORY_SEPARATOR . "temp";
        ZipGenerator::deleteFolderContents('./temp');
        ZipGenerator::deleteFolderContents('./static');
        ZipGenerator::deleteFolderContents('./temp2');
       
      return "done";
      }else{

      }






      
  }


  function dirsSearch($filename){
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($filename));
    foreach($rii as $file){
        
        #check if the file has the word "quiz-" on it
        #we dont need the other folders for now
       
        
        if(strpos($file, $filename. DIRECTORY_SEPARATOR . $_POST["url"]) !== false){
            
            #check if there is a file inside called "index.html"
            if(strpos($file, "index.html")){

                #read the file as string, using specialchars
                $content = htmlspecialchars(file_get_contents($file));
                #replace to the correct folder
                $replaced = str_replace("/media", "../media", $content);
                $replaced = str_replace("/assets", "../assets", $replaced);
                #overwrite the file, using the new string
                file_put_contents($file, htmlspecialchars_decode($replaced));
               
            }
          
        }
        
        
    }
}


function deleteFolderContents($dirname){
    if (is_dir($dirname)) {
        $dir = new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS);
        foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST) as $object) {
            if ($object->isFile()) {
                unlink($object);
            } elseif($object->isDir()) {
                rmdir($object);
            } else {
                throw new Exception('Unknown object type: '. $object->getFileName());
            }
        }
        //rmdir($dirname); // Now remove myfolder
    } else {
        throw new Exception('This is not a directory');
    }
}



}

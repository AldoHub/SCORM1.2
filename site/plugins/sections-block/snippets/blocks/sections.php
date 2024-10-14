<?php 

#$questionObj = $block->question()->toArray();
#$decoded = json_decode($questionObj["question"], true);
#$data = $block->data()->toArray();
$objs = $block->toArray();

$_arr=[];

$hide = $block->hide()->html();
/*
foreach($decoded as $val){
    array_push( $_arr , $val );   
    
}
*/



#print_r($objs["content"]);
?>
<!--questions-->
<div class="questions 
<?php echo $block->contentOnly()->html();?>
<?php echo $block->hide()->html(); ?>">
   
<?php

foreach($objs["content"] as $key => $value){
    #print_r($value);
    if($key == "data"){
        
        $_d = json_decode($value, true);
        if(empty($_d)){
           
        }else{
            $d = $_d;
            #print_r($d);
            foreach($_d as $__d){
               
                if($__d["type"] == "embed"){
                    $embed = array_values($__d["content"]);
                        $content = "<div class='data-content'>";
                        $content .= $embed[0];
                        $content .= "</div>";
                        echo $content;                        
                    /*
                    //make a curl
                    if($__d["type"] == "embed"){
                      $embed = array_values($__d["content"]);
                        #print_r($embed[0]);
                    }
                    
                    //check if video has a special keywork (vimeo, youtube, etc)

                    $ch = curl_init();
                    //set the target
                    $url = "https://www.youtube.com/oembed?url=" . $embed[0] . "&format=json";
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                    // parameters
                    $result = curl_exec ($ch);
                    
                    curl_close ($ch);    

                    print_r($result);
                    */   
                }
                if($__d["type"] == "cwriter"){
                   $content = "<div class='data-content'>";
                   $content .= $__d["content"]["writer"];
                   $content .= "</div>";
                   echo $content; 

                }
                if($__d["type"] == "carousel") {
                    $carousel = $__d["content"]["carousel"];
                    $imagesContent = json_decode($carousel, true);
                    $images = $imagesContent[0]["content"]["images"];
                    //$url = $get_image->url();
                    $carousel_output = "<div class='data-content-carousel'>";
                    $carousel_output .= "<div class='carousel'>";
                    //loop through the images and get the files
                    foreach($images as $image){
                        #echo $image;
                        $get_image = $page->image($image); 
                        $url = $get_image->url();
                        #$carousel_output .="<img alt=". $image ." src='". $url ."' />";
                        $carousel_output .= "<a href='".$url."' data-lity>";
                        $carousel_output .="<div class='carousel-item' style='background-image:url(". $url .")'></div>";
                        $carousel_output .= "</a>";
                    }

                    $carousel_output .= "</div>";
                    $carousel_output .= "</div>";
                    echo $carousel_output;
                }
            }
        }
       
        
    }
    if($key == "question"){
       
        $_q = json_decode($value, true);
       
        if(empty($_q)){
            #echo "IS NULL";
        }else{
            $q = $_q[0];
            $type = $q["type"];
            $values = array_values($q["content"]);

            if($type == "texts"){
      
                $txt_output = "<div class='question texts'>";
                $txt_output .= "<div class='question-title'>";
                $txt_output .= "<label>" . $values[0] . "</label>";
                $txt_output .= "<span class='error'></span>";
                $txt_output .= "</div>";
                $txt_output .= "<div>";
                $txt_output .= "<input type='text' data-src-score='". $values[2] ."' data-src-value='" . $values[1] . "'  data-src-score-wrong='".$values[3] . "' />";
                $txt_output .= "</div>";
                $txt_output .= "</div>";
                echo $txt_output;
               
            }
            if($type == "textareas"){
                
                $txt_output = "<div class='question textareas'>";
                $txt_output .= "<div class='question-title'>";
                $txt_output .= "<label>" . $values[0] . "</label>";
                $txt_output .= "<span class='error'></span>";
                $txt_output .= "</div>";
                $txt_output .= "<div>";
                $txt_output .= "<textarea data-src-score='". $values[2] ."' data-src-value='" . $values[1] . "'  data-src-score-wrong='".$values[3] . "'></textarea>";
               
                $txt_output .= "</div>";
                $txt_output .= "</div>";
                echo $txt_output;
               
            }
            if($type == "selects"){
               
                $f = $values[1];
                $ff = json_decode($f, true);
                $select_output ="<div class='question selects'>";
                $select_output .= "<div class='question-title'>";
                $select_output .= "<label>". $values[0] ."</label>"; 
                $select_output .= "<span class='error'></span>";
                $select_output .= "</div>";
                
                $select_output .= "<div>";
                $select_output .= "<select>";
                $select_output .= "<option value=''>-------</option>";
                foreach($ff as $option){
                    $select_output .= "<option value='". $option["content"]["answer"] . "'";
                    //add the points here
                    if($option["content"]["answer"] == $values[2]){
                        $select_output .= "data-src-points=" . $values[3];
                    }else{
                        $select_output .= "data-src-points=" . $values[4];
                    }
                    $select_output .= ">";
                    $select_output .= $option["content"]["answer"];
                    $select_output .= "</option>";
                    
                }
                $select_output .= "</select>";
              
                $select_output .= "</div>";
               
                $select_output .= "</div>";
                echo $select_output;
               
            }
            if($type == "radios"){
               
               $r = $values[1];
               $rr = json_decode($r, true);
               $radios_output = "<div class='question radios'>";
               $radios_output .= "<div class='question-title'>";
               $radios_output .= "<p>". $values[0] ."</p>";
               $radios_output .= "<span class='error'></span>";
               $radios_output .= "</div>";
               $radios_output .= "<div class='radios-wrapper'>";
               
               #get the letters
               $c = count($rr);
               $c_array = [];
               $__i = 0;
               for($i = 'A' ; $i <= 'Z' ; $i++){
                if($__i < $c){
                    array_push($c_array , $i);
                    $__i++; 
                }else{
                    break;
                }
                
               }
               /*
               foreach (range('A', 'Z') as $_i => $char) {
                    if($_i < $c){
                        array_push($c_array , $char);
                    }else{
                        break;
                    }
               }
                */
               
               foreach($rr as $i => $radio){
                #print_r($radio["content"]["answer"]);
               
                    $radios_output .= "<div class='radio-wrapper'>";
                    $radios_output .= "<input type='radio' value='". $radio["content"]["answer"] ."'";
                    if($radio["content"]["answer"] == $values[2]){
                        $radios_output .= "data-src-points=" . $values[3];
                    }else{
                        $radios_output .= "data-src-points=" . $values[4];
                    }
                    $radios_output .= " />";
                    $radios_output .= "<label>". "<span>" .$c_array[$i] . ". " ."</span>"  . $radio["content"]["answer"] ."</label>";
                    $radios_output .= "</div>";     
               }
               
               $radios_output .= "</div>";
               
               $radios_output .= "</div>";
               echo $radios_output;
              
            }

            if($type == "checks"){
                $cb = $values[1];
                $cbs = json_decode($cb, true);
                #print_r($values[2]);
                $ans = [];
                $exp = explode(",", $values[2]);
                foreach($exp as $e){
                    array_push($ans, $e);
                }
               
                $cbs_output = "<div class='question checkboxes'>";
                $cbs_output .= "<div class='question-title'>";
                $cbs_output .= "<p>". $values[0] ."</p>";
                $cbs_output .= "<span class='error'></span>";
                $cbs_output .= "</div>";
                $cbs_output .= "<div class='checkboxes-wrapper' data-src-points=". $values[3]." data-src-selected=[] data-src-items='".json_encode($ans) ."' >";

                #get the letters
                $c = count($cbs);
                $c_array = [];
                $__i = 0;
                for($i = 'A' ; $i <= 'Z' ; $i++){
                if($__i < $c){
                    array_push($c_array , $i);
                    $__i++; 
                }else{
                    break;
                }
                
                }
                
                foreach($cbs as $i => $checkbox){
                    #print_r($checkbox["content"]["answer"]);
                    $cbs_output .= "<div class='checkbox-wrapper'>";
                    $cbs_output .= "<input type='checkbox' value='". $checkbox["content"]["answer"] ."'";
                   /*
                    if($checkbox["content"]["answer"] == $values[2]){
                        $cbs_output .= "data-src-points=" . $values[3];
                    }else{
                        $cbs_output .= "data-src-points=" . $values[4];
                    }
                    */
                    $cbs_output .= " />";
                    $cbs_output .= "<label>". "<span>" .$c_array[$i] . ". " ."</span>"  . $checkbox["content"]["answer"] ."</label>";
                    $cbs_output .= "</div>";     
                
                }
                $cbs_output .= "</div>";
                
                $cbs_output .= "</div>";
                echo $cbs_output;
            }

            if($type == "draganddrop"){
                #print_r($values);
                $boxes = json_decode($values[2], true);
                $images = json_decode($values[3], true);
                $get_image = $page->image($values[0][0]); 
                $ids = [];
                foreach($boxes as $item){
                    array_push($ids, $item["id"]);
                }
                $label_urls = [];
                //get the image
                #print_r($images[0]["content"]["images"]);

                foreach($images[0]["content"]["images"] as $img){
                   
                    $get_label_image = $page->image($img); 
                    $label_url = $get_label_image->url();
                    array_push($label_urls, $label_url);    
                   
                }
                
                #print_r($label_urls);
                $url = $get_image->url();
                #print_r($items);    
                $output = "<div class='question' data-src-score='' id='" . $values[1] . "'>";        
                $output .= "<div class='boxes' data-src-selected=[] data-src-items=". json_encode($ids) ." data-src-points='". $values[4] ."' data-src-wrong-points='".$values[5]."'>";
                $output .= "<img style='max-width: 100%' src='". $url ."' />";
               
                #generate the dropzones
                foreach($boxes as $key => $box){
                    #print_r($box["id"]);
                    #print_r($key);
                    $output .= "<div class='box box-" .$values[1]. "' ";
                    $output .= "id='". $box["id"] . "-dropzone" . "'";
                    $output .= "data-src-id='" . $box["id"] . "'" ;
                    $output .= "style='";
                    $output .= "left:". $box["left"] . ";";     
                    $output .= "top:". $box["top"] . ";";
                    $output .= "width:" . $box["width"] . ";";
                    $output .= "height:" . $box["height"] . ";'";   
                    $output .= "></div>";
                }    

                $output .= "<div style='width: 40%;margin:0 auto;position:absolute;'><span class='error'></span></div>";
                $output .= "</div>";   
               
                #generate the labels for the drag event    
                
                $output .= "<div class='image-labels" ."'>";
                foreach($boxes as $key => $box){
                    $output .= "<div class='rect image-label-". $values[1] . "'";
                    $output .= "data-src-id='" . $box["id"] . "'" ;
                    $output .= "style='";
                    $output .= "left:". $box["left"] . ";";     
                    $output .= "top:". $box["top"] . ";";
                    $output .= "width:" . $box["width"] . ";";
                    $output .= "height:" . $box["height"] . ";";
                    $output .= "background-image:url(" . $label_urls[$key] . ")";  
                    $output .= "'></div>";
                }
               
                $output .= "</div>";
              

                #add the script module
                $output .= "
                <script type='module'>
                import interact from 
                'https://cdn.interactjs.io/v1.10.11/interactjs/index.js';

                function dragMoveListener (event) {
                    var target = event.target
                    // keep the dragged position in the data-x/data-y attributes
                    var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
                    var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy
                  
                    // translate the element
                    target.style.transform = 'translate(' + x + 'px, ' + y + 'px)'
                  
                    // update the posiion attributes
                    target.setAttribute('data-x', x)
                    target.setAttribute('data-y', y)
                }


                var startPos = null;

                function dragStart(event){
                    var rect = interact.getElementRect(event.target);
                    // record center point when starting the very first a drag
                    startPos = {
                        x: rect.left + rect.width  / 2,
                        y: rect.top  + rect.height / 2
                    }

                    event.interactable.draggable({
                    snap: {
                        targets: [startPos]
                    }
                    });

                }

                window.dragMoveListener = dragMoveListener;
                window.dragStart = dragStart;
             
                
                interact('.image-label-". $values[1] ."')
                .draggable({
                    snap: {
                      targets: [startPos],
                      range: Infinity,
                      relativePoints: [ { x: 0.5, y: 0.5 } ],
                      endOnly: true
                    },
                    onstart: function (event) {
                        var rect = interact.getElementRect(event.target);
              
                        // record center point when starting the very first a drag
                        startPos = {
                          x: rect.left + rect.width  / 2,
                          y: rect.top  + rect.height / 2
                        }
              
                      event.interactable.draggable({
                        snap: {
                          targets: [startPos]
                        }
                      });
                    },
                    // call this function on every dragmove event
                    onmove: function (event) {
                      var target = event.target,
                          // keep the dragged position in the data-x/data-y attributes
                          x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
                          y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;
              
                      // translate the element
                      target.style.webkitTransform =
                      target.style.transform =
                        'translate(' + x + 'px, ' + y + 'px)';
              
                      // update the posiion attributes
                      target.setAttribute('data-x', x);
                      target.setAttribute('data-y', y);
                      target.classList.add('getting--dragged');
                    },
                    onend: function (event) {
                      event.target.classList.remove('getting--dragged')
                    }
                  });




                interact('.box-".$values[1]."').dropzone({
                    accept: '.image-label-".$values[1]."',
                    overlap: 0.0001,
                   
                    // listen for drop related events:
                        ondropactivate: function (event) {
                            
                          },
                          ondragenter: function (event) {
                           
                            console.log('dragenter');
                            var draggableElement = event.relatedTarget,
                                dropzoneElement  = event.target,
                                dropRect         = interact.getElementRect(dropzoneElement),
                                dropCenter       = {
                                  x: dropRect.left + dropRect.width  / 2,
                                  y: dropRect.top  + dropRect.height / 2
                                };
                    
                            event.draggable.draggable({
                              snap: {
                                targets: [dropCenter]
                              }
                            });
                    
                         
                          },
                          ondragleave: function (event) {
                            
                            event.draggable.draggable({
                                snap: {
                                    targets: []
                                }
                            });
                              
                                // remove the drop feedback style
                                event.target.classList.remove('drop-target')
                                event.relatedTarget.classList.remove('can-drop')
                                
                                //event.relatedTarget.textContent = 'Dragged out'
                                
                                //remove the id from the array
                                let droppedElId = event.relatedTarget.getAttribute('data-src-id');
                                let zoneId = event.target.getAttribute('data-src-id');
                                event.relatedTarget.classList.remove('greenBorder');
                                let selectedArr = event.target.parentElement.getAttribute('data-src-selected');
                                let parsed = JSON.parse(selectedArr);
                                let p = event.target.parentElement;
                                let error = p.querySelector('.error');

                                /*
                                if(parsed.includes(droppedElId)){
                                    let index = parsed.indexOf(droppedElId);
                                    parsed.splice(index, 1);
                                    event.target.parentElement.setAttribute('data-src-selected', JSON.stringify(parsed));
                                }else{
                                    let index = parsed.indexOf('null');
                                    parsed.splice(index, 1);
                                    event.target.parentElement.setAttribute('data-src-selected', JSON.stringify(parsed));
                                }
                                */


                            
                                if(parsed.includes(droppedElId + 'null')){
                                    let index = parsed.indexOf(droppedElId +'null');
                                    parsed.splice(index, 1);
                                    event.target.parentElement.setAttribute('data-src-selected', JSON.stringify(parsed));
                                }
                                
                                if(parsed.includes(droppedElId)){
                                    let index = parsed.indexOf(droppedElId);
                                    parsed.splice(index, 1);
                                    event.target.parentElement.setAttribute('data-src-selected', JSON.stringify(parsed));
                                }

                            
                                let _answers = JSON.parse(p.getAttribute('data-src-items'));
                                let _selected = JSON.parse(p.getAttribute('data-src-selected'));
                                let answers = [];
                                let selected = [];
                                
                                _answers.map(item =>{
                                    let _v = item.replace(/ /g,'')
                                    answers.push(_v.toLowerCase());
                                });

                                _selected.map(item =>{
                                    let _v = item.replace(/ /g,'')
                                    selected.push(_v.toLowerCase());
                                });
                                
                                //console.log(answers, selected);

                                if(selected.sort().compare(answers.sort())) {
                                    //console.log('array match');
                                    p.setAttribute('data-src-current-score', parseInt(p.getAttribute('data-src-points')));
                                } else {
                                    //console.log('array DOESNT match');
                                    p.setAttribute('data-src-current-score', parseInt(p.getAttribute('data-src-wrong-points')));
                                }

                                //check if the data-src-complete
                                let completed = event.target.parentElement.parentElement.parentElement.getAttribute('data-src-complete');
                            
                                if(parsed.length < 1){
                                    error.classList.add('red');
                                    error.innerHTML = 'This drag and drop is empty';
                                    event.target.parentElement.parentElement.parentElement.setAttribute('data-src-complete', 1)
                                }
                       
                            },
                            ondrop: function (event) {

                                Array.prototype.compare = function(testArr) {
                                    if (this.length != testArr.length) return false;
                                    for (var i = 0; i < testArr.length; i++) {
                                        if (this[i].compare) { //To test values in nested arrays
                                            if (!this[i].compare(testArr[i])) return false;
                                        }
                                        else if (this[i] !== testArr[i]) return false;
                                    }
                                    return true;
                                    } 
            
                                //console.log('DROPPED!!!');
                                //get the sources of the elements and compare them
            
                                let droppedElId = event.relatedTarget.getAttribute('data-src-id');
                                let zoneId = event.target.getAttribute('data-src-id');
                                event.relatedTarget.classList.add('greenBorder');
            
                                let selectedArr = event.target.parentElement.getAttribute('data-src-selected');
                                let p = event.target.parentElement;
                                let error = p.querySelector('.error');
                                //console.log(error);
                                let parsed = JSON.parse(selectedArr);
                                if(droppedElId === zoneId){
                                    if(parsed.includes(droppedElId)){
                                        //already in array
                                        console.log('already in array');
                                    }else{
                                        parsed.push(droppedElId);
                                        //console.log(parsed);
            
                                        event.target.parentElement.setAttribute('data-src-selected', JSON.stringify(parsed));
                                    }
                                
                                }else{
                                    console.log('NO MATCH');
                                    if(parsed.includes(droppedElId + 'null')){
                                        console.log('already in array');
                                    }else{
                                        parsed.push(droppedElId + 'null');
                                        event.target.parentElement.setAttribute('data-src-selected', JSON.stringify(parsed));
                                    }
                                    
                                }      
            
                                let _answers = JSON.parse(p.getAttribute('data-src-items'));
                                let _selected = JSON.parse(p.getAttribute('data-src-selected'));
                                let answers = [];
                                let selected = [];
            
                                
                                
                                _answers.map(item =>{
                                    answers.push(item.toLowerCase());
                                });
            
                                _selected.map(item =>{
                                    selected.push(item.toLowerCase());
                                });
                                
                                //console.log(answers, selected);
            
                                if(selected.sort().compare(answers.sort())) {
                                    //console.log('array match');
                                    p.setAttribute('data-src-current-score', parseInt(p.getAttribute('data-src-points')));
                                } else {
                                    //console.log('array DOESNT match');
                                    p.setAttribute('data-src-current-score', parseInt(p.getAttribute('data-src-wrong-points')));
                                }
            
            
                                //check if the data-src-complete is 0, then remove 1
                                let completed = event.target.parentElement.parentElement.parentElement.getAttribute('data-src-complete');
                                if(parsed.length > 0){
                                    error.classList.remove('red');
                                    error.innerHTML = '';
                                    event.target.parentElement.parentElement.parentElement.setAttribute('data-src-complete', 0)
                                }
                               
                          },
                          ondropdeactivate: function (event) {
                         
                       
                          }   

                })

              
                
                </script>
                ";
               
                $output .= "</div>"; 
                echo $output;    
               
            }

            if($type == "equi"){
                #print_r($values);
                $get_image = $page->image($values[1][0]); 
                $url = $get_image->url();
                $container = $values[2];
                $a = explode(",",$values[3]);
                $b = [];
                foreach($a as $_a){
                    array_push($b, $_a);
                }

                #masks    
                $masks = json_decode($values[6], true);
                $mask_images = $masks[0]["content"]["images"];

                #reveals
                $reveals = json_decode($values[7], true);
                $reveal_images = $reveals[0]["content"]["images"];
  
                #all images masks + reveals
                $all_images = array_merge( $mask_images, $reveal_images);
               
                
                $equi = "<div  class='question equi' data-src-items=". json_encode($b) ."><div id='".$container."' data-src-score=". $values[4]." class='inner-equi'><div style='margin-bottom: 2em'><label>". $values[0] ."</label></div><div class='info-message'>Pan around the scene by clicking and dragging. Double click on your answer.</div></div></div>";
               
                $equi .= "
                <script id='fragmentShaderHotSpot' type='x-shader/x-fragment'>
                    // Will have to manually change the hotspot texture on the fly and move the picking logic out to JS, retarded, but it should work
                    varying vec2 vUv;
                    varying vec3 vPosition;
                    varying vec3 vNormal;

                    uniform sampler2D map;
                    uniform int clicked;
                    uniform sampler2D hotspot;
                    uniform int hovered;
                    uniform sampler2D hhotspot;

                    void main() {
                        vec3 base = texture2D(map, vUv).rgb;
                        vec3 chighlight = vec3(255,0,0); // Click highlight - red
                        vec3 hsmap = texture2D(hotspot, vUv).rgb;
                        vec3 hhighlight = vec3(255,255,0); // Hover highlight - yellow
                        vec3 hhsmap = texture2D(hhotspot, vUv).rgb;
                        vec3 color = base;

                        // Do click lookup first so that hover will override it
                        if(clicked > 0){
                            color = mix(base, chighlight, ((hsmap.r + hsmap.g + hsmap.b) / float(765)));
                        }

                        // Do hover lookup (only if hovered hotspot and clicked hotspot don't match)
                        if(hovered > 0){
                            // This solution also hollows stuff out, if we made a completely black hotspot map, that will fix this
                            if(lessThan(clamp(hsmap, 0.0, 1.0), vec3(0.01,0.01,0.01)).r){
                            //if(clamp(hsmap, 0.0, 1.0) != vec3(1.0,1.0,1.0)){
                                color = mix(base, hhighlight, ((hhsmap.r + hhsmap.g + hhsmap.b) / float(765)));
                            }
                        }else if(clicked < 1){
                            color = base;
                        }

                        gl_FragColor = vec4(color, 1.0);
                    }
                </script>
                ";


                $equi .="
                <!-- vertex shader for main background -->
                <script id='vertexShaderHotSpot' type='x-shader/x-fragment'>
                    varying vec2 vUv;
                    varying vec3 vPosition;
                    varying vec3 vNormal;

                    void main(){
                        vUv = uv;
                        // Since the light is in camera coordinates
                        // I'll need the vertex position in camera coords too
                        vPosition = (modelViewMatrix * vec4(position, 1.0)).xyz;
                        vNormal = mat3(modelMatrix) * normal;
                        gl_Position = projectionMatrix * vec4(vPosition, 1.0);
                    }
                </script>

                ";    


                $equi .="
                <script type='module'>

                // Find the latest version by visiting https://cdn.skypack.dev/three.
                import * as THREE from 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.module.js';

                window.texturesloaded = 0;
                let camera, scene, skybox, mycanvas, container;
                var renderer, skytex;";
                
                #array will hold the names of the files
                $masks_arr = [];
                #loop through the images and pass their names to the new array
                 foreach($all_images as $name){
                    $renamed = explode(".", $name); 
                    array_push($masks_arr, $renamed[0]);
                }

                #generate the variable definitions, using the names  
                foreach($masks_arr as $m){
                    $equi .= "let " . $m . "_" . $container . ";";
                }
  

                $equi .="
                var texarray = [];
                const hotspots = {
                    ids: [],
                    clicked: [],
                    data: {}
                };
                const hotspotsdata = {};
                const raycaster = new THREE.Raycaster();
                const mouse = new THREE.Vector2();
                const onClickPosition = new THREE.Vector2();
                const offscreenCanvas = document.createElement('canvas');

                let isUserInteracting = false,
                onPointerDownMouseX = 0, onPointerDownMouseY = 0,
                lon = 0, onPointerDownLon = 0,
                lat = 0, onPointerDownLat = 0,
                phi = 0, theta = 0;
    
    
                function getIntersects( point, objects ) {
                    mouse.set( ( point.x * 2 ) - 1, - ( point.y * 2 ) + 1 );
                    raycaster.setFromCamera( mouse, camera );
                    return raycaster.intersectObjects( objects );
                }
        
                function getMousePosition( dom, x, y ) {
                    const rect = dom.getBoundingClientRect();
                    return [ ( x - rect.left ) / rect.width, ( y - rect.top ) / rect.height ];
                }

                function registerHotspot(mesh){
                    console.log('--> registering hotspots');
                    console.log(mesh);
                    // Add hotspot id to ids array
                    hotspots.ids.push(mesh.id);
                    // Pull texture data into hotspot data array
                    offscreenCanvas.getContext('2d').clearRect(0, 0, offscreenCanvas.width, offscreenCanvas.height);            
                    offscreenCanvas.getContext('2d').drawImage(mesh.material.map.image, 0, 0, mesh.material.map.image.width, mesh.material.map.image.height);
                    hotspots.data[mesh.id] = offscreenCanvas.getContext('2d').getImageData(0, 0, offscreenCanvas.width, offscreenCanvas.height);
                }
                ";

                #init function
                $equi .= "
                function init(){
                    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 1100);
                    camera.position.z = 0;
                
                    scene = new THREE.Scene();
        
                    // Create skybox
                    const skygeom = new THREE.SphereGeometry( 500, 60, 40 );
                    // invert the geometry on the x-axis so that all of the faces point inward
                    skygeom.scale( -1, 1, 1 );
                    window.hotspotss = hotspots;
                    // Change to use custom shader!
                    const skymat = new THREE.ShaderMaterial( { 
                        uniforms: {
                            map: {
                                value: skytex
                            },
                            clicked: {
                                value: 0
                            },
                            hotspot: {
                                value: 0 //hottex
                            },
                            hovered: {
                                value: 0
                            },
                            hhotspot: {
                                value: 0 //hottex
                            },
                            texArray: {
                                value: [
                ";    

                #seems we need to load the reveals only here
                #generate the textures array, need to match the variables defined for them
                foreach($reveal_images as $img){
                    $renamed = explode(".", $img); 
                    $equi .= "$renamed[0]" . "_" .  $container . ",";
                };
                
                $equi .= "           ]
                            }
                        },
                        fragmentShader: document.getElementById( 'fragmentShaderHotSpot' ).textContent,
                        vertexShader: document.getElementById( 'vertexShaderHotSpot' ).textContent
                    } );
                    
                    ";                    


                #create the hotspot spheres (geometries -> use the masks images)
                foreach($mask_images as $mask){
                    $renamed = explode(".", $mask); 
                    
                    #create the geometry
                    $equi .= "const ". "hotgeom_". $renamed[0] . "_" . $container ." = new THREE.SphereGeometry( 500, 60, 40 );";
                    #invert geometry
                    $equi .= "hotgeom_". $renamed[0] . "_" . $container .".scale( -1, 1, 1 );
                    
                    ";
                    
                    #create the material
                    $equi .= "const ". "hotmat_" . $renamed[0] . "_" . $container . "= new THREE.MeshBasicMaterial({";
                    #material inner attributes
                    $equi .= "map: " . $renamed[0] . "_" . $container . ", ";
                    $equi .= "opacity: 0, transparent: true";
                    $equi .= "});
                    
                    "; 
                }

                #create the meshes ( mesh -> use the reveals)
                foreach($reveal_images as $reveal){
                    $rrenamed = explode(".", $reveal); 
                    #remove the reveal part of the text
                    $_rrenamed = explode("_reveal", $reveal);
                    $equi .= "const ". "hotmesh_" . $rrenamed[0] . "_" . $container . "= new THREE.Mesh( ". "hotgeom_". $_rrenamed[0] . "_" . $container ." , " . "hotmat_" . $_rrenamed[0] . "_" . $container . " );
                    
                    ";
                    #add hotmesh attrs
                    $equi .= "hotmesh_" . $rrenamed[0] . "_" . $container . ".name=" . "'". $_rrenamed[0] ."'; 
                    
                    ";
                    #add the reveal
                    $equi .= "hotmesh_" . $rrenamed[0] . "_" . $container . ".reveal=" . $rrenamed[0] . "_" . $container . "; 
                    
                    "; 
                    #regiter the hotspot
                    $equi .= "registerHotspot(" . "hotmesh_" . $rrenamed[0] . "_" . $container . ");
                    
                    ";
                }


                #create the skymesh
                #use the url of the background image and add it to the scene
                $equi .= "
                    const skymesh = new THREE.Mesh( skygeom, skymat );
                    skybox = skymesh;
                    scene.add( skymesh );
                    ";

                #add to scene the meshes
                foreach($reveal_images as $reveal){
                    $rrenamed = explode(".", $reveal); 
                    #remove the reveal part of the text
                    $_rrenamed = explode("_reveal", $reveal);
                    $equi .= "scene.add(". "hotmesh_" . $rrenamed[0] . "_" . $container .");
                    
                    ";
                };

                #add variables
                $equi .= "
                    window.skyboox = skybox;
                    window.spoots = hotspots;
                    window.scene = scene;

                ";
              
                #add the canvas to the document
                $equi .= "
                    renderer = new THREE.WebGLRenderer( { antialias: true } );
                    renderer.setPixelRatio( window.devicePixelRatio );
                    container = document.getElementById('".$container."');
                    container.appendChild(renderer.domElement);
                    renderer.domElement.style.display = 'none';

                    let int = setInterval(
                        function(){
                           if(container.clientWidth != 0){
                              clearInterval(int);

                              let width='';
                                if(window.innerWidth < 650){
                                    width = container.clientWidth;
                                }else{
                                    width = container.clientWidth - 105;
        
                                }
        
                                //calc the height according the width / (16 / 9)
                                let height= width / (16/9);
                                console.log(width);
                                
                                renderer.setSize( width, height );
                                camera.aspect = width / height;
                                camera.updateProjectionMatrix();
                            
                                //display it
                            
                                renderer.domElement.style.display = 'block';



                           }
                        }, 
                        200
                    );

                 
                ";

                #hover event for highlights
                $equi .= "
                    //mouse event for the canvas regenerated by threejs
                    renderer.domElement.addEventListener('mousemove', async function(e) {
                        e.preventDefault();
                        onClickPosition.fromArray(
                        getMousePosition(
                            renderer.domElement,
                            e.clientX,
                            e.clientY
                            )
                        );
        
                        const intersects = getIntersects( onClickPosition, scene.children );
                        if ( intersects.length > 0 && intersects[ 0 ].uv ) {
                            //console.log(intersects);
                            for(let i = 0;i < intersects.length;i++){
                                // Only pay attention to our hotspots
                                if(hotspots.ids.indexOf(intersects[i].object.id) > -1){
                                    if(highlightIntersection(intersects[i])){
                                        // No need to loop anymore, we found our hotspot
                                        break;
                                    }
                                }
                            }
                        }
                    });
                
                
                ";   

                #select/click event    
                $equi .= "
                renderer.domElement.addEventListener('dblclick', async function(e) {
                    e.preventDefault();
                    onClickPosition.fromArray(
                    getMousePosition(
                        renderer.domElement,
                        e.clientX,
                        e.clientY
                        )
                    );
    
                    const intersects = getIntersects( onClickPosition, scene.children );
                    if ( intersects.length > 0 && intersects[ 0 ].uv ) {
                        console.log(intersects);
                        for(let i = 0;i < intersects.length;i++){
                            // Only pay attention to our hotspots
                            if(hotspots.ids.indexOf(intersects[i].object.id) > -1){
                                if(getCanvasColor(intersects[i])){
                                    // No need to loop anymore, we found our hotspot
                                    break;
                                }
                            }
                            // If on last loop and nothing found
                            if((i+1) == intersects.length){
                                console.log('hit!');
                                hotspots.clicked = [];
                                skybox.material.uniforms.hotspot.value = 0;
                                skybox.material.uniforms.clicked.value = [0, 0, 0, 0, 0];
                            }
                        }
                    }
                });
    
                ";    



                $equi .= "
                    //---- Equirectangular events start
                    container.style.touchAction = 'none';
                    container.addEventListener( 'pointerdown', onPointerDown );
                    container.addEventListener( 'wheel', onDocumentMouseWheel );
                    window.addEventListener( 'resize', onWindowResize );
                    //---- Equirectangular events end
                
                ";  

                #end of init function     
                $equi .= "}";    


                #custom Threejs functions    
                    
                #highlight auxiliar functions
                $equi .="
                
                    function highlightIntersection(intersected){
                        let uv = intersected.object.material.map.transformUv( intersected.uv );
                        if(drawDataURIOnCanvasForMouse(intersected.object, offscreenCanvas, uv)){
                            document.body.style.cursor = 'pointer';
                            skybox.material.uniforms.hhotspot.value = intersected.object.reveal;
                            
                            skybox.material.uniforms.hovered.value = 1;
                            return true;
                        }
                        document.body.style.cursor = '';
                        skybox.material.uniforms.hhotspot.value = 0;
                        skybox.material.uniforms.hovered.value = 0;
                        return false;
                    }
                
                ";   

                $equi .= "
                    function drawDataURIOnCanvasForMouse(hotspot, canvas, uv) {
                        // get the exact pixel clicked from %age values
                        uv.x = Math.floor(uv.x * hotspot.material.map.image.width);
                        uv.y = Math.floor(uv.y * hotspot.material.map.image.height);
                        let pixelData = [
                            hotspots.data[hotspot.id].data[(4 * (uv.x + uv.y*offscreenCanvas.width))],
                            hotspots.data[hotspot.id].data[(4 * (uv.x + uv.y*offscreenCanvas.width))+1],
                            hotspots.data[hotspot.id].data[(4 * (uv.x + uv.y*offscreenCanvas.width))+2],
                            hotspots.data[hotspot.id].data[(4 * (uv.x + uv.y*offscreenCanvas.width))+3]
                        ];
                        // Return boolean to let calling function know
                        if((pixelData[0] == 0) && (pixelData[1] == 0) && (pixelData[2] == 0) && (pixelData[3] == 255)){
                            return false;
                        }
                        return true;
                    }

                ";    

                #select/click auxiliar functions    

                $equi .= "
                    function getCanvasColor(intersected){
                        // Return a 0 - 1 decimal representation of positioning in the image dimensions
                        let uv = intersected.object.material.map.transformUv( intersected.uv );
                        // Update mesh buffer value here for which hotspot was clicked, if one was clicked
                        if(drawDataURIOnCanvas(intersected.object, offscreenCanvas, uv)){
                            skybox.material.uniforms.hotspot.value = intersected.object.reveal;
                            skybox.material.uniforms.clicked.value = 1;
                            return true;
                        }
                        return false;
                    }
                ";    


                $equi .= "
                    function drawDataURIOnCanvas(hotspot, canvas, uv) {
                        // get the exact pixel clicked from %age values
                        uv.x = Math.floor(uv.x * hotspot.material.map.image.width);
                        uv.y = Math.floor(uv.y * hotspot.material.map.image.height);
                        let pixelData = [
                            hotspots.data[hotspot.id].data[(4 * (uv.x + uv.y*offscreenCanvas.width))],
                            hotspots.data[hotspot.id].data[(4 * (uv.x + uv.y*offscreenCanvas.width))+1],
                            hotspots.data[hotspot.id].data[(4 * (uv.x + uv.y*offscreenCanvas.width))+2],
                            hotspots.data[hotspot.id].data[(4 * (uv.x + uv.y*offscreenCanvas.width))+3]
                        ];
                        // Return boolean to let calling function know
                        if((pixelData[0] == 0) && (pixelData[1] == 0) && (pixelData[2] == 0) && (pixelData[3] == 255)){
                            return false;
                        }

                        //check the parent
                        let pp =renderer.domElement.parentElement.parentElement.parentElement; 
                        let p = renderer.domElement.parentElement.parentElement;
                        let c = renderer.domElement.parentElement;
                        
                        let selectedItem = [];

                        console.log(hotspot.name)

                        selectedItem.push(hotspot.name);
                        p.setAttribute('data-src-selected-items', JSON.stringify(selectedItem));
     
                        console.log('---->', p.getAttribute('data-src-selected-items'));
     
                        if(p.getAttribute('data-src-selected-items') == null || p.getAttribute('data-src-selected-items') == '[]'){
                            pp.setAttribute('data-src-complete', 1);
                            let err = c.querySelector('.error');
                            err.innerHTML = 'This panorama is empty';
                            err.classList.add('red'); 
                        }else{
                            let err = c.querySelector('.error');
                            err.innerHTML = '';
                            err.classList.remove('red'); 
                            pp.setAttribute('data-src-complete', 0);
                        }  
                        return true;
                    }
                    
                ";    

                #default Threejs functions
                $equi .= "
                function animate() {
                    requestAnimationFrame( animate );
                    update();
                }
        
                function update() {        
                    lat = Math.max( - 85, Math.min( 85, lat ) );
                    phi = THREE.MathUtils.degToRad( 90 - lat );
                    theta = THREE.MathUtils.degToRad( lon );
        
                    const x = 500 * Math.sin( phi ) * Math.cos( theta );
                    const y = 500 * Math.cos( phi );
                    const z = 500 * Math.sin( phi ) * Math.sin( theta );
        
                    camera.lookAt( x, y, z );
                    renderer.render( scene, camera );
                }
        
                function onWindowResize() {
                

                    let width = '';
                    //make the resize take the full container
                    if(window.innerWidth < 650){
                        width = container.clientWidth;
                    }else{
                        width = container.clientWidth - 105;
                    }   

                    let height = width / (16/9);
                    camera.aspect = width / height;
                    camera.updateProjectionMatrix();
                    renderer.setSize( width, height );

                }
        
                function onPointerDown( event ) {
                    if ( event.isPrimary === false ) return;
        
                    isUserInteracting = true;
        
                    onPointerDownMouseX = event.clientX;
                    onPointerDownMouseY = event.clientY;
        
                    onPointerDownLon = lon;
                    onPointerDownLat = lat;
        
                    document.addEventListener( 'pointermove', onPointerMove );
                    document.addEventListener( 'pointerup', onPointerUp );
                }
        
                function onPointerMove( event ) {
                    if ( event.isPrimary === false ) return;
        
                    lon = ( onPointerDownMouseX - event.clientX ) * 0.1 + onPointerDownLon;
                    lat = ( event.clientY - onPointerDownMouseY ) * 0.1 + onPointerDownLat;
                }
        
                function onPointerUp() {
                    if ( event.isPrimary === false ) return;
        
                    isUserInteracting = false;
        
                    document.removeEventListener( 'pointermove', onPointerMove );
                    document.removeEventListener( 'pointerup', onPointerUp );
                }
        
                function onDocumentMouseWheel( event ) {
                    //$('body').addClass('stop-scrolling');
                    const fov = camera.fov + event.deltaY * 0.05;
        
                    camera.fov = THREE.MathUtils.clamp( fov, 10, 75 );
        
                    camera.updateProjectionMatrix();
                
                }

                "; 


               

                #load the textures

                #get all the images
                #get their urls and push them to a new array
                $images_arr = [];
                foreach($all_images as $image){
                    $image_file = $page->image($image);
                    $image_url = $image_file->url();
                    array_push($images_arr, $image_url); 
                }

                $ii = 0;
                $count = count($masks_arr);
                $equi .= "let txts =". ($count + 1). " ;
                
                "; 

                #load the background aka sky
                $equi .= "
                    // Wait until the darn textures are ready!
                    let numTs= 0;
                    
                    function tryInit(loaded){
                        console.log(loaded);
                        
                        if(loaded == txts){
                            init();
                            animate();
                        
                        }
                    }

                    //loads the first image, the background
                    skytex = new THREE.TextureLoader().load('". $url ."', function(tex){
                        offscreenCanvas.width = tex.image.width;
                        offscreenCanvas.height = tex.image.height;
                        numTs++;
                        tryInit(numTs);
                    });
                
                ";
             

                #load the rest of the images textures
                foreach($masks_arr as $m => $v){
                     
                    $equi .= $v . "_" . $container;
                    $equi .= "= new THREE.TextureLoader().load(". "'" . $images_arr[$ii]  ."'" . ", function(tex){";
                 
                    $equi .= "numTs++;";
                    $equi .= "tryInit(numTs);";
                    $equi .= "});";
                    #add name to the texture here
                    #push them to the texarray
                    $equi .= "texarray.push(" .  $v . "_" . $container . ");";
                    $ii++;
                    
                 };    


                $equi .= "</script>";    
                echo $equi;
                
            }

        }
   
   
        
    }
   
}

?>

</div>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?= $site->title() ?></title>
  <meta name="description" content="Quiz template for Shell">
  <meta name="author" content="aldocaava - NytrobitLabs">
  <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
  <script src="../shared/contentfunctions.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"> 
  <!-- slick -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css" integrity="sha512-wR4oNhLBHf7smjy0K4oqzdWumd+r5/+6QO/vDda76MW5iug4PT7v86FoEkySIJft3XA0Ae6axhIvHrqwm793Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css" integrity="sha512-6lLUdeQ5uheMFbWm3CP271l14RsX1xtx+J5x2yeIDkkiBpeVTNhTqijME7GgRKKi6hCqovwCoBTlRBEC20M8Mg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- lity -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lity/2.4.1/lity.min.js" integrity="sha512-UU0D/t+4/SgJpOeBYkY+lG16MaNF8aqmermRIz8dlmQhOlBnw6iQrnt4Ijty513WB3w+q4JO75IX03lDj6qQNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lity/2.4.1/lity.min.css" integrity="sha512-UiVP2uTd2EwFRqPM4IzVXuSFAzw+Vo84jxICHVbOA1VZFUyr4a6giD9O3uvGPFIuB2p3iTnfDVLnkdY7D/SJJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<?php 
  $background = $page->back();
  $replaced = str_replace("- ", "", $background);
  
  $get_image = $page->image($replaced); 
  $url = $get_image->url();

?>


<style>
  
/**
---- Normalization
*/
/*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */

/* Document
   ========================================================================== */

/**
 * 1. Correct the line height in all browsers.
 * 2. Prevent adjustments of font size after orientation changes in iOS.
 */

html {
  line-height: 1.15; /* 1 */
  -webkit-text-size-adjust: 100%; /* 2 */
}

/* Sections
   ========================================================================== */

/**
 * Remove the margin in all browsers.
 */

body {
  margin: 0;
}

/**
 * Render the `main` element consistently in IE.
 */

main {
  display: block;
}

/**
 * Correct the font size and margin on `h1` elements within `section` and
 * `article` contexts in Chrome, Firefox, and Safari.
 */

h1 {
  font-size: 2em;
  margin: 0.67em 0;
}

/* Grouping content
   ========================================================================== */

/**
 * 1. Add the correct box sizing in Firefox.
 * 2. Show the overflow in Edge and IE.
 */

hr {
  box-sizing: content-box; /* 1 */
  height: 0; /* 1 */
  overflow: visible; /* 2 */
}

/**
 * 1. Correct the inheritance and scaling of font size in all browsers.
 * 2. Correct the odd `em` font sizing in all browsers.
 */

pre {
  font-family: monospace, monospace; /* 1 */
  font-size: 1em; /* 2 */
}

/* Text-level semantics
   ========================================================================== */

/**
 * Remove the gray background on active links in IE 10.
 */

a {
  background-color: transparent;
}

/**
 * 1. Remove the bottom border in Chrome 57-
 * 2. Add the correct text decoration in Chrome, Edge, IE, Opera, and Safari.
 */

abbr[title] {
  border-bottom: none; /* 1 */
  text-decoration: underline; /* 2 */
  text-decoration: underline dotted; /* 2 */
}

/**
 * Add the correct font weight in Chrome, Edge, and Safari.
 */

b,
strong {
  font-weight: bolder;
}

/**
 * 1. Correct the inheritance and scaling of font size in all browsers.
 * 2. Correct the odd `em` font sizing in all browsers.
 */

code,
kbd,
samp {
  font-family: monospace, monospace; /* 1 */
  font-size: 1em; /* 2 */
}

/**
 * Add the correct font size in all browsers.
 */

small {
  font-size: 80%;
}

/**
 * Prevent `sub` and `sup` elements from affecting the line height in
 * all browsers.
 */

sub,
sup {
  font-size: 75%;
  line-height: 0;
  position: relative;
  vertical-align: baseline;
}

sub {
  bottom: -0.25em;
}

sup {
  top: -0.5em;
}

/* Embedded content
   ========================================================================== */

/**
 * Remove the border on images inside links in IE 10.
 */

img {
  border-style: none;
}

/* Forms
   ========================================================================== */

/**
 * 1. Change the font styles in all browsers.
 * 2. Remove the margin in Firefox and Safari.
 */

button,
input,
optgroup,
select,
textarea {
  font-family: inherit; /* 1 */
  font-size: 100%; /* 1 */
  line-height: 1.15; /* 1 */
  margin: 0; /* 2 */
}

/**
 * Show the overflow in IE.
 * 1. Show the overflow in Edge.
 */

button,
input { /* 1 */
  overflow: visible;
}

/**
 * Remove the inheritance of text transform in Edge, Firefox, and IE.
 * 1. Remove the inheritance of text transform in Firefox.
 */

button,
select { /* 1 */
  text-transform: none;
}

/**
 * Correct the inability to style clickable types in iOS and Safari.
 */

button,
[type="button"],
[type="reset"],
[type="submit"] {
  -webkit-appearance: button;
}

/**
 * Remove the inner border and padding in Firefox.
 */

button::-moz-focus-inner,
[type="button"]::-moz-focus-inner,
[type="reset"]::-moz-focus-inner,
[type="submit"]::-moz-focus-inner {
  border-style: none;
  padding: 0;
}

/**
 * Restore the focus styles unset by the previous rule.
 */

button:-moz-focusring,
[type="button"]:-moz-focusring,
[type="reset"]:-moz-focusring,
[type="submit"]:-moz-focusring {
  outline: 1px dotted ButtonText;
}

/**
 * Correct the padding in Firefox.
 */

fieldset {
  padding: 0.35em 0.75em 0.625em;
}

/**
 * 1. Correct the text wrapping in Edge and IE.
 * 2. Correct the color inheritance from `fieldset` elements in IE.
 * 3. Remove the padding so developers are not caught out when they zero out
 *    `fieldset` elements in all browsers.
 */

legend {
  box-sizing: border-box; /* 1 */
  color: inherit; /* 2 */
  display: table; /* 1 */
  max-width: 100%; /* 1 */
  padding: 0; /* 3 */
  white-space: normal; /* 1 */
}

/**
 * Add the correct vertical alignment in Chrome, Firefox, and Opera.
 */

progress {
  vertical-align: baseline;
}

/**
 * Remove the default vertical scrollbar in IE 10+.
 */

textarea {
  overflow: auto;
}

/**
 * 1. Add the correct box sizing in IE 10.
 * 2. Remove the padding in IE 10.
 */

[type="checkbox"],
[type="radio"] {
  box-sizing: border-box; /* 1 */
  padding: 0; /* 2 */
}

/**
 * Correct the cursor style of increment and decrement buttons in Chrome.
 */

[type="number"]::-webkit-inner-spin-button,
[type="number"]::-webkit-outer-spin-button {
  height: auto;
}

/**
 * 1. Correct the odd appearance in Chrome and Safari.
 * 2. Correct the outline style in Safari.
 */

[type="search"] {
  -webkit-appearance: textfield; /* 1 */
  outline-offset: -2px; /* 2 */
}

/**
 * Remove the inner padding in Chrome and Safari on macOS.
 */

[type="search"]::-webkit-search-decoration {
  -webkit-appearance: none;
}

/**
 * 1. Correct the inability to style clickable types in iOS and Safari.
 * 2. Change font properties to `inherit` in Safari.
 */

::-webkit-file-upload-button {
  -webkit-appearance: button; /* 1 */
  font: inherit; /* 2 */
}

/* Interactive
   ========================================================================== */

/*
 * Add the correct display in Edge, IE 10+, and Firefox.
 */

details {
  display: block;
}

/*
 * Add the correct display in all browsers.
 */

summary {
  display: list-item;
}

/* Misc
   ========================================================================== */

/**
 * Add the correct display in IE 10+.
 */

template {
  display: none;
}

/**
 * Add the correct display in IE 10.
 */

[hidden] {
  display: none;
}

/**
--- End of Normalization
*/

html {
  height: 100%;
}



@font-face {
    font-family: 'din_mediumregular';
    src: url('<?php echo url("assets/fonts/din_medium_regular-webfont.woff2");  ?>') format('woff2'),
         url('<?php echo url("assets/fonts/din_medium_regular-webfont.woff");  ?>') format('woff');
    font-weight: normal;
    font-style: normal;

}


@font-face {
    font-family: 'dinbold';
    src: url('<?php echo url("assets/fonts/din_bold-webfont.woff2"); ?>') format('woff2'),
         url('<?php echo url("assets/fonts/din_bold-webfont.woff"); ?>') format('woff');
    font-weight: normal;
    font-style: normal;

}

body {
  margin:0px;
  padding: 0px;
  background: url(<?php echo $url?>);
  background-size: cover;
  background-attachment: fixed;
  min-height: 100%;
  color: #fff;
  font-size: 1.1em;
 position: relative;
 font-family: "dinbold";
 
}

.container {
  overflow: hidden; 
  position: relative;
  z-index: 10;
  padding-bottom: 2em;
  display:none;
}

.header_bar{

  display:flex;
  z-index:99;
  padding: 2em 2em;
  align-items:center;
  justify-content: space-between;
}

#progress_bar{
  border: 1px solid blue;
}
.hide {
  display: none;
}
.hidden, .off {
  visibility: hidden;
  height: 0px;
  display: block;
  max-height: 0px;
  overflow: hidden;
}


.carousel {
 max-width: 1860px;
 padding-left: 5.2em;
 margin-bottom: 1em;
}

.carousel-item {
  width: 725px;
  height: 400px;
  background-size: cover;
  background-position: center;
  
  min-width:300px;
  box-sizing: border-box 

}

.slick-slide {
  margin: 0 27px;
  
}
.slick-list {
  margin: 0 -27px;
  width: 1860px;
    
}

.error{
  width: 150px;
  text-align: center;
  margin-top: 0.6em;
  color: #fff;
  padding: 10px;
  display: block;
  font-size: 0.78em;
}

.info-message{
  margin-bottom: 0.6em;
  background: #FAA613;
  padding: 10px;
}

.red {
  background: #E66460;
}

.selects > label, .texts > label, .radios > label, .textareas > label, .checkboxes > label{
  display:block;
}


.custom-tooltip{
  background: rgba(0,0,0,0.1);
  clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
  width: 50px;
  height: 50px;
  box-shadow: 1px 0px 0px #000;
}

.custom-tooltip span {
  display:none;
}


.radios, .checkboxes  , .texts, .textareas, .selects, .data-content {
  display:flex;
  justify-content: center;
  width: 80%;
  max-width: 1200px;
  margin: 0 auto;
}

.data-content {
  margin-bottom: 0em;
  font-family: "din_mediumregular";
}

.questions > div:last-of-type {
  margin-bottom: 2em;
}
.question-title{
  flex-basis: 50%;
}
.question-title > p , .question-title > label{
  margin: 0px;
  padding:0px;
}

input, .radio-wrapper > label, .checkbox-wrapper > label {
  font-family: "din_mediumregular";
}

em {
  font-family: "dinbold";
}


.radios p, .checkboxes p,.textareas label, .texts label, .selects label{
  font-size: 1.7em;
  
}

input[type="radio"], input[type="checkbox"]{
  display:none;
}

.radios-wrapper, .checkboxes-wrapper .textareas > div:last-of-type, .selects > div:last-of-type, .texts > div:last-of-type {
  margin-left: 8em;
 
}

.radios-wrapper > div, .checkboxes-wrapper > div  {
  border: 2px solid #25aae1;
  color: #25aae1;
  border-radius: 1em;
  margin-bottom: 0.7em;
  
}

.radios-wrapper > div > label, .checkboxes-wrapper > div > label {
  cursor: pointer;
  display:block;
  padding: 10px;
}

.radios-wrapper > div > label > span .checkboxes-wrapper > div > label > span {
  color: #fff;

}

.radios > p, .checkboxes > p {
  margin-top: 0px;
}

.pnlm-container{
  margin: 0 auto;
}
progress {
  background: transparent;
}
progress::-moz-progress-bar
{
    background: blue;
}
progress::-webkit-progress-bar
{
    background: transparent;
}
progress::-webkit-progress-value
{
    background: blue;
}
/*
#questions_bar{
  opacity: 0;
  margin-top: -2em;
}
*/
#questions_bar > p {
  margin-bottom: 0.1em;
}
.next, .prev {
  display: none;
}

.buttons-container {
  position: absolute;
  width: 100%;
  margin: 0 auto;
  top: 0;
  height: 100%;
 
}

.prev, .next {
  position: absolute;
  bottom: 2em;
  z-index: 90;
  background: none;
  color: white;
  border: none;
  padding: 10px;
  cursor: pointer;
}

.next::after {
  content: "";
  width: 10px;
  height: 10px;
  border: solid #25aae1;
  border-width: 0 3px 3px 0;
  display: inline-block;
  transform: rotate(-45deg);
  -webkit-transform: rotate(-45deg);
  margin-left:10px;
}


.prev::before {
  content: "";
  width: 10px;
  height: 10px;
  border: solid #25aae1;
  border-width: 0 3px 3px 0;
  display: inline-block;
  transform: rotate(135deg);
  -webkit-transform: rotate(135deg);
  margin-right:10px;
}

.next {
  right: 2em;
}

.prev {
  left: 2em;
}

.selected {
  background: #25aae1;
}

.selected > label {
  color: #fff;
  
}
.prev, .next {
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

canvas {
  margin: 0 auto;
}


.inner-equi {
  
  
  /*
  width: 100%;
  max-width: 1280px;
  height: 720px;
  aspect-ratio: 16 / 9;
  border: 1px solid red;
  margin: 0 auto;

  */
  
  padding: 0 3em 5em;
  max-width: 1280px;
  height: 720px;
  margin: 0 auto;

}




.stop-scrolling {
  height: 100%;
  overflow: hidden;
}

.loader {
  position: absolute;
  top: 0px;
  left:0px;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  z-index: 99;
}


.loader-wrapper {
  text-align: center;
  position: relative;
  top: 50%;
}

.lds-grid {
  display: block;
  position: relative;
  width: 80px;
  height: 80px;
  margin: 0 auto;
}
.lds-grid div {
  position: absolute;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: #fff;
  animation: lds-grid 1.2s linear infinite;
}
.lds-grid div:nth-child(1) {
  top: 8px;
  left: 8px;
  animation-delay: 0s;
}
.lds-grid div:nth-child(2) {
  top: 8px;
  left: 32px;
  animation-delay: -0.4s;
}
.lds-grid div:nth-child(3) {
  top: 8px;
  left: 56px;
  animation-delay: -0.8s;
}
.lds-grid div:nth-child(4) {
  top: 32px;
  left: 8px;
  animation-delay: -0.4s;
}
.lds-grid div:nth-child(5) {
  top: 32px;
  left: 32px;
  animation-delay: -0.8s;
}
.lds-grid div:nth-child(6) {
  top: 32px;
  left: 56px;
  animation-delay: -1.2s;
}
.lds-grid div:nth-child(7) {
  top: 56px;
  left: 8px;
  animation-delay: -0.8s;
}
.lds-grid div:nth-child(8) {
  top: 56px;
  left: 32px;
  animation-delay: -1.2s;
}
.lds-grid div:nth-child(9) {
  top: 56px;
  left: 56px;
  animation-delay: -1.6s;
}
@keyframes lds-grid {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.box {
  position: absolute;
  /*
  background: rgba(0, 0, 0, 0.5);
  */
}
.rect {
  border: 5px solid #fff;
  box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  margin: 0.4em;
}

.greenBorder {
  border-color: #5CC163;
}

.boxes, .image-labels{
  width: 100%;
  max-width: 640px;
  min-width: 300px;
}

.boxes {
 
  position:relative;
  margin: 0 auto;
 
}

.image-labels {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  margin: 4em auto 0em auto;
 
}
iframe {
  margin-bottom: 1em;
}


@media screen and (max-width:1280px){
  body {
    overflow-x: hidden;
  }
  .carousel {
  max-width: 1260px;
  }

  .carousel-item {
    width: 475px;
    height: 300px;
    background-size: cover;
    background-position: center;
    min-width:300px; 

  }

  .slick-slide {
    margin: 0 20px;
    
  }
  .slick-list {
    margin: 0 -27px;
    width: 1260px;
      
  }

  .data-content > p {
    font-size: 0.8em;
  }

  .question {
    flex-direction: column;
  }

  .radios-wrapper, .checkboxes-wrapper .textareas > div:last-of-type, .selects > div:last-of-type, .texts > div:last-of-type {
    margin-left: 0em;
    margin-top: 0em;
  }

  .prev, .next {
    font-size: 0.8em;
  }


  .next::after {
    width: 8px;
    height: 8px;
    margin-left: 6px;
  }

  .prev::before {
  
    width: 8px;
    height: 8px;
    margin-right:6px;
  }

/*
.inner-equi{
  
    height: 600px;
  }
  */
}



@media screen and (max-width: 650px){
  form {
    padding: 0 2.1em;
  }
  .carousel {
    padding-left: 2.2em;
  }
  .data-content, .question {
    width: 100%;
    /*padding: 0px 2.2em 0px 2.2em;*/
  }
  canvas {
    margin: 0px;
  }
  .current_question > div:last-of-type {
   margin-bottom: 3em;
  }

  #logo > img {
    width: 80%;
    min-width: 200px;
  }

  #questions_bar{
    font-size:0.8em;
  }
  #questions_bar > p {
    text-align: center;
  }
  .header_bar{
   padding: 2em 1em;
   display:block;
  }
  #progress_bar, input, textarea, select{
    width: 100%;
  }
  
  .next {
  right: 1em;
  }

  .prev {
    left: 1em;
  }
  
  .inner-equi{
    height: 400px;
    padding: 0em;
  }
  
  

}

</style>

<?php 
  $logo = $page->logo();
  $replaced = str_replace("- ", "", $logo);
  
  $get_image = $page->image($replaced); 
  $url = $get_image->url();
  
?>


<body>

    <div class="header_bar">
      <div id="logo"><?php echo "<img src='". $url ."' />" ?></div>
      <div id="questions_bar">
        <!-- remove the "1" from the current_question and let js fill it, if the bar must be hidden again-->
        <p>Question <span id="current_question">1</span> of <span id="total_questions" ></span></p>
        <progress id="progress_bar" value="0" max="100"></progress>
      </div>
    </div>
   <div class='loader'>
    <div class='loader-wrapper'>
      <div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
      <span>Loading assets, please wait ...</span> 
    </div>
  </div>
   <div class="container">
     <!-- <h1><?= $page->title() ?></h1>-->
      <p id="isLast" style="display:none"><?= $page->lastPage()->html() ?></p>
      <form id="open_form">
      <?= $page->parts()->toBlocks(); ?>
      </form>
    </div>


    <div class="buttons-container">
      <button class="prev">Previous</button>
      <button class="next">Next</button>
    </div>

  <script>


   
$(function(){ 
  

  //init vars
  let sc;
  let radioPoints = 0;
  let selectPoints = 0;
  let textPoints = 0;
  let textareaPoints = 0;
  let ls;
  let sections_arr = [];
 
  //check available sections
  let sections = $(".questions");
  let done_array = [];
  let question_sections = $(".questions").not(".contentOnly");
  //console.log(question_sections.length, sections.length);
  //let sections_length = sections.length;
  //get the sections that dont have the "hide" class
  let _sections = [];
  let questions = $(".questions");
  let total_score = 0;

  let current_index = null;
  //load the slick carousels
  $('.carousel').slick({
    dots: false,
    prevArrow: false,
    nextArrow: false,
    infinite: true,
    variableWidth: true,
    slidesToShow: 3,
   
  });



  //TODO-- add a loader
  //$(".container").show("slow");

  setTimeout(() => {
    $(".loader").css({"display": "none"})
    $(".container").css({"display": "block"});
    $(".inner-equi").append("<span class='error'></span>");
    $("canvas").on("mouseleave", function(){
      $("body").css({"cursor": ""})
    });


    let h = $(".boxes").height();
    let rects = $(".rect");
    let boxs = $(".box")
    $.each(rects, function(key, value){
     //console.log(boxs[key]);
     $(value).css({"width": $(boxs[key]).css("width"),
                   "height": $(boxs[key]).css("height")
                  });
                  //console.log($(boxs[key]).css("width"), $(boxs[key]).css("height"));
    });

   

  }, 1000);

  $( window ).resize(function() {
    let rects = $(".rect");
    let boxs = $(".box")
    $.each(rects, function(key, value){
     //console.log(boxs[key]);
     $(value).css({"width": $(boxs[key]).css("width"),
                    "height": $(boxs[key]).css("height")
                   });
    
    });
  });


  //get the total number of sections with a question
  //and add it as the value of the total questions
  $("#total_questions").text(question_sections.length);
  
 
  //get the values of the section fields
  function setSectionStorage(parent, field, value){
    
    //console.log("hey")
    let parentId= $(parent).attr("id");
    let arr = [];
    let obj = {};
    obj[field] = value;
   
    if(localStorage.getItem(parentId)){
      let item = localStorage.getItem(parentId);
      let parsed = JSON.parse(item);
      let entries = []; 
      $.each(parsed, function (el, val) {
        entries.push(Object.keys(val)[0]);
        //console.log(Object.keys(val)[0])
        if(Object.keys(val)[0] == field){
          //console.log("match!!")
          val[field] = value;
        }
      });


      if(entries.includes(field)){

      }else{
        parsed.push({
              [field]: value
            });
      }

      localStorage.setItem(parentId, JSON.stringify(parsed));
    }else{
      //console.log("pushing first time")
      arr.push(obj);
      localStorage.setItem(parentId, JSON.stringify(arr));
    }
    
  }


  //add the correct names to the texts
  let t = $("form input[type='text']");    
  $.each(t, function(index , val){
    $(val).attr("name", "t" + ( index + 1));
    $(val).attr("data-src-current-score", 0);
    
    $(val).on("keyup", function(){
    
      if($(this).val() == ""){
        let p = $(this).parent().parent().parent()
        substractFromProgressBar(p);
        $(this).parent().parent().find(".error").text("this field is required");
        $(this).parent().parent().find(".error").addClass("red");
        $(this).attr("data-src-current-score", parseInt($(this).attr("data-src-score-wrong")));
        $(this).parent().parent().parent().attr("data-src-complete", 1);
     }else{
       $(this).parent().parent().find(".error").text("");
       $(this).parent().parent().find(".error").removeClass("red");
       if($(this).val().toLowerCase() == $(this).attr("data-src-value")){
         $(this).attr("data-src-current-score", parseInt($(this).attr("data-src-score")));
         
       }else{
         $(this).attr("data-src-current-score", parseInt($(this).attr("data-src-score-wrong")));
         
       }
       $(this).parent().parent().parent().attr("data-src-complete", 0);
     }
     
    });

    //store the data
    $(val).on("change", function(){
      //setSectionStorage($(val).parent().parent(), $(val).attr("name"), $(val).val());
    });
    
  });
  
  //add the correct names to the selects
  let s = $("form select");
   $.each(s, function(index, val){
    $(val).attr("name", "s" + (index + 1))
    $(val).attr("data-src-current-score", 0);
   
    $(val).on("change", function(){
      let currVal = $(this).val();
     
      if(currVal == ""){
        let p = $(this).parent().parent().parent();
        substractFromProgressBar(p);
        $(this).parent().parent().parent().find(".error").text("this field is required");
        $(this).parent().parent().parent().find(".error").addClass("red");
        $(this).parent().parent().parent().attr("data-src-complete", 1);
      }else{
        $(this).parent().parent().parent().find(".error").text("");
        $(this).parent().parent().parent().find(".error").removeClass("red");
        //console.log(currVal);
        let options = $(this).find("option");
       
        $.each(options, function(k, v) {
          if($(v).attr("value") == currVal){
            $(this).parent().attr("data-src-current-score", parseInt($(v).attr("data-src-points")));
          } 
         });
         $(this).parent().parent().parent().attr("data-src-complete", 0);
      }
      
    })
  });
  let _v = 1;
  //add the correct names to the radios
  let q = $("form .radios");
  $.each(q, function(el, val){
    let v = $(val).find("input");
    //console.log( "---->" , $(val).val());
    
     $.each(v, function(v , val){
       $(val).attr("name", "q" + el);
       $(val).attr("id", "qid" +  _v);
       
       //get the labels and add the "for" element
       $(val).parent().find("label").attr("for", "qid" + _v );
       $(val).on("change", function(){
       //console.log($(val).attr("name"));
     
       if($(this).is(":checked") == true){
          $(this).parent().parent().parent().find(".error").text("");
          $(this).parent().parent().parent().find(".error").removeClass("red");
          $(this).parent().parent().attr("data-src-current-score", parseFloat($(this).attr("data-src-points")));
        
        }
        if($(this).is(":checked") == false){
          $(this).parent().parent().parent().find(".error").text("this field is required");
          $(this).parent().parent().parent().find(".error").addClass("red");
        }

        $(this).parent().parent().parent().parent().attr("data-src-complete", 0);
       
       })
        _v++;
      })
     
  });

  //compare 2 arrays function
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



  let cb = $("form .checkboxes");
  $.each(cb, function(el, val){
    let v = $(val).find("input");
    //console.log( "---->" , $(val).val());
    
     $.each(v, function(v , val){
     
       $(val).attr("name", "cb" + el);
       
       $(val).attr("id", "cbid" +  _v);
       
       //get the labels and add the "for" element
       $(val).parent().find("label").attr("for", "cbid" + _v );
       $(val).on("change", function(){

        let items = $(val).parent().parent().attr("data-src-selected");
        let parsed = JSON.parse(items);

        if($(this).is(":checked") == true){
          $(val).parent().addClass("selected");
          parsed.push($(val).val());
          $(val).parent().parent().attr("data-src-selected", JSON.stringify(parsed));

          
          if(parsed.length > 0){
            $(this).parent().parent().parent().parent().attr("data-src-complete", 0);
            $(this).parent().parent().parent().find(".error").text("");
            $(this).parent().parent().parent().find(".error").removeClass("red");
          }
         
        }
        if($(this).is(":checked") == false){
          $(val).parent().removeClass("selected");
          let value = $(val).val();
          const i = parsed.indexOf(value);

          if(i > -1){
            parsed.splice(i, 1);
          }

          $(val).parent().parent().attr("data-src-selected", JSON.stringify(parsed));

          if(parsed.length < 1){
            $(this).parent().parent().parent().parent().attr("data-src-complete", 1);
            $(this).parent().parent().parent().find(".error").text("this field is required");
            $(this).parent().parent().parent().find(".error").addClass("red");
          }

         
        }

        let _answers = JSON.parse($(val).parent().parent().attr("data-src-items"));
        let _selected =  JSON.parse($(val).parent().parent().attr("data-src-selected"));
      
        let answers = [];
        let selected = [];
        $.each(_answers, function(el, val){
          let _v = val.replace(/ /g,'')
          answers.push(_v.toLowerCase());
        })

        $.each(_selected, function(el, val){
          let _v = val.replace(/ /g,'')
          selected.push(_v.toLowerCase());
        });


        //console.log(answers, selected)
        let _c = $(val).parent().parent().attr("data-src-points");
        let r = parseFloat(_c);
        let w = 0;
        
        if(selected.sort().compare(answers.sort())) {
          //console.log("array match");
          $(val).parent().parent().attr("data-src-current-score", r);
        } else {
          //console.log("array DOESNT match");
          $(val).parent().parent().attr("data-src-current-score", w);
        }

        })
        _v++;
      })
     
  });

  //add the correct names to the textareas
  let ta = $("form textarea");
    $.each(ta, function(el, val){
      $(val).attr("name", "ta" + (el + 1));
      $(val).attr("data-src-current-score", 0);
      
      $(val).on("keyup", function(){
        
        if($(this).val() == ""){
          let p = $(this).parent().parent().parent()
          substractFromProgressBar(p);
          //$(this).parent().parent().find(".error").text("this field is required");
          $(this).parent().parent().find(".error").addClass("red");
          $(this).parent().parent().parent().attr("data-src-complete", 1);
        }else{
          $(this).parent().parent().find(".error").text("");
          $(this).parent().parent().find(".error").removeClass("red");
          if($(this).val().toLowerCase() == $(this).attr("data-src-value")){
            $(this).attr("data-src-current-score", parseInt($(this).attr("data-src-score")));
            
          }else{
            $(this).attr("data-src-current-score", parseInt($(this).attr("data-src-score-wrong")));
            
          }
          $(this).parent().parent().parent().attr("data-src-complete", 0);
        
      }
      
      });
      
      $(val).on("change", function(){
        //setSectionStorage($(val).parent().parent(), $(val).attr("name"), $(val).val());
        //console.log("field has changed!!!");
      });

  });


let equis = $(".equi");
  
    $.each(equis, function(el,val){
    $(val).on("mouseleave", function(){
      if($(val).attr("data-src-selected-items") == undefined || $(val).attr("data-src-selected-items")== "[]"){
        $(val).find(".error").text("This panorama is empty");
        $(val).find(".error").addClass("red");
      }else{
        console.log($(val).attr("data-src-items"));

        let _answers = JSON.parse($(val).attr("data-src-items"));
        let _selected = JSON.parse($(val).attr("data-src-selected-items"));
          
        let answers = [];
        let selected = [];
        $.each(_answers, function(el, val){
          let _v = val.replace(/ /g,'')
          answers.push(_v.toLowerCase());
        })

        $.each(_selected, function(el, val){
          let _v = val.replace(/ /g,'')
          selected.push(_v.toLowerCase());
        })
        //console.log(answers, selected)
        let _c = $(this).find(".inner-equi");
        let r = parseFloat($(_c).attr("data-src-score"));
        let w = 0;


        if(selected.sort().compare(answers.sort())) {
          //console.log("array match");
          $(val).attr("data-src-current-score", r);
        } else {
          //console.log("array DOESNT match");
          $(val).attr("data-src-current-score", 0);
        }
      }
    });
  });
  
  
  if(localStorage.getItem("score")){
    ls = parseInt(localStorage.getItem("score"));
    console.log("local storage --->", ls)
  }else{
    ls = 0;
    //console.log("NO local storage --->", ls)
  }
  
  $.each(sections, function(el, val){
    if($(val).hasClass("hide")){
      //console.log("skipping section: ", el);
    }else{
      _sections.push(val);
     
    }
    
  });
  let index= 1;
  //add the functionality to the rest of the sections that are renderd
 
  $.each(_sections, function(el, val){

        //hide the rest of the sections, not the first one and we have more than one
        if(el > 0){
          $(val).addClass("hidden"); 
        }

        //check if we can show or not the questions_bar at the start
        if(el == 0){
          $(val).addClass("current_question");
          //enable in order to hide the bar again
          /*
          if($(val).hasClass("contentOnly")){
            $("#questions_bar").css({"opacity": 0});
          }else{
            $("#questions_bar").css({"opacity": 1});
            $("#current_question").text("1");
          }
          */
        }

        if($(val).hasClass("contentOnly")){
          //skip the count
        }else{
          //add the index of the question to the current section
          $(val).attr("data-src-index", index++);
        }

        //add id to the current section
        $(val).attr("id", el + 1);
        
        $(val).attr("data-src-section-total", 0);
        //find all the children inside the section
        let children = $(val).find("div.question");
        let childrenLength = children.length;
        $(val).attr("data-src-complete", childrenLength);
        
        $.each(children, function(i, child){
        /*
         if($(child).hasClass("texts")){
           $(child).on("change", function(){
            let name = $(child).find("input[type='text']").attr("name");

              if($(child).find("input[type='text']").val() == ""){
                addToSection(val, name, childrenLength); 
              }else{
                removeFromSection(val, name, childrenLength);
              }
           });
         }
         
         if($(child).hasClass("textareas")){
          $(child).on("change", function(){
             let name = $(child).find("textarea").attr("name");
              
              if($(child).find("textarea").val() == ""){
                addToSection(val, name, childrenLength); 
              }else{
                removeFromSection(val, name, childrenLength);
              }
           });
         }
         if($(child).hasClass("selects")){
          $(child).on("change", function(){
           
            let name = $(child).find("select").attr("name");
           
            if($(child).find("select").val() == ""){
                addToSection(val, name, childrenLength); 
              }else{
                removeFromSection(val, name, childrenLength);
              }
           
           });
           
         }
         if($(child).hasClass("radios")){
           
            $(child).on("change", function(){
              let name = $(child).find("input[type='radio']").attr("name");
              if($(child).find("input[type='radio']:checked")){
                removeFromSection(val, name, childrenLength);
              }else{
                addToSection(val, name, childrenLength); 
              }
            
            });
           
         }
         */
     
      });
   
        
     $(".next").css({"display": "block"})

  });


$("input[type='radio']").on("click", function(){
  
  let f =  $(this).parent().parent().find(".radio-wrapper");
  $.each(f, function(i, val){
   $(val).removeClass("selected");
  })
  
  $(this).parent().addClass("selected");
});


$(".prev").on("click", async function(e){
  e.preventDefault();
  let current = $(".current_question");
  let id = $(current).attr("id");
  let prevDiv = $(current).prev("div.questions");
  let prevId = $(prevDiv).attr("id")
  
  $("#" + id).addClass("hidden");
  $("#" + id).removeClass("current_question");
  $("#" + prevId).removeClass("off");
  $("#" + prevId).addClass("current_question");

  if(prevId == 1){
    $(".prev").css({"display": "none"})
  }
  //enable in order to hide the bar again
/*
  if($("#" + id).prev("div").hasClass("contentOnly")){
    $("#questions_bar").css({"opacity": 0})
  }else{
    $("#questions_bar").css({"opacity": 1})
  }
*/
  if($("#" + id).prev("div.questions")){
      current_index =$("#" + id).prev("div").attr("data-src-index");
      $("#current_question").text(current_index);
  }else{
    console.log("no prev div");
  }

});


$(".next").on("click", async function(e){
  e.preventDefault();
  
  
    
  let current = $(".current_question");
  let id = $(current).attr("id");
  let nextDiv = $(current).next("div.questions");
  let nextId = $(nextDiv).attr("id")
  
  let iframe = $(current).contents().find('iframe');
 

  if(nextId > 1){
    $(".prev").css({"display": "block"})
  }
  
  if($(".current_question").hasClass("contentOnly")){
    getNextSectionClass(id, nextId);
    $(iframe).each(function() { 
        var src= $(this).attr('src');
        $(this).attr('src',src);  
        });
    
  }else{
    let ss = $(".questions").not(".hide").not(".hidden");
    let arr = [];
    let currentLastId = "";
  
    $.each(questions, function(el, val){
     
        if($(val).hasClass("hide") || $(val).hasClass("hidden")){
          //console.log("skip")
        }else{
          //console.log(val);
          arr.push(val);
        }
    })

    let s = [];
    
    $.each(arr, async function(el, val){
      if( $(val).attr("data-src-complete") && $(val).attr("data-src-complete") == 0){
        //console.log($(val).attr("data-src-complete"));
      }else{
        //console.log("--->", "section NOT completed");
        if($(val).hasClass("contentOnly")){
          
        }else{
          s.push($(val));
        }
      
        
      }

    });
 
    if(s.length > 0){
      //alert("Please fill all the required fields")
      Swal.fire({
        icon: 'error',
        title: 'Required field',
        text: 'Please fill the field to continue',
      });

      let isDone = checkValues(ss);
     
      if(id == 1){
        $(".prev").css({"display": "none"})
      }
      //console.log(isDone);
    }else{
      let curr_index = $("#"+ id).attr("data-src-index");
      if(curr_index == question_sections.length){
        let c = submitFormValues(ss);
        if(c){

        let g= 100 / question_sections.length;
        let rounded = g.toFixed(16);
        let currentProgress =  $("#progress_bar").attr("value");
        let sum = parseFloat(currentProgress) + parseFloat(rounded);
       
        $("#progress_bar").attr("value", sum );
        $(current).attr("data-src-was-done", true);
        sections_arr.push($(current).attr("id"));



          //make the score check and the end the course
          $.each(ss, function(el, val){
            let _s = JSON.parse($(val).attr("data-src-section-total"));
            total_score = total_score + _s.reduce((a, b) => a + b, 0);
            //console.log(total_score);
          });

            //console.log(total_score);
           fixed = total_score.toFixed(2);
           total_score = fixed;
           if(total_score == 99.99){
             total_score = 100;
           }
          
           localStorage.setItem("score", total_score);
            window.parent.RecordTest(total_score);
            
            //set the course/quiz as finished
            console.log("finishing course...");
            localStorage.removeItem("score");
            window.parent.markAsFinishedAndCompleted();
            window.parent.doExit();
            window.parent.doUnload(true);

        }

      }else{
        getSectionScore(ss);
        getNextSectionClass(id, nextId);
        updateProgressBar(current);
        
        $(iframe).each(function() { 
        var src= $(this).attr('src');
        $(this).attr('src',src);  
        });
        
        $(current).attr("data-src-was-done", true);
        sections_arr.push($(current).attr("id"));
      }
      
    }
  
  }

});


function substractFromProgressBar(parent){
    //get the current question and see 
    //if it was done before
    if($(parent).attr("data-src-was-done")){
      let g= 100 / question_sections.length;
      let rounded = g.toFixed(16);
      let currentProgress =  $("#progress_bar").attr("value");
      let substract = parseFloat(currentProgress) - parseFloat(rounded);
      $("#progress_bar").attr("value", substract);
      $(parent).removeAttr("data-src-was-done");
    }else{
      
    }
    
}

function updateProgressBar(current){

    if($(current).attr("data-src-was-done")){
      //console.log("was done!!");
    }else{
      let g= 100 / question_sections.length;
      let rounded = g.toFixed(16);
      let currentProgress =  $("#progress_bar").attr("value");
      let sum = parseFloat(currentProgress) + parseFloat(rounded);
      $("#progress_bar").attr("value", sum );
    }
    
  
}

function getNextSectionClass(currentId , nextS){
  
  $("#"+ currentId).addClass("off");
  $("#" + currentId).removeClass("current_question");
  //enable in order to hide the bar again
  /*
  if($("#" + nextS).hasClass("contentOnly")){
      $("#questions_bar").css({"opacity": 0})
  }else{
      $("#questions_bar").css({"opacity": 1})
   
  }
  */

  if($("#"+ currentId).attr("data-src-index")){
    current_index =  parseInt($("#"+ currentId).attr("data-src-index")) + 1;; 
  }else{
    if(current_index == null){
      current_index = 1;
    }
  }

  $("#current_question").text(current_index);
  $("#" + nextS).removeClass("hidden");
  $("#" + nextS).addClass("current_question");
}
/*
function removeFromSection(val, name, childrenLength){
  if(done_array.includes(name)){
    //console.log(name + " has already been added");
  }else{
    console.log($(val));
    $(val).attr("data-src-complete", parseInt($(val).attr("data-src-complete")) - 1);
    done_array.push(name);
    
  }
  //console.log(done_array);
  
}

function addToSection(val, name, childrenLength){
 
  done_array.splice(done_array.indexOf(name), 1);
  if($(val).attr("data-src-complete") < childrenLength){
      $(val).attr("data-src-complete", parseInt($(val).attr("data-src-complete")) + 1 );
      

      if(sections_arr.includes($(val).attr("id"))){
        //console.log("includes")
        sections_arr.splice(sections_arr.indexOf($(val).attr("id")), 1);
        let total = $(val).attr("data-src-complete");
        let int_total = parseInt(total);
        let g= 100 / question_sections.length;
        let rounded = g.toFixed(16);
        let currentProgress =  $("#progress_bar").attr("value");

        let substract = parseFloat(currentProgress) - parseFloat(rounded);
        $("#progress_bar").attr("value", substract);
        
      }else{
        //console.log("doesnt include");
       
      }

  }
   
}
*/

function getSectionScore(ss){
  
  //console.log("trigger function")
  let currentLastId = "";
  $.each(ss, function(el, val){
    //console.log(ss);
    let texts = $(val).find(".texts");
    let selects = $(val).find(".selects");
    let textareas = $(val).find(".textareas");
    let radios = $(val).find(".radios");
    let equis = $(val).find(".equi");
    let boxes = $(val).find(".boxes");
    let checkboxes = $(val).find(".checkboxes");
    //let panellums = $(val).find(".pnlm-container");
    //let sectionScore = 0;
    let r = [];

    $.each(texts, function(el, val){
      let t =$(val).find("input[type='text']");
     
      r.push(parseFloat($(t).attr("data-src-current-score")));
    });


    $.each(selects, function(el, val){
      let _s = $(val).find("div");
      let s =$(_s).find("select");
     
      r.push(parseFloat($(s).attr("data-src-current-score")));
    });

    $.each(textareas, function(el, val){
      let ta =$(val).find("textarea");
      
      r.push(parseFloat($(ta).attr("data-src-current-score")));
      
    });

    $.each(radios, function(el, val){
     let ra = $(val).find("div.radios-wrapper");
     r.push(parseFloat($(ra).attr("data-src-current-score")));
    });


    $.each(equis, function(el, val){
      r.push(parseFloat($(equis).attr("data-src-current-score")));
    })

    $.each(boxes, function(el, val){
      r.push(parseFloat($(val).attr("data-src-current-score")));
    })


    $.each(checkboxes, function(el, val){
      let cbw = $(val).find("div.checkboxes-wrapper");
      r.push(parseFloat($(cbw).attr("data-src-current-score")));
    })
    //console.log("--->", r);
    $(val).attr("data-src-section-total", JSON.stringify(r));
    if($(val).attr("id") == ss.length){
      currentLastId = $(val).attr("id");
      
    };
    
  });
  return currentLastId;
  
}  


function submitFormValues(ss){
  
  let checkingDone = false;
  $.each(ss, function(el, val){
    //console.log(ss);
    let texts = $(val).find(".texts");
    let selects = $(val).find(".selects");
    let textareas = $(val).find(".textareas");
    let radios = $(val).find(".radios");
    let equis = $(val).find(".equi");
    let boxes = $(val).find(".boxes");
    let checkboxes = $(val).find(".checkboxes");
    //let panellums = $(val).find(".pnlm-container");
    //let sectionScore = 0;
    let r = [];

    $.each(texts, function(el, val){
      let t =$(val).find("input[type='text']");
     
      r.push(parseFloat($(t).attr("data-src-current-score")));
    });


    $.each(selects, function(el, val){
      let s =$(val).find("select");
     
      r.push(parseFloat($(s).attr("data-src-current-score")));
    });

    $.each(textareas, function(el, val){
      let ta =$(val).find("textarea");
      
      r.push(parseFloat($(ta).attr("data-src-current-score")));
      
    });

    $.each(radios, function(el, val){
     let ra = $(val).find("div.radios-wrapper");
     //console.log(ra);
     r.push(parseFloat($(ra).attr("data-src-current-score")));
    });
    
    $.each(equis, function(el, val){
     
      r.push(parseFloat($(val).attr("data-src-current-score")));
    })

    $.each(boxes, function(el, val){
     r.push(parseFloat($(val).attr("data-src-current-score")));
    })
    

    $.each(checkboxes, function(el, val){
      let cbw = $(val).find("div.checkboxes-wrapper");
      r.push(parseFloat($(cbw).attr("data-src-current-score")));
    })
    //console.log("--->", r);
    $(val).attr("data-src-section-total", JSON.stringify(r));
    
    if(ss.length === (el + 1)){
      checkingDone = true;
      
    };


  });
  return checkingDone;
  
}


function checkValues(ss){
  
  let checkingDone = false;

  $.each(ss, function(el, val){
    
    let texts = $(val).find(".texts");
    let selects = $(val).find(".selects");
    let textareas = $(val).find(".textareas");
    let radios = $(val).find(".radios");
    //let panellums = $(val).find(".pnlm-container");
    let boxes = $(val).find(".boxes")
    let sectionScore = 0;
    
    $.each(texts, function(el, val){
      
      let t =$(val).find("input[type='text']");
      if($(t).val() == ""){
        $(val).find(".error").text("this field is required");
        $(val).find(".error").addClass("red");
      }else{
        $(val).find(".error").text("");
        $(val).find(".error").removeClass("red");
       
      }
    });


    $.each(selects, function(el, val){
     
      let _s =$(val).find("div");
      let s = $(_s).find("select");
      if($(s).val() == ""){
       $(val).find(".error").text("this field is required");
       $(val).find(".error").addClass("red");
      }else{
        $(val).find(".error").text("");
        $(val).find(".error").removeClass("red");
      }

    });

    $.each(textareas, function(el, val){
      let ta =$(val).find("textarea");
      
      if($(ta).val() == ""){
        $(val).find(".error").text("this field is required");
        $(val).find(".error").addClass("red");
      }else{
        $(val).find(".error").text("");
        $(val).find(".error").removeClass("red");
      }
      
    });

    $.each(radios, function(el, val){
      let t =$(val).find(".radios-wrapper > div");
      let r = $(val).find("input[type='radio']");
      let n = $(r).attr("name");
     
      if($('input[name="' + n + '"]:checked').val() == undefined){
        $(val).find(".error").text("this field is required");
        $(val).find(".error").addClass("red");
      }else{
        $(val).find(".error").text("");
        $(val).find(".error").removeClass("red");
      }
      
      
       
    });

    $.each(boxes, function(el, val){
      if($(val).attr("data-src-selected") == "[]"){
        $(val).parent().find(".error").text("This drag and drop is empty");
        $(val).parent().find(".error").addClass("red");
      }
    });

/*
    $.each(panellums, function(el, val){
      
      if($(val).attr("data-src-items-selected") == undefined){
        $(val).next(".error").text("This panorama is empty");
        $(val).next(".error").addClass("red");
      }else{
        if($(val).attr("data-src-items-selected")== "[]"){
          $(val).next(".error").text("This panorama is empty");
          $(val).next(".error").addClass("red");
        }else{
          $(val).next(".error").text("");
          $(val).next(".error").removeClass("red");
        } 
      }
    
    })
*/
    if(ss.length === (el + 1)){
      checkingDone = true;
      
    };
   
  });
  return checkingDone;
  
}


//$(".buttons-container").css({"top": ($("body").height() - 50) + "px"})

});





</script>
</body>
</html>


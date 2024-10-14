<template>

 <div class="dragndrop-container">
  <p><strong>Select an image to preview it.</strong></p>
  <p><em>In order to edit the name of a rectangle, double click it and go down to the edit section.</em></p>
  
  <div>
    <input id="image" type="file" name="file" @change="(e) => onImageSelected(e)"  />
  </div>
  <div v-if="hasChildren" class="export">
    <button id="exportRects" @click='exportRectangles()'>Export Rectangles</button>
  </div>
  <div id="preview">
    <img draggable="false" src="#" id='previewImage' @click="createRectangles()" @mousemove="(e) => setMouseCoords(e)" />    

  </div> 
  
  <div class="edit">
    <p>Type the name for the specific rectangle, and click "change name" to change it</p>
    <input type="text" id="rectName" />
    <input type="hidden" id="hiddenSelected" />
    <button id="changeName" @click="onNameChanged()">Change Name</button>
    <hr>
    <button id="deleteRect" @click="deleteRectangle()">Delete Selected Rectangle</button>

  </div>
 </div>
</template>

<script>
 import { uuid } from 'vue-uuid';

export default {
  data(){
    return {
      mouse:{
        x: 0,
        y: 0,
        startX: 0,
        startY: 0
      },
      id: null, 
      element: null,
      hasChildren: false
    }
  },
  methods:{
      onImageSelected(e){
        var files = e.target.files || e.dataTransfer.files;
        if(files.length > 0){
          //console.log(files[0]);
          let reader = new FileReader();

          reader.onload = (e) => {
            document.querySelector("#previewImage").setAttribute("src", e.target.result);
            setTimeout(function(){
              
              //console.log(document.querySelector("#previewImage").parentElement.parentElement.offsetWidth);
              let pw = document.querySelector("#previewImage").parentElement.parentElement.offsetWidth;
              let nw = document.querySelector("#previewImage").offsetWidth / pw * 100
              document.querySelector("#preview").style.width = nw + "%";
              
              /*
              document.querySelector("#preview").style.width =  document.querySelector("#previewImage").offsetWidth + "px";
              document.querySelector("#preview").style.height =  document.querySelector("#previewImage").offsetHeight + "px";
              document.querySelector("#preview").style.visibility = "visible";
              */
             //document.querySelector("#preview").style.width = document.querySelector("#previewImage").offsetWidth + "px";
             document.querySelector("#preview").style.visibility = "visible";
            }, 400);
          
          }

          reader.readAsDataURL(files[0]);
        
        }else{
          alert("No image selected");
          //document.querySelector("#previewImage").setAttribute("src", "#");
        }
      
      

      },
      createRectangles(){
        //console.log("-------->", this.element)
        if (this.element !== null) {
            this.element = null;
             document.querySelector("#preview").style.cursor = "default";
              document.getElementById(this.id).style.pointerEvents = "auto";
             
               document.getElementById(this.id).addEventListener("dblclick", function(e){
                //console.log(e.target.getAttribute("id"));
                //get the input and fill it
                document.querySelector("#rectName").value = e.target.getAttribute("data-src-id");
                document.querySelector("#rectName").setAttribute("data-src-selected", e.target.getAttribute("id"));
                document.querySelector("#hiddenSelected").value = e.target.getAttribute("id");         
                document.querySelector(".edit").style.display = "block";
                //remove the class selected for any other .rectangle
                let rectangles = document.querySelectorAll(".rectangle");
                
                for(let i = 0; i < rectangles.length; i++){
                  rectangles[i].classList.remove("selected");
                }
               
                e.target.classList.add("selected");
            })
             this.hasChildren = true;
             //console.log("--->", this.hasChildren);
             console.log("finsihed.");
        } else {
            console.log("begun.");
            
            this.mouse.startX = this.mouse.x;
            this.mouse.startY = this.mouse.y;
            this.element = document.createElement('div');
            this.element.className = 'rectangle'
            this.id = uuid.v1();
            this.element.setAttribute("id", this.id);
            this.element.setAttribute("data-src-id", this.id);
            this.element.style.left = this.mouse.x + 'px';
            this.element.style.top = this.mouse.y + 'px';

            //console.log(this.mouse.x, this.mouse.y);
            
            document.querySelector("#preview").appendChild(this.element)
           
            document.querySelector("#preview").style.cursor = "crosshair";
        }
        
      },
      exportRectangles(){
        console.log("rectangles will be exported here as json data")
        //get all the rectangles
        let rectangles = document.querySelectorAll(".rectangle");
        let rectanglesArr = [];
        if(rectangles.length < 1){
           alert("There are no rectangles to export, try adding a few onto an image"); 
        }else{
          for(let i = 0; i < rectangles.length; i++){
            let item = {
              id: rectangles[i].getAttribute("data-src-id"),
              left: rectangles[i].style.left,
              top: rectangles[i].style.top,
              width: rectangles[i].style.width,
              height: rectangles[i].style.height
            }
            rectanglesArr.push(item);
          }
        } 


/*
        let formData = new FormData();
        formData.append("rectangles", JSON.stringify(rectanglesArr));
*/
        let filename = "coords.txt";
         var file = new Blob([JSON.stringify(rectanglesArr)], {type: "text/plain"});
        if (window.navigator.msSaveOrOpenBlob) // IE10+
            window.navigator.msSaveOrOpenBlob(file, filename);
        else { // Others
            var a = document.createElement("a"),
                    url = URL.createObjectURL(file);
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(function() {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);  
        }, 0); 
    }
         /*
        fetch("http://kirby-alpha.test/dragndrop", {
            method: "POST",
            body: formData,
          })
         .then((res) => {
            //return res.blob();
          return res;
          })
          .then((blob) => {
            console.log(blob);
           
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = "replaced_static.zip";
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
           
          })
          .catch((err) => {
            console.log(err);
          });
*/

      },
      setMouseCoords(e){

       let vX = e.clientX;
       let vY = e.clientY;
       //get the dimensions of the container
       let boxRect = e.target.getBoundingClientRect(); 

       let localX = (vX-boxRect.left);
       let localY = (vY-boxRect.top);

       this.mouse.x = localX;
       this.mouse.y = localY;

      if (this.element !== null) {
        
        //initial % would be total width of img / X position of the top left box
        let iw = document.querySelector("#preview").offsetWidth;
        let ih = document.querySelector("#preview").offsetHeight;
        //console.log(iw, ih);
        //initial % would be the total height of img / Y position of the top left box
        
        //you'd get the x and y of the top left corner, and the x,y of the bottom right corner of the box,
        // subtract them and then get the percentage of the image like the first example
        //offsetTop + height gets you bottom
        //offsetWidth + element width gets you right of the box
        
        let w = (Math.abs(this.mouse.x - this.mouse.startX) / iw * 100 );
        let h = (Math.abs(this.mouse.y - this.mouse.startY) / ih * 100);
        //dimensions
        this.element.style.width = w + '%';
        this.element.style.height = h + '%';

        let l = (this.mouse.x - this.mouse.startX < 0) ? this.mouse.x / iw * 100 : this.mouse.startX / iw * 100;
        let t = (this.mouse.y - this.mouse.startY < 0) ? this.mouse.y / ih * 100 : this.mouse.startY / ih * 100;
        //position     
        this.element.style.left = l + "%";
        this.element.style.top = t + "%";
        
        
        /*
        this.element.style.width = Math.abs(this.mouse.x - this.mouse.startX) + 'px';
        this.element.style.height = Math.abs(this.mouse.y - this.mouse.startY) + 'px';
        this.element.style.left = (this.mouse.x - this.mouse.startX < 0) ? this.mouse.x + 'px' : this.mouse.startX + 'px';
        this.element.style.top = (this.mouse.y - this.mouse.startY < 0) ? this.mouse.y + 'px' : this.mouse.startY + 'px';
        */



       }

      },
      onNameChanged(){
        //set the name to the data-src-id of the selected element
        let selectedId = document.querySelector("#rectName").getAttribute("data-src-selected");
        document.getElementById(selectedId).setAttribute("data-src-id", document.querySelector("#rectName").value);
      },
      deleteRectangle(){
        //get the current id on the hidden input
        let elemId = document.querySelector("#hiddenSelected").value;  
        let toDelete = document.getElementById(elemId);
        toDelete.remove();
        document.querySelector(".edit").style.display = "none";

        //check the length of the rectangles and hide the export button if that us 0
        let rectangles = document.querySelectorAll(".rectangle");
        if (rectangles.length < 1){
          this.hasChildren = false;
        }

      }

  }, 
  mounted(){
    
  }
}
</script>

<style lang="css">
.dragndrop-container{
  background: #fff;
  padding: 1em;
}
#previewImage {
  max-width: 100%;
}
#preview {
  position: relative;
  visibility: hidden;
  /*
  height: 0px;
  width: 0px;
  */
  width: auto;
  height: auto;
}
.rectangle {
    border: 1px solid #FF0000;
    position: absolute;
    pointer-events: none;
}
.edit {
  display:none; 
  padding-top: 1em;
  -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
#changeName, #deleteRect, #exportRects {
  padding: 0.5em;
  font-size: 0.9em;
  
}
#rectName {
  margin-top: 0.6em;
}
#changeName {
  margin:1em 0em;
  background: #ccc;
  
}
#exportRects{
  background: #6cd887;
  color: #fff;
}
#deleteRect {
  color: red;
}
.selected {
  background: rgba(255, 0, 0, 0.5);
}
</style>

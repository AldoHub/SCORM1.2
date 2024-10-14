<template>
 <div class="container">
  <form>
    <p>Fill the fields to edit the default values</p>
    <div class="form-field">
      <label for="title">Course Title</label>
      <input v-model="title" placeholder="Shell Course Title" type="text" name="title" />   
    </div>
    <div class="form-field">
      <label for="id">Course ID</label>
      <input v-model="id" type="text" placeholder="org.shell.course.title" name="id"  />   
    </div>
     <div class="form-field">
      <label>Organization</label>
      <input v-model="organization" name="organization" placeholder="shell_organization" type="text" />   
    </div>
      <div class="form-field">
      <label>Passing Score</label>
      <input v-model="score" name="score" placeholder="70" type="number" />   
    </div>
    <!--
    <div class="form-field">
      <label>Resource ID</label>
      <input v-model="resourceID" name="resourceID" type="text" />   
    </div>
     <div class="form-field">
      <label for="itemID">Item ID</label>
      <input v-model="itemID" type="text" name="itemID" />   
    </div>
    -->
     <div class="form-field-submit">
       <button class="button" v-on:click.prevent="generateZip" type="submit">Generate Package</button>   
    </div>
  </form>
 </div>
</template>

<script>

export default {
  
  data(){
    return {
      title: null,
      id: null,
      organization: null,
      score: 70
    }
  },
  methods:{
      handleFile(e){
        
      },  
      generateZip(){
        alert("DOING STUFF!!! WAIT!!!");
        let current_loc = window.location.href;
        let split_loc = current_loc.split("/").pop();
        
        //call the custom route
        //and download the file that it sends back
        const formData = new FormData();
        //append the url location
        formData.append("url", split_loc);

        if(this.title == null){
         formData.append("title", "Shell Course")
        }else{
          formData.append("title", this.title);
        }

        
        if(this.id == null){
          formData.append("id", "org.shell.course");
        }else{
          formData.append("id", this.id);
        }
        
        if(this.organization == null){
          formData.append("organization", "shell_organization")
        }else{
          formData.append("organization", this.organization);
        }
       
       if(this.score == null){
          formData.append("score", 70)
        }else{
          formData.append("score", this.score);
        }
       
        let href = location.href.split("panel");
        let _url = href[0];
        
        fetch(`${_url}generate-zip`, {
            method: "POST",
            body: formData,
          })
         .then((res) => {
            return res.blob();
           
          })
          .then((blob) => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download =  split_loc + ".zip";
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
           
          })
          .catch((err) => {
            console.log(err);
          });

    },
      
  }
}
</script>

<style lang="css">

form > p {
  font-weight: bold;
  margin-bottom: 1em;
}

.container {
  padding: 1em;
  background:#fff;
 
}


.form-field {
  margin-bottom: 0.5em;
}

.form-field label {
  display:block;
  margin-bottom: 0.2em;
  font-weight: 600;
}


input {
  padding: 0.2em;
  display:block;
  padding: 0.4em;
}

.text{
  margin-bottom: 1em;
}

.button {
  margin-top: 1em;
  padding: 0.7em;
  background: #6cd887;
  color: white;
}


</style>

<template>
 <div class="container">
  <p class="text">SCORM zip</p>
   <form enctype='multipart/form-data'>
    <button class="button2" v-on:click.prevent="generateScorm" type="submit">Create Zip</button>
  </form>

 </div>
</template>

<script>

export default {
  
  data(){
    return {
      zipFile:"",
    }
  },
  methods:{
      generateScorm(){
        //call the custom route
        //and download the file that it sends back
        const formData = new FormData();
        fetch("http://plainkit-main-rebuild.test/generate-scorm", {
            method: "POST",
            body: formData,
          })
         .then((res) => {
            console.log(res);
          })
          .catch((err) => {
            console.log(err);
          });

    },
      
  }
}
</script>

<style lang="css">

.container {
  padding: 1em;
  background:#fff;
}

.text{
  margin-bottom: 1em;
}

.button2 {
  padding: 0.7em;
  background: rgb(255, 0, 0);
  color: white;
}


</style>

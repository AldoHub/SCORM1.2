<style>

</style>

<?php 
#print_r($block->Image()->toFile()->url());
#print_r($block->width())
?>

<?php if($block->panorama()->isNotEmpty()):?>

   
    <div id="<?= $block->panorama()->html(); ?>" 
    <?php if($block->width()->isNotEmpty()): ?>
    style="width:100%; height:100vh"
    <?php else: ?>
    style="width:500px; height:300px"
    <?php endif; ?>
    >
    <!--place all info inside this div, in order to be visible on fullscreen-->
    </div>
    <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
    <script src="../shared/contentfunctions.js"></script>
    <script>
    viewer = pannellum.viewer('<?= $block->panorama()->html(); ?>', {
    "type": "equirectangular",
    "autoLoad": true,
    "hfov": 120,
    "panorama": "<?= $block->Image()->toFile()->url() ?>",
    "hotSpots": [
        {
            "pitch": 14.1,
            "yaw": 1.5,
            "cssClass": "SkyTwo",
            "createTooltipFunc": hotspot,
            "createTooltipArgs": "SkyTwo"
        },
         {
            "pitch": 10.1,
            "yaw": 200.5,
            "cssClass": "TreeTwo",
            "createTooltipFunc": hotspot,
            "createTooltipArgs": "TreeTwo"
        },
        {
            "pitch": -9.4,
            "yaw": 222.6,
            "cssClass": "red",
            "createTooltipFunc": hotspot,
            "createTooltipArgs": "red"
        },
        {
            "pitch": -0.9,
            "yaw": 144.4,
            "cssClass": "red",
            "createTooltipFunc": hotspot,
            "createTooltipArgs": "Red"
        }
    ]
    
});

// Hot spot creation function
function hotspot(hotSpotDiv, args) {
    hotSpotDiv.classList.add('custom-tooltip');
    var span = document.createElement('span');
    span.innerHTML = args;
    hotSpotDiv.appendChild(span);
    span.style.width = span.scrollWidth - 20 + 'px';
    span.style.marginLeft = (span.scrollWidth - hotSpotDiv.offsetWidth) / 2 + 'px';
    span.style.marginTop = span.scrollHeight - 12 + 'px';
}
    </script>
<?php endif; ?>




<script>
/*
// Create viewer
viewer = pannellum.viewer('<?= $block->panorama()->html(); ?>', {
    "type": "equirectangular",
    "autoLoad": true,
    "hfov": 120,
    "panorama": "https://images.unsplash.com/photo-1557971370-e7298ee473fb?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1900&q=80",
    "hotSpots": [
        {
            "pitch": 14.1,
            "yaw": 1.5,
            "cssClass": "SkyTwo",
            "createTooltipFunc": hotspot,
            "createTooltipArgs": "SkyTwo"
        },
         {
            "pitch": 10.1,
            "yaw": 200.5,
            "cssClass": "TreeTwo",
            "createTooltipFunc": hotspot,
            "createTooltipArgs": "TreeTwo"
        },
        {
            "pitch": -9.4,
            "yaw": 222.6,
            "cssClass": "red",
            "createTooltipFunc": hotspot,
            "createTooltipArgs": "red"
        },
        {
            "pitch": -0.9,
            "yaw": 144.4,
            "cssClass": "red",
            "createTooltipFunc": hotspot,
            "createTooltipArgs": "Red"
        }
    ]
    
});

// Hot spot creation function
function hotspot(hotSpotDiv, args) {
    hotSpotDiv.classList.add('custom-tooltip');
    var span = document.createElement('span');
    span.innerHTML = args;
    hotSpotDiv.appendChild(span);
    span.style.width = span.scrollWidth - 20 + 'px';
    span.style.marginLeft = (span.scrollWidth - hotSpotDiv.offsetWidth) / 2 + 'px';
    span.style.marginTop = span.scrollHeight - 12 + 'px';
}

let currentScore = 0;
let counter = 2;

viewer.on("load", () => {

    if(!localStorage.getItem("score")){
        localStorage.setItem("score", 0);
    }else{
        currentScore = localStorage.getItem("score");
    }
    

    $(".SkyTwo").on("click", function(){
        let itemValue = 18;
        calcScoreValue(itemValue);   
            
    });

    $(".TreeTwo").on("click", function(){
        let itemValue = 10;
        calcScoreValue(itemValue);
    });

});

function calcScoreValue(itemValue){
        //remove one to the counter
        counter--;
        currentScore = parseInt(currentScore) + parseInt(itemValue);
        console.log(currentScore);

        //save the results on local storage
        localStorage.setItem("score", currentScore);
        
        //record the changes in the score
        window.parent.RecordTest(currentScore);
        
        //remove the click event after the score setting
        $(this).unbind("click");
        //check how far we are on the counter 
        if(counter == 0){
            //move to the next page
            window.parent.doNext();
        }else{
            console.log("chances left: " + counter);
        }
    }
    */
/*
    $('body').on('click', '#panoramaTwo', function() {
        var inputText = "example hotspot text";
        var currentPitch = viewer.getPitch();
        var currentYaw = viewer.getYaw();
        viewer.addHotSpot({"pitch":currentPitch, "yaw":currentYaw, "type":"info", "text":inputText});
    });
    */
</script>
"use strict";

function PirateMode(){
    var clicks = 0;
    this.initialize = function(){
        //Get the data containing json array with the detected faces
        $.get( "../twickrtags/Model/API/RESTapi_typ.php",{getMyFaces:true}, function( data ) {
            //If you click the pirate button an eye patch and a hat will be added to the images where a face has been detected
            $("#pirateButton").on("click", function(){
                if(clicks === 0) {
                    makePirateOverlay(data);
                    clicks += 1;
                }
                else{
                    removePirateOverlay();
                    clicks -= 1;
                }
            });
        }, "json");
    };
    var makePirateOverlay = function(faceDetection){
        var containerDivs = document.getElementsByClassName("containerphoto");
        var i;
        var img;
        var newImg;
        var newImg1;
        for(i=0;i<containerDivs.length;i++){
            img = containerDivs[i].firstChild;
            if(img.getAttribute("id") === faceDetection[i].face_id && faceDetection[i].face_id !== "nothing"){
                //Add new image containing eye patch, has to be in the same div as original image to work
                newImg1 = document.createElement("img");
                newImg1.setAttribute("src", "../twickrtags/Images/Pirate_eye_patch.png");
                newImg1.setAttribute("class", "pirate");
                containerDivs[i].appendChild(newImg1);

                //Styling is set in this way only because it has to be set dynamically with face coordinates
                newImg1.style.width = faceDetection[i].w + "px";
                newImg1.style.position = "absolute";
                newImg1.style.top = (faceDetection[i].y - faceDetection[i].h * 0.1) + "px";
                newImg1.style.left = (faceDetection[i].x + 4) + "px";

                //Add new image containing hat, same procedure
                newImg = document.createElement("img");
                newImg.setAttribute("src", "../twickrtags/Images/Pirate_hat.png");
                newImg.setAttribute("class", "pirate");
                containerDivs[i].appendChild(newImg);
                newImg.style.width = (faceDetection[i].w * 2.2) + "px";
                newImg.style.position = "absolute";
                newImg.style.top = (faceDetection[i].y - faceDetection[i].h * 1.3) + "px";
                newImg.style.left = (faceDetection[i].x - faceDetection[i].w * 0.7) + "px";
            }
        }
    };
    var removePirateOverlay = function(){
        $(".pirate").remove();
    }
}

$(document).ready(function(){
    var pirateMode = new PirateMode();
    pirateMode.initialize();
});
"use strict";

function PirateMode(){
    var clicks = 0;
    this.initialize = function(){
        $.get( "RESTapi_typ.php",{getMyFaces:true}, function( data ) {
            $("#pirateButton").on("click", function(){
                if(clicks === 0) {
                    makePirateOverlay(data);
                    clicks += 1;
                    console.log(clicks);
                }
                else{
                    removePirateOverlay(data);
                    clicks -= 1;
                    console.log(clicks);
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
            if(img.getAttribute("id") === faceDetection[i].face_id){
                newImg1 = document.createElement("img");
                newImg1.setAttribute("src", "../Images/Pirate_eye_patch.png");
                newImg1.setAttribute("class", "pirate");
                containerDivs[i].appendChild(newImg1);
                newImg1.style.width = faceDetection[i].w + "px";
                newImg1.style.position = "absolute";
                newImg1.style.top = (faceDetection[i].y - faceDetection[i].h * 0.1) + "px";
                newImg1.style.left = (faceDetection[i].x + 4) + "px";

                newImg = document.createElement("img");
                newImg.setAttribute("src", "../Images/Pirate_hat.png");
                newImg.setAttribute("class", "pirate");
                containerDivs[i].appendChild(newImg);
                newImg.style.width = (faceDetection[i].w * 2.2) + "px";
                newImg.style.position = "absolute";
                newImg.style.top = (faceDetection[i].y - faceDetection[i].h * 1.3) + "px";
                newImg.style.left = (faceDetection[i].x - faceDetection[i].w * 0.7) + "px";
            }
        }
    };
    var removePirateOverlay = function(faceDetection){
        $(".pirate").remove();
        /*var containerDivs = document.getElementsByClassName("containerphoto");
        var i;
        var img;
        for(i=0;i<=containerDivs.length;i++){
            img = containerDivs[i].firstChild;
            if(img.getAttribute("id") === faceDetection[i].face_id){
                img.nextSibling.remove();
                img.nextSibling.remove();
                /!*newImg1 = document.createElement("img");
                newImg1.setAttribute("src", "../Images/Pirate_eye_patch.png");
                containerDivs[i].appendChild(newImg1);
                newImg1.style.width = faceDetection[i].w + "px";
                newImg1.style.position = "absolute";
                newImg1.style.top = (faceDetection[i].y - faceDetection[i].h * 0.1) + "px";
                newImg1.style.left = (faceDetection[i].x + 4) + "px";

                newImg = document.createElement("img");
                newImg.setAttribute("src", "../Images/Pirate_hat.png");
                containerDivs[i].appendChild(newImg);
                newImg.style.width = (faceDetection[i].w * 2.2) + "px";
                newImg.style.position = "absolute";
                newImg.style.top = (faceDetection[i].y - faceDetection[i].h * 1.3) + "px";
                newImg.style.left = (faceDetection[i].x - faceDetection[i].w * 0.7) + "px";*!/
            }
        }*/
    }
}

$(document).ready(function(){
    var pirateMode = new PirateMode();
    pirateMode.initialize();
});
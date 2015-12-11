"use strict";
// $(document).ready(function(){
//     $.ajax({
//         type: "GET",
//         dataType: "xml",
//         url: "http://api.sr.se/api/v2/traffic/messages",
//         success: function(xml){
//             $(xml).find("title").each(function(){
//             $("#output").append($(this).text() + "<br />");
//         });
//         }
//     });
// });
var ApiResponse = {
    init: function() {
    var xhttp = new XMLHttpRequest();
    var url = "http://api.sr.se/api/v2/traffic/messages";
    
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var myArray = JSON.parse(xhttp.responseText);
            alert(myArray);
            ApiResponse.handleResponse(myArray);
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
    },
    handleResponse: function(array){
         var i;
         var object = "";
         for(i = 0; i < array.length; i++) {
             object += "working";
            //object.push(array[i].title);
            //arr[i].display + '</a><br>';
        }
        // object += "";
        // var x = xmlDoc.getElementsByTagName("subcategory");
        // for(i = 0; i<x.length;i++){
        //     title.push(x[i].textContent);
        // }
        
        
        document.getElementById("output").innerHTML = object;
    }
    
    // function(){
    //     var trafficMessage = $.get("http://api.sr.se/api/v2/traffic/messages", );
    //     trafficMessage
    //     alert(trafficMessage);
    // }
};

window.onload = ApiResponse.init;
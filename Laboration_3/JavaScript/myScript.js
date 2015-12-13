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
        $.getJSON("http://api.sr.se/api/v2/traffic/messages?format=json&indent=true", function(data){
            for(var i in data.messages) {
                ApiResponse.handleResponse(data.messages[i]);
            }
        });
        var map = L.map('map').setView([59.84631644520587, 17.720024407748333], 13);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
            maxZoom: 18,
            id: 'johannalc.odj2kmj2',
            accessToken: 'pk.eyJ1Ijoiam9oYW5uYWxjIiwiYSI6ImNpaTN0ajdlajAwM212d20zNnVkaDZpZGcifQ.pmFpfuFQx4f2kLoTusWy-w'
        }).addTo(map);
        /*$.ajax({
            type: 'GET',
            url: "http://api.sr.se/api/v2/traffic/messages?format=json&indent=true",
            dataType: "json",
            complete: function(data){
                var parsedObj = JSON.parse(JSON.stringify(data));
                var jsonData = parsedObj.responseText;
                //var newData = JSON.parse(jsonData.messages);
                console.log(jsonData);
                //ApiResponse.handleResponse(data);
                /!*var messages = $(xmlData).find("messages").each(function(i){
                    titles[i] = $(this).find("title").text();
                });*!/
                //console.log(messages);
            }
        });*/



        /*$.getJSON("http://api.sr.se/api/v2/traffic/messages", function(data){
            var items = [];
            $.each(data, function(key, val){
                items.push( "<li id='" + key + "'>" + val + "</li>" );
            });
            $( "<ul/>", {
                "class": "my-new-list",
                html: items.join( "" )
            }).appendTo( "body" );
        });*/


    //var xhttp = new XMLHttpRequest();
    //var url = "http://api.sr.se/api/v2/traffic/messages";
    //
    //xhttp.onreadystatechange = function() {
    //    if (xhttp.readyState == 4 && xhttp.status == 200) {
    //        var myArray = JSON.parse(xhttp.responseText);
    //        alert(myArray);
    //        ApiResponse.handleResponse(myArray);
    //    }
    //};
    //xhttp.open("GET", url, true);
    //xhttp.send();
    },
    handleResponse: function(data){

        /*$(xml).find('message').each(function(){
            //var $messages = $(this);
            var $message = $(this);
            var title = $message.children('title');
            var priority = $message.attr('priority');
            var subCategory = $message.children('subcategory');
            var description = $message.children('description');*/
            //var title = data.title;
            console.log(data);

            var html = '<h3>' + data.title + '</h3>       <p>' + data.priority + ' ' + data.subcategory + '</p><p>' + data.description + '</p>';
            html += '';

            $('#output').append(html);
        //});
        // var i;
        // var object = "";
        // for(i = 0; i < array.length; i++) {
        //     object += "working";
        //    //object.push(array[i].title);
        //    //arr[i].display + '</a><br>';
        //}
        // object += "";
        // var x = xmlDoc.getElementsByTagName("subcategory");
        // for(i = 0; i<x.length;i++){
        //     title.push(x[i].textContent);
        // }
        
        
        //document.getElementById("output").innerHTML = object;
    }
    
    // function(){
    //     var trafficMessage = $.get("http://api.sr.se/api/v2/traffic/messages", );
    //     trafficMessage
    //     alert(trafficMessage);
    // }
};

window.onload = ApiResponse.init;
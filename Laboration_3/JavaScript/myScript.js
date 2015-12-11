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
        $.ajax({
            type: 'GET',
            url: "http://api.sr.se/api/v2/traffic/messages",
            dataType: "json",
            complete: function(data){
                var parsedObj = JSON.parse(JSON.stringify(data));
                var xmlData = parsedObj.responseText;
                console.log(xmlData);
                ApiResponse.handleResponse(xmlData);
                /*var messages = $(xmlData).find("messages").each(function(i){
                    titles[i] = $(this).find("title").text();
                });*/
                //console.log(messages);
            }
        });



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
    handleResponse: function(xml){

        $(xml).find('message').each(function(){
            //var $messages = $(this);
            var $message = $(this);
            var title = $message.children('title');
            var priority = $message.attr('priority');
            var subCategory = $message.children('subcategory');
            var description = $message.children('description');
            console.log(title);

            var html = '<h3>' + title.text() + '</h3>       <p>' + priority + ' ' + subCategory.text() + '</p><p>' + description.text() + '</p>';
            //html += '';

            $('#output').append(html);
        });
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
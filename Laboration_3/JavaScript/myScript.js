"use strict";
var ApiResponse = {
    init: function() {
        var map = L.map('map').setView([63.045, 14.326], 5);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
            maxZoom: 18,
            id: 'johannalc.odj2kmj2',
            accessToken: 'pk.eyJ1Ijoiam9oYW5uYWxjIiwiYSI6ImNpaTN0ajdlajAwM212d20zNnVkaDZpZGcifQ.pmFpfuFQx4f2kLoTusWy-w'
        }).addTo(map);

        $.getJSON("http://api.sr.se/api/v2/traffic/messages?format=json&indent=true", function(data){
            for(var i in data.messages) {
                ApiResponse.handleResponse(data.messages[i]);
            }
        });
    },
    handleResponse: function(data, map){
        console.log(data);
        var date = parseInt(data.createddate.substr(6));
        //date = date.replace(/[\/]/, "");
        var dateFormat = new Date(date);
        console.log(dateFormat);

        var html = '<h3>' + data.title + '</h3><p>' + data.priority + ' ' + data.subcategory + date + '</p><p>' + data.description + '</p>';
        html += '';

        $('#output').append(html);
    },
    showOnMap: function (coordinates, map){

    }
};

window.onload = ApiResponse.init;
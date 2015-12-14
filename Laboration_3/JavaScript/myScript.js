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

        $.getJSON("http://api.sr.se/api/v2/traffic/messages?format=json&size=50", function(data){
            for(var i in data.messages) {
                ApiResponse.handleResponse(data.messages[i], map);
            }
        });

        $("#all").click(function(){
            $("tr").removeClass("goldfish");
        });
        $("#traffic").click(function(){
            $(".0").removeClass("goldfish");
            $(".1").addClass("goldfish");
            $(".2").addClass("goldfish");
            $(".3").addClass("goldfish");
        });
        $("#public").click(function(){
            $(".0").addClass("goldfish");
            $(".1").removeClass("goldfish");
            $(".2").addClass("goldfish");
            $(".3").addClass("goldfish");
        });
        $("#planned").click(function(){
            $(".0").addClass("goldfish");
            $(".1").addClass("goldfish");
            $(".2").removeClass("goldfish");
            $(".3").addClass("goldfish");
        });
        $("#other").click(function(){
            $(".0").addClass("goldfish");
            $(".1").addClass("goldfish");
            $(".2").addClass("goldfish");
            $(".3").removeClass("goldfish");
        });
    },
    handleResponse: function(data, map){
        var html;
        var date;

        date = parseInt(data.createddate.substr(6));
        date = new Date(date);
        date = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate();

        ApiResponse.showDetails(data, map);

        html = '<tr class="' + data.category + '"><td>' + data.priority + '</td><td>' + data.subcategory + '</td><td>' + data.title + '</td><td>' + date + '</td></tr>';
        html += '';

        $('#output').append(html);
    },
    showDetails: function (data, map){
        var marker = L.marker([data.latitude, data.longitude]).addTo(map);
        marker.bindPopup("<h4>" + data.title + "</h4><p>" + data.description + "</p>");
    }
};

window.onload = ApiResponse.init;
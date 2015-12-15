"use strict";
var ApiResponse = {
    init: function() {
        var messages;
        var marker = [];
        var map;

        map = L.map('map').setView([63.045, 14.326], 5);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
            maxZoom: 18,
            id: 'johannalc.odj2kmj2',
            accessToken: 'pk.eyJ1Ijoiam9oYW5uYWxjIiwiYSI6ImNpaTN0ajdlajAwM212d20zNnVkaDZpZGcifQ.pmFpfuFQx4f2kLoTusWy-w'
        }).addTo(map);

        $.getJSON("http://api.sr.se/api/v2/traffic/messages?format=json&size=50", function(data){

            for(var i in data.messages){
                var date = parseInt(data.messages[i].createddate.substr(6));
                data.messages[i].createddate = new Date(date);
            }
            //Sort messages based on dates, most recent one first, before saving them locally in messages
            messages = data.messages;
            messages.sort(ApiResponse.sortOnDate);

            for(var i in data.messages) {
                marker[i] = L.marker([data.messages[i].latitude, data.messages[i].longitude]).addTo(map);
                ApiResponse.handleResponse(data.messages[i], map, marker[i]);
            }
        });
        $("#output").on("click", "tr", function(){
            for(var i in messages){
                if(messages[i].id.toString() === $(this).context.id){
                    ApiResponse.showDetails(messages[i], marker[i]).openPopup();
                }
            }
        });

        $("#all").click(function(){
            $(".event").removeClass("goldfish");
        });
        $("#traffic").click(function(){
            $(".event").addClass("goldfish");
            $(".0").removeClass("goldfish");
        });
        $("#public").click(function(){
            $(".event").addClass("goldfish");
            $(".1").removeClass("goldfish");
        });
        $("#planned").click(function(){
            $(".event").addClass("goldfish");
            $(".2").removeClass("goldfish");
        });
        $("#other").click(function(){
            $(".event").addClass("goldfish");
            $(".3").removeClass("goldfish");
        });
    },
    handleResponse: function(data, map, marker){
        var html;
        var date;

        date = ApiResponse.formatDate(data.createddate);

        //Draw the marker on the map
        ApiResponse.showDetails(data, marker);

        html = '<tr class="event ' + data.category + '" id="' + data.id + '"><td class="priority-' + data.priority + '">' + data.priority + '</td><td>' + data.subcategory + '</td><td>' + data.title + '</td><td class="date">' + date + '</td></tr>';
        $('#output').append(html);
    },
    showDetails: function (data, marker){
        return marker.bindPopup("<h4>" + data.title + "</h4><p>" + ApiResponse.formatDate(data.createddate) + '\n' + ApiResponse.formatTime(data.createddate) + '\n' + data.description + "</p>");
    },
    sortOnDate: function (a,b){
        var dateA = new Date(a.createddate).getTime();
        var dateB = new Date(b.createddate).getTime();
        return dateA > dateB ? -1 : 1;
    },
    formatDate: function(date){
        var monthFormat;
        var dateFormat;
        var newDate;

        //To fix up format of the month and day to look nice and neat
        if(date.getMonth().toString().length<2){
            monthFormat = '0' + date.getMonth();
        }
        else{
            monthFormat = date.getMonth();
        }
        if(date.getDate().toString().length<2){
            dateFormat = '0' + date.getDate();
        }
        else{
            dateFormat = date.getDate();
        }
        newDate = date.getFullYear() + '-' + monthFormat + '-' + dateFormat;
        return newDate;
    },
    formatTime: function(date){
        var hours;
        var minutes;

        if(date.getHours().toString().length<2){
            hours = '0' + date.getHours();
        }
        else{
            hours = date.getHours();
        }
        if(date.getMinutes().toString().length<2){
            minutes = '0' + date.getMinutes();
        }
        else{
            minutes = date.getMinutes();
        }
        return hours + ':' + minutes;
    }
};

window.onload = ApiResponse.init;
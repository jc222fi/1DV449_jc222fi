"use strict";
function ApiResponse(){

    var that = this;

    this.map = {};
    this.marker = [];

    var ApiUrl = "http://api.sr.se/api/v2/traffic/messages?format=json&size=50";

    var startCheckingCache = function(){
        window.setInterval(getData, 1000 * 60); // Check every minute
        console.log("checked if cache is old");
    };
    var getMap = function(){
        that.map = L.map('map').setView([63.045, 14.326], 5);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
            maxZoom: 18,
            id: 'johannalc.odj2kmj2',
            accessToken: 'pk.eyJ1Ijoiam9oYW5uYWxjIiwiYSI6ImNpaTN0ajdlajAwM212d20zNnVkaDZpZGcifQ.pmFpfuFQx4f2kLoTusWy-w'
        }).addTo(that.map);
    };
    var encodeTags = function(str){
        var lt = /</g,
            gt = />/g,
            ap = /'/g,
            ic = /"/g;
        return str.toString().replace(lt, "&lt;").replace(gt, "&gt;").replace(ap, "&#39;").replace(ic, "&#34;");
    };
    var sanitizeInput = function(object){
        for(var i in object){
            encodeTags(object[i].toString());
        }
    };
    var getData = function(){

        var messages;
        // IF data is old or non existant
        if(!lscache.get(ApiUrl))
        {
            $.getJSON(ApiUrl, function(data){
                messages = that.parseDate(data.messages, true);

                //Sort messages based on dates, most recent one first, before saving them locally in messages
                messages.sort(sortOnDate);
                that.createMarkers(messages);

                // Save in cache
                lscache.set(ApiUrl, messages, 15);
            });
        }
        // Data exists in cache
        else
        {
            // Get from cache
            var messages = lscache.get(ApiUrl);
            messages = that.parseDate(messages, false);
            that.createMarkers(messages);
        }
    };
    this.parseDate = function(messages, parseSpecial) {
        for(var i in messages){
            sanitizeInput(messages[i]);
            var date = messages[i].createddate;
            if(parseSpecial) {
                date = parseInt(date.substr(6));
            }
            messages[i].createddate = new Date(date);
        }
        return messages;
    };
    this.createMarkers = function(messages) {
        that.marker = [];
        for(var i in messages) {
            that.marker[i] = L.marker([messages[i].latitude, messages[i].longitude]).addTo(that.map);
            handleApiResponse(messages[i], that.map, that.marker[i]);
        }
    };
    this.initialize = function() {

        getMap();
        getData();
        startCheckingCache();

        $("#output").on("click", "tr", function(){

            var allMessages = lscache.get(ApiUrl);
            that.parseDate(allMessages, false);
            for(var i in allMessages){
                if(allMessages[i].id.toString() === $(this).context.id){
                    showDetails(allMessages[i], that.marker[i]).openPopup();
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
    };
    var sortOnDate = function(a, b){
        var dateA = new Date(a.createddate).getTime();
        var dateB = new Date(b.createddate).getTime();
        return dateA > dateB ? -1 : 1;
    };
    var handleApiResponse = function(data, map, marker) {
        var html;
        var date;

        date = formatDate(data.createddate);

        //Draw the marker on the map
        showDetails(data, marker);

        html = '<tr class="event ' + data.category + '" id="' + data.id + '"><td class="priority-' + data.priority + '">' + data.priority + '</td><td>' + data.subcategory + '</td><td>' + data.title + '</td><td class="date">' + date + '</td></tr>';
        $('#output').append(html);
    };
    var formatDate = function(date) {
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
    };
    var formatTime = function(date) {
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
    };
    var showDetails = function(data, marker) {
        return marker.bindPopup("<h4>" + data.title + "</h4><p>" + formatDate(data.createddate) + ' ' + formatTime(data.createddate) + '<br />' + data.description + "</p>");
    };
}

$(document).ready(function(){
    var apiResponseObj = new ApiResponse();
    apiResponseObj.initialize();
});
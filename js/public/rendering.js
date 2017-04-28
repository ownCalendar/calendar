// import app.js;

$(document).ready(function(){
 var sendRequest = function(owner, url, uri, name) {
   var method = 'POST';
   var requestUrl = '../reserveAppointment';
   var headers = {
     'Content-Type': 'application/xml; charset=utf-8'
   };
   var data = JSON.stringify({
     "owner":owner,
     "url":url,
     "uri":uri,
     "name":name
    });

   var httpRequest = new XMLHttpRequest();
   httpRequest.open(method, requestUrl, true);
   httpRequest.setRequestHeader("Content-type", "application/json");
   httpRequest.onreadystatechange = function() {//Call a function when the state changes.
     if(httpRequest.readyState == 4 && httpRequest.status == 200) {
       alert("Your appointment has been selected");
     }
   }
   httpRequest.send(data);
 };

  var appointmentConfirmedAlert = function() {
    alert("You have already selected an appointment.")
  }

  var confirmAppointment = function(){
      var conf = confirm("Do you want to select this appointment?");

      if(conf) {
        var name = prompt("Please enter your full name: ");

        if (name != null) {
          var uri = $(this).attr("data-uri");
          var url = $(this).attr("data-url").split('/').slice(-2)[0];
          var owner = $(this).attr("data-owner");

          // alert("You have selected this appointment. Please await confirmation.");
          $(".fc-day-grid-event").unbind( "click", confirmAppointment );
          $(".fc-day-grid-event").bind("click", appointmentConfirmedAlert);

          // Write extra fuction to handle event updates
      	  // call it here with uri, url, and name
      	  sendRequest(owner, url, uri, name);
	       }

      }
  };

  // removing menu buttons
  var headerAppName = $(".header-appname").clone();
  $(".menutoggle").remove();
  $('<div class="header-appname-container"></div>').insertAfter("#owncloud").append(headerAppName);

  // setting up events
  $(".fc-content, .fc-event,.fc-event-container,.fc-title, .fc-day-grid-event").css('cursor', 'pointer');
  $(".fc-day-grid-event").click(confirmAppointment);
});

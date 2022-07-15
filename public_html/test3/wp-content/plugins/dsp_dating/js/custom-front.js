// new captcha code	
var userLat=0,userLng=0,userLocation,marker,map;
var onloadCallback = function() {
		if(jQuery('#reCaptcha').length > 0){
			grecaptcha.render('reCaptcha', {
			  'sitekey' : '6LeaFf8SAAAAAOvDpgAV1P5Wo0tEc2gfi53B0Sl-'
			});
		}
	}

// function to play sound
function playSound(filename){   
	document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
}

// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete,lat,lang;
var componentForm = {
  lat: 'lat',
  lng: 'lng',
  country: 'country',
  
};

/**
 * [getCountryUsingAddresComponents is used to extract only country values from json formatted value return using google api]
 * @param  {[array]} addressComponents [All the list of addresses ]
 * @return {[string][country]
 */
function getCountryUsingAddresComponents(addressComponents){
	var country = '';
	for(var i = 0; i < addressComponents.length; i += 1) {
	  var addressObj = addressComponents[i];
	  for(var j = 0; j < addressObj.types.length; j += 1) {
	    if (addressObj.types[j] === 'country') {
	      	country = addressObj.long_name; // confirm that this is the country name
	    }
	  }
	}
	return country;
}

// [START region_fillform]
function fillInAddress() {
  // Get the place details from the autocomplete object.
	var place = autocomplete.getPlace();
	var lat = place.geometry.location.lat();
	var lng = place.geometry.location.lng();
	var addressComponents = place.address_components;
	var country = getCountryUsingAddresComponents(addressComponents);
	var datas = [
				{key:'lat',val:lat},
				{key:'lng',val:lng},
				{key:'country',val:country}
			];
  
	for (var i = 0; i <= datas.length+1; i++) {
		  var data = datas.pop();
		  var key = data.key;
		  var value = data.val;
		  document.getElementById(key).value = value;
		}
 
}

// edit my location called function to get lattitude and longitude values dynamically
//(function($) { 
function getLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		} else { 
			x.innerHTML = "Geolocation is not supported by this browser.";
		}

}

function showPosition(position) {
   userLat =  position.coords.latitude;
   userLng =  position.coords.longitude;
   var element = document.getElementById('edit_location');
   var site_url = element.getAttribute("data-siteurl");
   var redirect_url = site_url + "/members/edit/edit_my_location?lat="+userLat+"&lng="+userLng;
   window.location.href = redirect_url;
}
//)(jQuery);



jQuery(document).ready(function($){
    $('#edit_location').click(function(e){
        e.preventDefault();
        getLocation();

    });
    var notice = $('div.notices');
    notice.show();
    $('body').prepend(notice);

    setTimeout(function() {
        jQuery('div.mesagebox').fadeOut("slow");
    }, 8000);


    $('.dspdp-tab-container').addClass('touch');
    $('.dspdp-tab-container').css('cursor','pointer');
    $('.touch .line.top-gap').appendTo('.touch .dsp-tab-container');


    $('.dsp-multiple-select').chosen();
});


/**
 * Add Go to current location button on gmap.
 * @param map
 * @param marker
 */
function addGoToCurrentLocationButton(map, marker)
{
	let controlDiv = document.createElement('div');

	let firstChild = document.createElement('button');
	firstChild.style.backgroundColor = '#fff';
	firstChild.style.border = 'none';
	firstChild.style.outline = 'none';
	firstChild.style.width = '28px';
	firstChild.style.height = '28px';
	firstChild.style.borderRadius = '2px';
	firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
	firstChild.style.cursor = 'pointer';
	firstChild.style.marginRight = '10px';
	firstChild.style.padding = '0';
	firstChild.title = 'Your Location';
	controlDiv.appendChild(firstChild);

	let secondChild = document.createElement('div');
	secondChild.style.margin = '5px';
	secondChild.style.width = '18px';
	secondChild.style.height = '18px';
	secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-2x.png)';
	secondChild.style.backgroundSize = '180px 18px';
	secondChild.style.backgroundPosition = '0 0';
	secondChild.style.backgroundRepeat = 'no-repeat';
	firstChild.appendChild(secondChild);

	google.maps.event.addListener(map, 'center_changed', function () {
		secondChild.style['background-position'] = '0 0';
	});

	firstChild.addEventListener('click', function () {
		let imgX = '0',
			animationInterval = setInterval(function () {
				imgX = imgX === '-18' ? '0' : '-18';
				secondChild.style['background-position'] = imgX+'px 0';
			}, 500);

		if(navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				map.setCenter(latlng);
				marker.setPosition(latlng);
				document.getElementById("lat").value = position.coords.latitude;
				document.getElementById("lng").value = position.coords.longitude;
				clearInterval(animationInterval);
				secondChild.style['background-position'] = '-144px 0';
			});
		} else {
			clearInterval(animationInterval);
			secondChild.style['background-position'] = '0 0';
		}
	});

	controlDiv.index = 1;
	map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
}

function initialize() {
 
  if(jQuery('#autocomplete').length > 0){
	// Create the autocomplete object, restricting the search
	// to geographical location types.
	autocomplete = new google.maps.places.Autocomplete(
		/** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
		{ types: ['geocode'] });
	// When the user selects an address from the dropdown,
	// populate the address fields in the form.
	google.maps.event.addListener(autocomplete, 'place_changed', function() {
	  fillInAddress();
	});
  }else if (jQuery('#lat').length > 0 && jQuery('#lng').length > 0){
	userLat =  document.getElementById("lat").value;
	userLng =  document.getElementById("lng").value;
	userLocation = new google.maps.LatLng(userLat, userLng);
	var mapOptions = {
	  zoom: 13,
	  center:userLocation
	};

	map = new google.maps.Map(document.getElementById('map_canvas'),
			mapOptions);

	marker = new google.maps.Marker({
	  map:map,
	  draggable:true,
	  animation: google.maps.Animation.DROP,
	  position: userLocation
	});
	google.maps.event.addListener(marker, 'click', toggleBounce);
	google.maps.event.addListener(marker, 'dragend', function() {
			userLat =  this.position.lat();
			userLng =  this.position.lng();
			document.getElementById("lat").value = userLat;
			document.getElementById("lng").value = userLng;
	});
	addGoToCurrentLocationButton(map, marker);
  }
}

function toggleBounce() {
  if (marker.getAnimation() != null) {
	marker.setAnimation(null);
  } else {
	marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

google.maps.event.addDomListener(window, 'load', function(){
		initialize();
  });


/**
 *  This function is used to compare the version of jquery;
 */

 function cmpVersion(v1, v2) {
    if(v1===v2) return 0;
    var a1 = v1.toString().split(".");
    var a2 = v2.toString().split(".");
    for( var i = 0; i < a1.length && i < a2.length; i++ ) {
        var diff = parseInt(a1[i],10) - parseInt(a2[i],10);
        if( diff>0 ) {
            return 1;
        }
        else if( diff<0 ) {
            return -1;
        }
    }
    diff = a1.length - a2.length;
    return (diff>0) ? 1 : (diff<0) ? -1 : 0;
}

/**
 *  This function is used to display message in message box
 */

function displayMessage(msg){
	el = document.getElementById("message-box");
	el.innerHTML = msg;
}

/**
 * [displayLicenseNotice description]
 * @return {[type]} [description]
 */
function displayLicenseNotice() {
    jQuery('div.license-notice').fadeIn();
}

/**
 *  This function is used to handle 
 *  language status
 *  @param integer	
 *  @param string
 *  @param string
 */
function language_status(id, status, url) { 
    var loc = window.location.href;
    if (loc.search("Action") > -1) {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }

    if (loc.indexOf('?') == -1)  {

        loc += "?Action=language_status&lid=" + id + "&status=" + status;

    } else {

        loc += "&Action=language_status&lid=" + id + "&status=" + status;

    }
    window.location.href = loc;
} // End language_status()
//start members page menu mobile responsive
jQuery(function(){
    var windowWidth = jQuery(window).width();
    if(windowWidth < 640) {
        jQuery(".dsp-tab-container").click(function () {
            jQuery(".dsp-line, .line").toggle();
        });
    }
});

jQuery(".dsp-tab-container").click(function(){
  jQuery(".dsp-line, .line").toggle();
});

jQuery(document).ready(function(){
	if(jQuery('.news-feed-page li div').hasClass('dspdp-bordered-item')){
			jQuery('#news_feed_box').show();
	}
	else{
			jQuery('.news-feed-page li').append('<span class="no--feed-item">No newsfeed to show at the moment.</span>');
	}
});
	jQuery('.menu-item').click(function(){
		if(jQuery('.menu-item').siblings().hasClass('current-menu-item')){
				jQuery(this).siblings().removeClass('current-menu-item');
			}
			else{};
		jQuery(this).addClass('current-menu-item');
		});

/**
 *  Additional search icon change 
 *  */	

	jQuery(document).ready(function(){
		// console.log("Hello world");
	// 	 if($('.text-title > .icon-plus')){
	// 	 	$(this).removeClass('.icon-plus');
	// 	 	$(this).addClass('.icon-minus');
	// 	 }
		 jQuery('.text-title').click(function () {
			 /*
			 var col_class = $(this).attr("class");
			 var act_col_class = col_class.split(" ")[1];
			 var act_col_class_id = act_col_class.split("-")[2];
*/
			if(jQuery(this).find("span").hasClass('icon-plus'))
			{
				jQuery(this).find("span").removeClass('icon-plus'); 
					jQuery(this).find("span").addClass('icon-minus');
				//	jQuery("#collapse"+act_col_class_id).show(); 
			}
			else
			{ 
				jQuery(this).find("span").removeClass('icon-minus');     
					jQuery(this).find("span").addClass('icon-plus'); 
				//	jQuery("#collapse"+act_col_class_id).hide();
			}
			}); 	 
	

		});

 

				

window.infowindow_active = false;
window.previous_marker = previous_marker_img = false;

jQuery( document ).ready(function() {


jQuery(document).on("click", "#hubbed-btn-click-collect",function(){
jQuery(".hubbed-modal").addClass("active");

var hubbed_first_phase = jQuery('.hubbed-second-phase');
	hubbed_first_phase[0].style.display = 'none';
	
	var input_one = document.getElementById('hubbed-search-field-one');
	var input_two = document.getElementById('hubbed-search-field-two');
	var options = {
		types: ["(regions)"],
		componentRestrictions: { country: "au" }
	};
	window.autocomplete_one = new google.maps.places.Autocomplete(input_one, options);
	window.autocomplete_two = new google.maps.places.Autocomplete(input_two, options);
	google.maps.event.addListener(autocomplete_one, 'place_changed', function () {
		var place = autocomplete_one.getPlace();
		if (place.geometry) {
			for (var i = 0; i < place.address_components.length; i++) {
				for (var j = 0; j < place.address_components[i].types.length; j++) {
					if (place.address_components[i].types[j] == "postal_code") {
						window.current_postcode = place.address_components[i].long_name;
						window.IsplaceChange = true;
					}
				}
			}
		}
	});
	input_one.onkeydown = function() {
		window.IsplaceChange = false;
	};
	input_two.onkeydown = function() {
		window.IsplaceChange = false;
	};
	google.maps.event.addListener(autocomplete_two, 'place_changed', function () {
		var place = autocomplete_two.getPlace();
		if (place.geometry) {
			for (var i = 0; i < place.address_components.length; i++) {
				for (var j = 0; j < place.address_components[i].types.length; j++) {
					if (place.address_components[i].types[j] == "postal_code") {
						window.current_postcode = place.address_components[i].long_name;
						window.IsplaceChange = true;
					}
				}
			}
		}
	});
  
  	jQuery('.hubbed-modal').addClass('hubbed-is-visible');
  	jQuery('body').addClass('hubbed-scrollfix');

if(!window.bounds)
{
window.bounds = new google.maps.LatLngBounds();
}
if(!window.markers){
window.markers = [];
}
if(!window.hubbed_map){
		window.hubbed_map = new google.maps.Map(document.getElementById('hubbed-map'),{
			center: { lat: -26.1772288, lng: 133.4170119 },
    		zoom: 4,
			draggable: 1,
			zoomControl: 1,
			zoomControlOptions: {
				position: google.maps.ControlPosition.RIGHT_CENTER
			},
			mapTypeControl: 0,
			streetViewControl: 0,
			fullscreenControl: 0,
			gestureHandling: "greedy",
			styles: [
				{
				  featureType: "poi.business",
				  stylers: [{ visibility: "off" }]
				},
				{
				  featureType: "transit",
				  elementType: "labels.icon",
				  stylers: [{ visibility: "off" }]
				}
			  ]
			
		});
	}

});

jQuery(document).on("click",".hubbed-close", function() {
  jQuery(".hubbed-modal").removeClass("active");
  jQuery(".hubbed-modal").removeClass("hubbed-is-visible");
  jQuery('body').removeClass('hubbed-scrollfix');


});


});
var closeMyLearnMoreHubbed = async function(e){
	document.querySelector('.hubbed-learn-more-modal').classList.remove('hubbed-is-visible');
}
var learnMorePopupHubbed = async function(e){
	document.querySelector('.hubbed-learn-more-modal').classList.add('hubbed-is-visible');
}
function LoaderShow()
{
  	jQuery('#hubbedModelLoaderBackgroud').css('display','flex');
  	jQuery('body').addClass("hubbed-loader-block");
}

function LoaderHide(){
  	jQuery('#hubbedModelLoaderBackgroud').css('display','none');
	jQuery('body').removeClass('hubbed-loader-block');
}


function showhidemap(show){
	
	if(show == 1){	
		jQuery("#hubbed_display_response").removeClass('hubbed-show-list');
		jQuery("#hubbed-explore-toggle-list").removeClass('active');
		jQuery("#hubbed-explore-toggle-map").addClass('active');
	}else{
		jQuery("#hubbed_display_response").addClass('hubbed-show-list');
		jQuery("#hubbed-explore-toggle-list").addClass('active');
		jQuery("#hubbed-explore-toggle-map").removeClass('active');
	}

};


function hubbedlisting(searched_nonce,searched_postcode,searched_services,searched_channel,searched_radius,page_no,append=0,isfirsttime=0)
{
	if(isfirsttime){
		var place = autocomplete_one.getPlace();
		if (window.IsplaceChange && place && place.geometry) {
			// var searched_postcode = window.current_postcode;
			var searched_postcode = document.getElementsByClassName("hubbed-search-field-one")[0].value;
		}else{
			var searched_postcode = document.getElementsByClassName("hubbed-search-field-one")[0].value;
		}
		document.getElementById("hubbed-search-field-two").value = document.getElementsByClassName("hubbed-search-field-one")[0].value;
	}else{
		var place = autocomplete_two.getPlace();
		if (window.IsplaceChange && place && place.geometry) {
			// var searched_postcode = window.current_postcode;
			var searched_postcode = document.getElementById("hubbed-search-field-two").value;
		}else{
			var searched_postcode = document.getElementById("hubbed-search-field-two").value;
		}
		document.getElementsByClassName("hubbed-search-field-one")[0].value = document.getElementById("hubbed-search-field-two").value;
	}
	LoaderShow();

jQuery.ajax({
        type : "post",
        dataType : "json",
        url : hubbed_ajax.ajaxurl,
        data : {action: "searched_list", searched_postcode:searched_postcode,searched_services:searched_services,searched_channel:searched_channel,searched_nonce:searched_nonce, searched_radius:searched_radius,page_no:page_no },
        success: function(response) 
        {

	LoaderHide();
        var responsedata = JSON.stringify(response);

		var returnedData = response.searchresult;
		console.log(returnedData);
         if (returnedData.responseMessage == "Success") {
				var append_html = "";
				var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
				var AuDatString = new Date().toLocaleString("en-US", {timeZone: "Australia/Sydney"});
				var AuDate = new Date(AuDatString);
				var todayDay = AuDate.getDay();
				var TodayDayName = days[todayDay];
				var dayDifferent = 7 - todayDay;
                jQuery(returnedData.data).each(function(index, element){
                	/* Start Process for Operating Hours */
					var CurrentHours;
					var ListedHours = [];
					var isOpen = false;
					element.businessHours.forEach(function(Belement, pindex) {
						if(TodayDayName == Belement.day){
							CurrentHours = Belement;
						}
						var cDay = days.indexOf(Belement.day);
						var Lindex = (cDay >= todayDay)?cDay-todayDay:(dayDifferent+cDay);
						ListedHours[Lindex] = Belement;
					});
					let getMonth = String((parseInt(AuDate.getMonth())+1)).padStart(2, '0');
					let getDate = String(AuDate.getDate()).padStart(2, '0');
					var startDate = new Date(AuDate.getFullYear() +"-"+ getMonth + "-" + getDate+ " "+ ListedHours[0].open_time  );
					var endDate = new Date(AuDate.getFullYear() +"-"+ getMonth + "-" + getDate+ " "+ ListedHours[0].close_time );
					if(startDate <= AuDate && AuDate <= endDate){
						isOpen = true;
					}
					/* End Process for Operating Hours */
					var businessIcon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity=".03" d="M11.743 24c5.247 0 9.5-.672 9.5-1.5s-4.253-1.5-9.5-1.5-9.5.672-9.5 1.5 4.253 1.5 9.5 1.5z" fill="#000"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M12.054 0C7.221.002 3.456 3.932 3.297 9.081c-.216 6.76 5.723 8.558 8.083 12.973.492.825.856.825 1.348 0 2.36-4.415 8.3-6.212 8.083-12.973C20.652 3.932 16.887.001 12.054 0z" fill="#000"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M9.019 8.692a.405.405 0 00-.404.398v3.512c0 .22.189.398.4.398h.555c.22 0 .4-.174.4-.391V10.74a.4.4 0 01.39-.39h1.248a.39.39 0 01.392.39v1.869c0 .216.182.391.402.391h2.581a.404.404 0 00.402-.398V9.09a.4.4 0 00-.404-.398H9.02zM14.7 5.203A.465.465 0 0014.32 5H9.68c-.149 0-.29.075-.38.203L8.078 7.428c-.188.267-.01.649.303.649h7.24c.313 0 .49-.382.304-.649l-1.225-2.225z" fill="#fff"></path></svg>';
					var address2 = "";

                    var address = element['address']['street1'] + ', ' +
                    address2 +
                    element['address']['city'] + ', ' +
                    element['address']['state'] + ', ' +
                    element['address']['country'] + ', ' +
                    element['address']['postcode'];

					var map_button = '<button  class="hubbed_selected_item hubbed-button-same select-address" type="button" data-company="' + element['name'].replace(/&/g, "and") + '" data-address="'+element['address']['street1'].replace(/&/g, "and")+'" data-address2="'+address2+'" data-city="'+element['address']['city']+'" data-province="'+element['address']['state']+'" data-country="'+element['address']['country']+'" data-zip="'+element['address']['postcode']+'" data-hubbedlb="'+element['storeDlb']+'" onclick="mycheckout(this)">Select this location</button>';
					var map_html = '<div class="hubbed-map-address"> <h4 class="hubbed-Name hubbed-h4-heading">'+element['name']+'</h4><p class="hubbed-address hubbed-parapgraph-text">'+businessIcon+address+'</p>'+map_button+'</div>';

					var infowindow = new google.maps.InfoWindow({
						content: map_html
					  });
					var main_marker = "https://apps.hubbed.com.au/storage/shopify/images/google-place.png";
					var marker = new google.maps.Marker({
						position: {lat: element['latitude'], lng: element['longitude']},
						map: hubbed_map,
						icon: element['channel']['marker_inactive']
						//icon: main_marker
					});
					marker.addListener('click', function() {
						var box = document.querySelector('#hubbed_display_records'),
						targetElm = document.querySelector('.hubbed-drop-'+element['droplocation_id']);
						scrollToElm( box, targetElm , 600 );  
						//infowindow.open(hubbed_map, marker);
						previous_marker && previous_marker.setIcon(previous_marker_img);
						previous_marker = marker;
						marker.setIcon(element['channel']['marker_active']);
						infowindow_active && infowindow_active.close();
						previous_marker_img = element['channel']['marker_inactive'];
						infowindow.open(hubbed_map, marker);
						infowindow_active = infowindow;

						});
					google.maps.event.addListener(infowindow,'closeclick',function(){
						marker.setIcon(element['channel']['marker_inactive']);
					});
					window.markers[element['droplocation_id']] = marker;
					bounds.extend(new google.maps.LatLng(element['latitude'],element['longitude']));

                	address2 = '';
                    /*if (element['address']['street2'] != null || element['address']['street2'] != '' || element['address']['street2'] != 'null'){
                      address2 = element['address']['street2'] + ', ';
                    }*/
                    var htmlWorkingHours = "<div class='hubbed-timing-expand'>";
                    let arrow_icon = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13.434 18.44a2.065 2.065 0 01-1.435.56 2.068 2.068 0 01-1.435-.56l-.712-.679c-.015-.011-.019-.03-.033-.044L.592 8.951a1.848 1.848 0 010-2.711l.71-.68a2.098 2.098 0 012.856 0L12 13.01l7.843-7.45a2.096 2.096 0 012.854 0l.712.68c.79.747.79 1.961 0 2.71l-9.23 8.767c-.013.016-.018.033-.03.044l-.714.68z"></path></svg>'
                    let use_arrow_icon = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.44 10.566l-.679-.714c-.011-.013-.028-.017-.044-.03L8.951.591a1.848 1.848 0 00-2.711 0l-.68.712a2.096 2.096 0 000 2.854l7.45 7.843-7.45 7.84a2.098 2.098 0 000 2.857l.68.71c.747.79 1.961.79 2.71 0l8.767-9.227c.014-.014.033-.018.044-.033l.68-.712c.375-.396.56-.916.559-1.435a2.065 2.065 0 00-.56-1.435z"></path></svg>'
                    if(isOpen){
                    	htmlWorkingHours += '<span class="open-now">Open now</span>'

                    }else{
                    	htmlWorkingHours += '<span class="closed-now">Closed now</span>'
                    }
                    htmlWorkingHours += "<p>" + ListedHours[0].open_time.substr(0,5) + " - "+ ListedHours[0].close_time.substr(0,5) + "</p>"+arrow_icon+"</div>";
                    OtherhtmlWorkingHours = "<div class='hubbed-expand-work-days'><dl class='hubbed-days-list'>";
                    ListedHours.forEach(function(Belement, pindex) {
                    	OtherhtmlWorkingHours += "<dt class='hubbed-days-title'>" + Belement.day + "</dt>";
                    	OtherhtmlWorkingHours += "<dd class='hubbed-days-time'>" + Belement.open_time.substr(0,5) + " - " + Belement.close_time.substr(0,5) +"</dd>";
                    });
                    OtherhtmlWorkingHours += "</dl></div>";

                    append_html += '<div class="hubbed-show-address hubbed-drop-'+element['droplocation_id']+'">\n\
                    					<div class="hubbed-address-details">\n\
                							<h4 class="hubbed-Name hubbed-h4-heading" data-location="'+element['droplocation_id']+'" data-company="' + element['name'].replace(/&/g, "and") + '" data-address="'+element['address']['street1'].replace(/&/g, "and")+'" data-address2="'+address2+'" data-city="'+element['address']['city']+'" data-province="'+element['address']['state']+'" data-country="'+element['address']['country']+'" data-zip="'+element['address']['postcode']+'" data-hubbedlb="'+element['storeDlb']+'" onclick="mycheckout(this)">' + element['name'] + '</h4>\n\
                							<p class="hubbed-address hubbed-parapgraph-text">' + businessIcon + address + '</p>\n\
                							<div class="timing hubbed-parapgraph-text"> ' + htmlWorkingHours + OtherhtmlWorkingHours +'</div>\n\
            							</div>\n\
            							<div class="hubbed-right">'+use_arrow_icon+'\n\
            								<button  class="hubbed_selected_item hubbed-button-same select-address" type="button" data-company="' + element['name'].replace(/&/g, "and") + '" data-address="'+element['address']['street1'].replace(/&/g, "and")+'" data-address2="'+address2+'" data-city="'+element['address']['city']+'" data-province="'+element['address']['state']+'" data-country="'+element['address']['country']+'" data-zip="'+element['address']['postcode']+'" data-hubbedlb="'+element['storeDlb']+'" onclick="mycheckout(this)">Select this location</button>\n\
        								</div>\n\
        							</div>';
				});
				hubbed_map.fitBounds(bounds);
                document.getElementById("hubbed_display_showmorebtn").innerHTML = '';
                
                if (returnedData.totalPages > 1) {
                  if(page_no < returnedData.totalPages){
                    var nextPage = parseInt(page_no) + 1;
                    document.getElementById("hubbed_display_showmorebtn").innerHTML = "<div class='hubbed-loadmore-section'><div class='hubbed-right'><span class='hubbed-button-same load_more hubbed_loadmore hubbed-button-same' id='load_more' data-page='"+ nextPage +"' data-searchKeyword='"+ searched_postcode +"' data-searchedChannel='"+ searched_channel +"' data-searchedServices='"+ searched_services +"' data-searchedNonce='"+searched_nonce+"'>Load More</span></div></div>";
                  }else{
                    document.getElementById("hubbed_display_showmorebtn").innerHTML = "<div class='hubbed-loadmore-section'><div class='hubbed-right'>-- No More records --</div></div>";
                  }

                }

                if(append == 1)
                {
                    document.getElementById("hubbed_display_records").innerHTML += append_html;
                }
                else{
                 	document.getElementById("hubbed_display_records").innerHTML = append_html;   
                }
                document.querySelectorAll('.hubbed-address-details .hubbed-h4-heading').forEach(function(singleElement) {
                    singleElement.addEventListener('click',function(){
                      let indexid = this.getAttribute('data-location');
                      new google.maps.event.trigger( window.markers[indexid], 'click' );
                  })
                })
                document.querySelectorAll('.hubbed-timing-expand').forEach(function(hubbedExpandTime) {
									hubbedExpandTime.addEventListener('click',function(){
										this.parentElement.querySelector('.hubbed-expand-work-days').classList.toggle('active');
										this.classList.toggle('active');
									})
								});
                
            }
            else
            {
                document.getElementById("hubbed_display_records").innerHTML = '<div class="hubbed-show-address"><p><b>We are sorry. A pickup store is not available within your search area. Please increase the radius of the search or search for an alternate location.</b></p></div>';   
								jQuery("#hubbed-explore-toggle-list").click();
								document.getElementById("hubbed_display_showmorebtn").innerHTML ="";
								window.markers.forEach(function(singleMarker, indexMarker) {
                  window.markers[indexMarker].setMap(null);
                });

								window.markers = [];
								window.bounds = new google.maps.LatLngBounds();

            
            }
           
           
           }

	});
 
}

// Click on show more

jQuery(document).on('click', '#hubbed-display-showmorebtn', function(){
var page_no = parseInt(jQuery(this).attr('data-page'));
console.log(page_no);
var next_page_no = page_no + 1;
console.log(next_page_no);

jQuery(this).attr('data-page',next_page_no);

	var  searched_postcode = jQuery("#hubbed-search-field-input").val();
    var searched_services = jQuery(".hubbed_filter_servises").val();
    var searched_channel = jQuery(".hubbed_filter_channel").val();
    var searched_radius = jQuery('input[name="hubbed_radius"]:checked').val();
    var  searched_nonce = jQuery("#search_nonce_postcode").val();
    var page_no = jQuery('#hubbed-display-showmorebtn').attr('data-page');


//hubbedlisting(searched_postcode,searched_services,searched_channel,searched_radius,page_no);

});


// click on search button

jQuery(document).on('keyup',"#hubbed-search-field-one" ,function(event) {
    if (event.keyCode === 13) {
        jQuery("#hubbed-search-button-first").click();
    }
});
jQuery(document).on('click', '#hubbed-search-button-first', function()
{
	jQuery('#hubbed_span_clear_search').addClass('hubbed-second-phase-active');

var hubbed_first_phase = jQuery('.hubbed-first-phase');
	hubbed_first_phase[0].style.display = 'none';
var hubbed_second_phase = jQuery('.hubbed-second-phase');
	hubbed_second_phase[0].style.display = 'block';

	var  searched_postcode = jQuery("#hubbed-search-field-one").val();
    var searched_services = '';
    var searched_channel = '';
    //var searched_radius = 3;
    var searched_radius = jQuery('input[name="hubbed_radius"]:checked').val();
    var  searched_nonce = jQuery("#search_nonce_postcode").val();
    var page_no = jQuery('#load_more').attr('data-page');
    page_no = page_no  - 1;
    if (page_no.length == null) {
    	page_no = 1;
    }
	var  searched_nonce = jQuery("#search_nonce_postcode").val();
	jQuery("#hubbed-search-field-two").val(searched_postcode);
  


    hubbedlisting(searched_nonce,searched_postcode,searched_services,searched_channel,searched_radius,page_no,append=0,isfirsttime=1)

  });  


jQuery(document).on('click', '#load_more', function()
{

	var page_no = jQuery(this).attr("data-page");
	var searched_postcode = jQuery(this).attr("data-searchKeyword");
	var searched_services = jQuery(this).attr("data-searchedServices");
	var searched_channel = jQuery(this).attr("data-searchedChannel");
	var searched_nonce = jQuery(this).attr("data-searchedNonce");
	//var searched_radius = 3;
	var searched_radius = jQuery('input[name="hubbed_radius"]:checked').val();
  	
  	hubbedlisting(searched_nonce,searched_postcode,searched_services,searched_channel,searched_radius,page_no,append=1)
});

jQuery("#hubbed-search-field-two").keyup(function(event) {
    if (event.keyCode === 13) {
        jQuery("#hubbed-search-button-second").click();
    }
});
jQuery(document).on('click', '#hubbed-search-button-second', function(){
	var  searched_postcode = jQuery("#hubbed-search-field-two").val();
	window.markers.forEach(function(singleMarker, indexMarker) {
      window.markers[indexMarker].setMap(null);
    });
	window.markers = [];
	window.bounds = new google.maps.LatLngBounds();

            
            
	var page_no = 1;
	var searched_radius = jQuery('input[name="hubbed_radius"]:checked').val();
	var searched_services = jQuery('input[name="hubbed_filter_service"]:selected').val();
	var searched_channel = jQuery('input[name="hubbed_filter_channel"]:selected').val();
	var  searched_nonce = jQuery("#search_nonce_postcode").val();
	if (searched_services == null) { searched_services="";}
	if (searched_channel == null) { searched_channel="";}
	
	
  	
  	hubbedlisting(searched_nonce,searched_postcode,searched_services,searched_channel,searched_radius,page_no,append=0)


});


function hubbedselectAddress(data)
{
	LoaderShow();
var address = jQuery(data).attr("data-address");
var company = jQuery(data).attr("data-company");
var address2 = jQuery(data).attr("data-address2");
var city = jQuery(data).attr("data-city");
var state = jQuery(data).attr("data-province");
var country = jQuery(data).attr("data-country");
var zip = jQuery(data).attr("data-zip");
var hubbedlb = jQuery(data).attr("data-hubbedlb");

jQuery.ajax({
        type : "post",
        dataType : "json",
        url : hubbed_ajax.ajaxurl,
        data : {action: "hubbed_selected_address", company:company, address:address,address2:address2,city:city,state:state, country:country,zip:zip,hubbedlb:hubbedlb },

        success: function(response) 
        {
        		LoaderHide();
        	var responsedata = JSON.stringify(response);
        	window.location.href=response.checkout_url;
        }
    })

};

jQuery(document).on('click', '.select-address', function()
{
	hubbedselectAddress(this);

});	



jQuery(document).on('click', '#cross_hubbed_checkout_address', function()
{


jQuery.ajax({
        type : "post",
        dataType : "json",
        url : hubbed_ajax.ajaxurl,
        data : {action: "removed_hubbed_address"},

        success: function(response) 
        {
          jQuery('.hubbed_checkout_address').remove();
         // jQuery("#ship-to-different-address-checkbox"). prop("checked", false);


}
});
});
function scrollToElm(container, elm, duration){
  var pos = getRelativePos(elm);
  scrollTo( container, pos.top , 2);  // duration in seconds
}

function getRelativePos(elm){
  var pPos = elm.parentNode.getBoundingClientRect(), // parent pos
      cPos = elm.getBoundingClientRect(), // target pos
      pos = {};

  pos.top    = cPos.top    - pPos.top + elm.parentNode.scrollTop,
  pos.right  = cPos.right  - pPos.right,
  pos.bottom = cPos.bottom - pPos.bottom,
  pos.left   = cPos.left   - pPos.left;

  return pos;
}
function easeInOutQuad(t){ return t<.5 ? 2*t*t : -1+(4-2*t)*t };
function scrollTo(element, to, duration, onDone) {
    var start = element.scrollTop,
        change = to - start,
        startTime = performance.now(),
        val, now, elapsed, t;

    function animateScroll(){
        now = performance.now();
        elapsed = (now - startTime)/1000;
        t = (elapsed/duration);

        element.scrollTop = start + change * easeInOutQuad(t);

        if( t < 1 )
            window.requestAnimationFrame(animateScroll);
        else
            onDone && onDone();
    };

    animateScroll();
}
jQuery(document).on('click',".hubbed-modal-overlay",function(){
	jQuery(".hubbed-modal").removeClass("active");
	jQuery(".hubbed-modal").removeClass("hubbed-is-visible");
	jQuery('body').removeClass('hubbed-scrollfix');
})
document.querySelector(".learn-more-overlay").addEventListener("click", function(event) {
	closeMyLearnMoreHubbed(event);
});
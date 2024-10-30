<div class="hubbed-modal">
   <div class="hubbed-modal-overlay hubbed-toggle"></div>
   <div id="hubbedModelLoaderBackgroud" class="hubbed-loader-backgroud" style="display:none;">
      <div id="hubbedModalLoader" class="hubbedModalLoader"></div>
   </div>
   <div class="hubbed-center">
      <div class="hubbed-modal-wrapper">
         <div class="hubbed-modal-inside-wrapper">
            <div class="hubbed-close hubbed-toggle" id="hubbed_span_clear_search">
               <svg fill="#000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve"> <g> <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88 c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242 C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879 s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/> </g> </svg>
            </div>
            <div class="hubbed-first-phase">
               <div id="hubbed-model-one" class="hubbed-model-one">
                  <div class="hubbed-logo" id="hubbed-logo">
                     <img src="https://apps.hubbed.com.au/assets/images/hubbed-small-white-logo.png">
                  </div>
                  <h2 class="hubbed-big-heading" id="hubbed-big-heading">Select a Collection Point</h2>

                  <div class="hubbed-search-location">
                     <div class="hubbed-search-relative">
                        <a href="#" class="hubbed-pin-location"><?php echo file_get_contents(Hubbed_URL."assets/front/pinicon.svg"); ?>
                        </a>
                        <input autocomplete="off" type="text" name="postcode_search" id="hubbed-search-field-one" placeholder="Enter your postcode or suburb" class="hubbed-search-field hubbed-search-field-one hubbed-form-field-text">
                        <?php wp_nonce_field( 'search_nonce_postcode', 'search_nonce_postcode' ); ?>
                        <a href="#" class="hubbed-input-clear" id="hubbed-input-clear">
                        <img src="https://apps.hubbed.com.au/storage/wp/assets/images/field-clear-icon.svg">
                        </a>                                    
                        <span class="hubbed-search-button" id="hubbed-search-button-first"><?php echo file_get_contents(Hubbed_URL."assets/front/search.svg"); ?></span>

                     </div>
                     <p id="hubbedErrMsg"></p>
                  </div>
               </div>
            </div>



         <div class="hubbed-second-phase" >
            
            <div class="hubbed-search-location">
               <img src="https://apps.hubbed.com.au/assets/images/hubbed-small-white-logo.png" class="hubbed-search-location-logo" alt="hubbed logo">
               <div class="hubbed-search-relative"> 
                  <span class="hubbed-pin-location"><?php echo file_get_contents(Hubbed_URL."assets/front/pinicon.svg"); ?></span> 
                  <input autocomplete="off" type="text"  id="hubbed-search-field-two" name="Search" placeholder="Enter your postcode or suburb" class="hubbed-search-field hubbed-search-field-two hubbed-form-field-text"> 
                  <a href="javascript:void(0)" class="hubbed-input-clear" id="hubbed-input-clear"> </a> 
                  <span class="hubbed-search-button" id="hubbed-search-button-second"><?php echo file_get_contents(Hubbed_URL."assets/front/search.svg"); ?></span>
               </div>
               <p id="hubbedErrMsg"></p>
               <div id="hubbed_display_filters" class="hubbed-filter-part">
                       <?php
                        $radius_data = array(
                           'store_id'=> (get_option('hubbed_store_id')),        
                        );
                        $response_radius = hubbed_api_call('/storeFilter', $radius_data);
                        $radiuses = $response_radius['filtes']['Search_Radius'];
                     ?>

                     <div class="hubbed-radius-km"> 
                       <label class="hubbed-label hubbed-h4-heading">Search Radius</label>
                       <?php
                       foreach ($radiuses as $radius) 
                       {
                         $checked="";
                         if (Hubbed_DEFAULT_RADIUS == $radius) {
                           $checked = "checked";
                         }

                         echo '<label class="hubbed_label_value hubbed-checkbox">'.$radius.'KM<input type="radio" name="hubbed_radius" class="hubbed_radius hubbed-radio-field" value="'.$radius.'"'.$checked.' > <span class="hubbed-checkmark"></span> </label> ';
                       }
                       ?>
                     </div>
                     <?php
                        $services = $response_radius['filtes']['Services'];
                        $channels = $response_radius['filtes']['Channels'];
                        if (!empty($services) && !empty($channels)){
                           echo '<div class="hubbed-filter-dropdown">';
                           if (!empty($services)){
                                 echo '<select class="hubbed_filter_servises" name="hubbed_filter_service">';
                                 echo '<option value=""> Select Services </option>';
                                 foreach ($services as $service) {
                                    echo '<option value="'.$service['id'].'">'.$service['name'].'</option>';  
                                 }
                                 echo '</select>';
                           }
                           if (!empty($channels)) {
                              echo '<select class="hubbed_filter_channel" name="hubbed_filter_channel">';
                              echo '<option value=""> Select Channels </option>';
                              foreach ($channels as $channel){
                                 echo '<option value="'.$channel['channel_id'].'">'.$channel['channel_name'].'</option>';  
                              }
                              echo '</select>';
                           }
                     ?>
                           <div class="hubbed-filter-buton hubbed-same-width-dropdown">
                                <button class="hubbed-applay-filter hubbed-button-same" id="hubbed-filter-btn" >Filter</button>
                                <button class="hubbed-clear-filter hubbed-link-button" id="hubbed-filter-clear">Clear Filter</button>
                           </div>
                       </div>
                  <?php }?>
               </div>

               <div class="explore-toggles">
                  <button type="button" class="search-button-toggle active" id="hubbed-explore-toggle-map" onclick="showhidemap(1)">Map</button>
                  <button type="button" class="search-button-toggle" id="hubbed-explore-toggle-list" onclick="showhidemap(0)">List</button>
               </div>
            </div>
            <div class="search-map-list-part">
               <!--- map div -->
                  <div id="hubbed-map"></div>
               <!--- map div -->
               <div class="hubbed-search-result" style="display: block;">
                  <div class="hubbed-search-address-part">
                     <div id="hubbed_display_response" class="hubbed-show-list">
                        <div id="hubbed_display_records"></div>
                        <div id="hubbed_display_showmorebtn"></div>
                     </div>
                  </div>
               </div>
            </div>
           
           </div>  
         </div>
         <!-- / hubbed-modal-wrapper-->                              
      </div>
      <!-- / hubbed center-->                      
   </div>
</div>
<div class="hubbed-learn-more-modal">
<?php 
$closeIcon = '<svg fill="#000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve"> <g> <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88 c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242 C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879 s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/> </g> </svg>';?>
   <div class="learn-more-overlay"></div>
   <div class="learn-more-sub-modal">
      <div class="learn-more-hubbed-close" onclick="closeMyLearnMoreHubbed(this)"><?php echo $closeIcon;?></div>
      <span class="learn-more-hubbed-title">Great Choice!</span>
      <span class="learn-more-hubbed-sub-title">By choosing <strong>HUBBED</strong> click & collect you can feel good about choosing a greener delivery option.</span>
      <span class="learn-more-hubbed-description">This is because by choosing to have your parcel delivered to a <strong>HUBBED</strong> location, you are helping to reduce carbon emissions. Couriers spend less time on the road by delivering multiple parcels to a <strong>HUBBED</strong> location vs door to door deliveries. You're also most likely picking up your parcel as part of an existing trip.</span>
      <span class="learn-more-hubbed-description"><strong>HUBBED</strong> has been certified by the Carbon Reduction Institute as helping to reduce carbon emissions by almost 50%. Learn more about <strong>HUBBED</strong> by visiting <a href="https://www.hubbed.com" target="blank">hubbed.com</a></span>
      <img class="learn-more-hubbed-logo" src="https://apps.hubbed.com.au/assets/images/hubbed-small-white-logo.png" alt="hubbed logo" />
   </div>
</div>
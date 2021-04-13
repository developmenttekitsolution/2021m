<?php
/**
 * Template Name: Listing With Map - Split Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Listeo
 */
get_header('split');?>
<div class="fs-container">

	<div class="fs-inner-container content">
		<div class="fs-content">
			<?php //echo do_shortcode('[listing-category]'); ?>

			<!-- Search -->

			<section class="search">
				<a href="#" id="show-map-button" class="show-map-button" data-enabled="<?php  esc_attr_e('Show Map ','listeo'); ?>" data-disabled="<?php  esc_attr_e('Hide Map ','listeo'); ?>"><?php esc_html_e('Show Map ','listeo') ?></a>
				<div class="row">
					<div class="col-md-12">
						<?php echo do_shortcode('[listeo_search_form source="half" more_custom_class="margin-bottom-30"]'); ?>
						
					</div>
				</div>

			</section>
			<!-- Search / End -->

			<section class="listings-container margin-top-30">
				

				<!-- Listings -->
				<div class="row fs-listings">
					<?php
						while ( have_posts() ) : the_post();?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-12'); ?>>
								<?php the_content(); ?>
							</article>
					<?php endwhile;   ?>
					<div class="col-md-12">
						<div class="copyrights margin-top-0"><?php $copyrights = get_option( 'pp_copyrights' , '&copy; Theme by Purethemes.net. All Rights Reserved.' ); 
			
					        
					            echo wp_kses($copyrights,array( 'a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),));
					         ?></div>
						</div>
					</div>
			</section>
			<!-- prachi added code here starts -->
			 
			    <div class="modal popup-overlay">
			        
			       <div class="modal-container">
			         <div class="modal-header">
			           <div class="modal-title"><?php esc_html_e('Send Message', 'listeo_core'); ?></div>
			           <!-- <span class="close">X</span> -->
                 <button title="Close (Esc)" type="button" class=" close mfp-close msgvenderclose" style="color: #807171 !important;"></button>
			          </div>
			          <div class="modal-content">
			              <?php if( is_user_logged_in() ) {
		                    ?>
                        <!-- <p>&nbsp;</p> -->
                         
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                    <div class="mesage_dialog">
		                        <div class="message-reply margin-top-0">
		                          <form action="javascript:void(0)" class="message_form">
		                            <div style="display: none;" class="notification closeable success margin-top-20"></div>
		                            <textarea cols="40"  class="custommessage" name="message" rows="3" placeholder="<?php esc_attr_e('Your message','listeo_core'); ?>" ></textarea>
		                            <button   class="button msg_sendbtn">
		                              
		                            <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i><?php esc_html_e('Send Message', 'listeo_core'); ?></button> 
		                           
		                           <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
		                          </form>
		                  
		                        </div>
		                      </div>
		                      <?php
		                  }else{
								?>
								<div style="margin-top:5px;" class="new-list-btn unverify_listing_btn">
									<a id="unverify_listing_msg_btn" href="#sign-in-dialog" class="send-message-to-owner button popup-with-zoom-anim listeo_list_provider_meg_btn" style="display:inline-block;">
										Message Vendor				</a>
								</div>
								<?php
							} ?>
			          </div>
			       </div>
			      
			      </div>
			<!-- prachi added code here ends -->
		</div>
	</div>
	<div class="fs-inner-container map-fixed">

		<!-- Map -->
		<div id="map-container" class="">
		    <div id="map" class="split-map" data-map-zoom="<?php echo get_option('listeo_map_zoom_global',9); ?>" data-map-scroll="true">
		        <!-- map goes here -->
		    </div>
		   
		</div>
 		
	</div>
</div>

<div class="clearfix"></div>

<?php get_footer('empty'); ?>
<!-- prachi added code here starts -->
<style>
.msgvenderclose{
  top: -3px !important;
  right: 10px !important;

}
.container-modal { 
  position: relative;
  display: block;
  float: left;
  width: 25%;
  height: 200px;
}

.title { 
  width: 200px;
  padding: 20px 0;
  background: #000;
  color: #fff;
  text-align: center;
  border-radius: 40px;
  cursor: pointer;
}


body.lorem { 
  position: fixed; 
  width: 100%; 
  height: 100%; 
  overflow: hidden; 
  
}

.modal { 
  display: none; 
  position: fixed;
  top: 77px; 
  left: 0; 
  width: 100%; 
  height: 100%; 
  background: rgba(0, 0, 0, 0.7);
  z-index: 999;
}

.modal.on { 
  display: block;
  
}

.modal-container { 
  position: relative;
  display: block; 
  top: 25%;
  width: 500px;
  margin: 0 auto;
  animation-name: bounceIn;
  animation-duration: 1s;
  animation-fill-mode: both;
}

.modal-container .modal-header {
  position: relative; 
  display: block;
  border-radius: 5px 5px 0px 0px;
  background:#f6f6f6; 
}

.modal-container .modal-header .modal-title {
  font-family: 'Roboto', sans-serif; 
  font-size: 30px; 
  line-height: 1.2; 
  
  font-weight: bold;
  margin-left: 26px;
  padding: 20px 0;
}

.modal-container .modal-header span.close {
  position: absolute; 
  display: block;
  width: 52px; 
  height: 52px; 
  top: 12px; 
  right: 30px;
  cursor: pointer;
}

.modal-container .modal-content { 
  position: relative; 
  display: block; 
  background-color: #fff;
  overflow-y: auto; 
  height: auto;
  border-radius: 0px 0px 5px 5px;
}
@keyframes bounceIn {
  from, 20%, 40%, 60%, 80%, to {
    animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
  }

  0% {
    opacity: 0;
    transform: scale3d(.3, .3, .3);
  }

  20% {
    transform: scale3d(1.1, 1.1, 1.1);
  }

  40% {
    transform: scale3d(.9, .9, .9);
  }

  60% {
    opacity: 1;
    transform: scale3d(1.03, 1.03, 1.03);
  }

  80% {
    transform: scale3d(.97, .97, .97);
  }

  to {
    opacity: 1;
    transform: scale3d(1, 1, 1);
  }
}
.mesage_dialog{
  width: 80%;
  margin:auto;
}
.modal-content{
  text-align: center;
}
@media (max-width: 650px) {
  .modal-container {
    width: 100%;
  }
}
</style> 
<script>
	// $('.container-modal .title').each(function (idx, item) {
 //    var winnerId = "winner-" + idx;
 //     this.id = winnerId;
 //     $(this).click(function(){
 //       var btn = $("#winner-" + idx);
 //       var span = $(".close");
 //       var popId = $('#win-'+ idx);
 //       btn.click(function() {
 //          $(popId).addClass('on');
 //          $('body').addClass('lorem');
 //        }); 
 //        span.click(function() {
 //           $(popId).removeClass('on');
 //           $('body').removeClass('lorem');
 //         });
       
        
     
 //     });
 // });
	function open_popup(pid,authid,gid) {
		
		$('.popup-overlay').attr("id", "popoverlay"+pid);
		$('.popup-content').attr("id", "popcontent"+pid);
		$('.mesage_dialog').attr("id", "small-dialog"+pid);
		// set the message tag attributes starts here
		$('.message-reply').attr("id", "unverify_listing_msg_form"+pid);
		$('.message_form').attr("id", "send-message-from-widget"+pid);
		$('.message_form').attr("data-listingid", pid);

		$('.custommessage').attr("id", "contact-message"+pid);
		$('.custommessage').attr("data-recipient",authid);
		$('.custommessage').attr("data-referral", pid);
		$('.msg_sendbtn').attr("id", "send_unverify_listing_msg_btn"+pid);
		$('.msg_sendbtn').attr("data-listing_id", gid);
		$('.msg_sendbtn').attr("onclick", "sendmsg("+pid+")");
		$('.notification').html('');
    if ($('.msg_sendbtn').attr("disabled")) {
             $('.msg_sendbtn').prop("disabled", false);

          }
    $('.custommessage').prop('required',true);
		// set the message tag attributes ends here

		$("#popoverlay"+pid).addClass('on');
  		$('body').addClass('lorem');
  	
  		$(".close").click(function() {
           $("#popoverlay"+pid).removeClass('on');
           $('body').removeClass('lorem');
         });
		// $('#popup-close').attr("onclick", "close_popup("+pid+")");
	
  //       $("#popoverlay"+pid+", #popcontent"+pid).addClass("active");
      }
  function close_popup(pid) {

        $("#popoverlay"+pid).removeClass('on');
        $('body').removeClass('lorem');
      }
  function sendmsg(pid){
  	
     jQuery('#send_unverify_listing_msg_btn'+pid).addClass('loading').prop('disabled', true);

        //var message = jQuery("#unverify_listing_msg_form"+pid).find("#contact-message"+pid).val();
        var message = jQuery("#contact-message"+pid).val();
        
        var listing_id = jQuery('#contact-message'+pid).data("referral");
        console.log(listing_id);
        var recipient = jQuery('#contact-message'+pid).data("recipient");
        var ajax_file_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        if ($.trim(message) !=='') {
            jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_file_url,
            data: {"action": "listeo_send_message", message: message, referral: listing_id,recipient:recipient },
            success: function(data){
            jQuery('#unverify_listing_msg_form'+pid+' .custommessage').val('');
                if(data.type == 'success')
                {
                  jQuery('#send_unverify_listing_msg_btn'+pid).removeClass('loading');
                        jQuery('#unverify_listing_msg_form'+pid+' .notification').removeClass('error').addClass('success').show().html('Your message is successfully sent.');
              
                }
                else {
                  jQuery('#unverify_listing_msg_form'+pid+' .notification').removeClass('success').addClass('error').show().html("Your message couldn't send, please try again.");
                        jQuery('#send_unverify_listing_msg_btn'+pid).removeClass('loading').prop('disabled', false);

                }
              }
          });
        }else{
          jQuery('#unverify_listing_msg_form'+pid+' .notification').removeClass('success').addClass('error').show().html("Your message couldn't send, message can not be empty.");
                        jQuery('#send_unverify_listing_msg_btn'+pid).removeClass('loading').prop('disabled', false);
        }
        
  }
</script>
<!-- prachi added code here starts -->
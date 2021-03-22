<?php 
add_action( 'wp_enqueue_scripts', 'listeo_enqueue_styles');
function listeo_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css',array('bootstrap','listeo-icons','listeo-woocommerce') ); 
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/cristian_style.css');
    //wp_enqueue_style( 'child-style-2', get_stylesheet_directory_uri() . '/css/sahil_style.css');
    
} 
// add_action( 'wp_enqueue_scripts', 'listeo_cristian_behind_scripts', 9999);
add_action( 'wp_head', 'listeo_cristian_behind_scripts', 9999);
function listeo_cristian_behind_scripts() {
	//dequeue frontend js because send message with widget has error
	//wp_dequeue_script('listeo_core-frontend');
    //wp_deregister_script('listeo_core-frontend');
    //wp_register_script( 'listeo_core-frontend', get_stylesheet_directory_uri() . '/js/frontend.js', array( 'jquery' ));
	//wp_enqueue_script('listeo_core-frontend');
    
	// wp_dequeue_script('daterangerpicker');
 //    wp_deregister_script('daterangerpicker');
    // wp_register_script( 'daterangerpicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', array( 'jquery','moment' ) );
	// wp_enqueue_script('daterangerpicker');  
    
	wp_register_script( 'cristian_script', get_stylesheet_directory_uri() . '/js/cristian_script.js', array( 'jquery' ));
	wp_enqueue_script('cristian_script');
} 

function remove_parent_theme_features() {
   	
}
add_action( 'after_setup_theme', 'remove_parent_theme_features', 10 );

function listing_category_slider(){
	$termArray = get_terms( array(
	    'taxonomy' => 'listing_category',
	    'hide_empty' => false,
	) );
	$html  = '<link rel="stylesheet" href="'.site_url() . '/wp-content/themes/listeo-child/css/flexslider.css"/>
				<script type="text/javascript" src="'.site_url(). '/wp-content/themes/listeo-child/js/jquery.flexslider-min.js"></script>	
	<div class="flexslider">
  			<ul class="slides">';
	foreach ($termArray as $singleTerm) {
		$metaData = get_term_meta($singleTerm->term_id);
		$coverImageID = $metaData['_cover'][0];
		$coverImage = wp_get_attachment_image_src($coverImageID, array('784','500'));
		if ($coverImage) : 
			$html .='<li><img src="'.$coverImage[0].'" /></li>';		
		endif; 
	}
	
	$html .='</ul></div>';
	$html .= '<script>
jQuery(window).load(function() {
  jQuery(".flexslider").flexslider({
    animation: "slide",
    controlNav: false
  });
});</script>';
	return $html;
}
add_shortcode( 'listing-category', 'listing_category_slider' );
if (!is_admin()) {
    add_filter( 'script_loader_tag', function ( $tag, $handle ) {    
        if ( strpos( $tag, "jquery-migrate.min.js" ) || strpos( $tag, "jquery.js") ) {
            return $tag;
        }
        return str_replace( ' src', ' defer src', $tag );
    }, 10, 2 );

}

function mz_footer(){
	
	if(isset($_GET['page_id']) && $_GET['page_id'] == 71){
		
	?>

	<script>
		
		jQuery(document).ready(function(){

			setTimeout(function(){

				jQuery('p#_gallery-description').html('Photo are the first thing that guests will see. We recommend adding 10 or more high quality photos.<br>Photo requirments:<br><ul><li>High resolution - Atleast 1,000 pixels wide</li><li>Horizontal orientation - No vertical photos</li><li>Color photos - No block & white</li><li>Mics. - No collages, screenshots, No watermarks</li></ul>');

			},200);

		});

	</script>

<?php
		
	}

	if(is_page(66)){
	    ?>
	    <script>
	        jQuery(document).ready(function() {
	        	if(jQuery(".message-content").length){
				    jQuery(".message-content").animate({ 
				        scrollTop: jQuery('.message-content').get(0).scrollHeight 
				    }, 2000);
				}
			});
	    </script>
	    <?php
	}
}

add_action('wp_footer','mz_footer');

function whero_limit_image_size($file) {

	// Calculate the image size in KB
	$image_size = $file['size']/1024;

	// File size limit in KB
	$limit = 200;

	// Check if it's an image
	$is_image = strpos($file['type'], 'image');

	if ( ( $image_size > $limit ) && ($is_image !== false) )
        	$file['error'] = 'Your picture is too large. It has to be smaller than '. $limit .'KB';

	return $file;

}
//add_filter('wp_handle_upload_prefilter', 'whero_limit_image_size');


add_action("widgets_init","register_unveryfie_siderbar");
    function register_unveryfie_siderbar()
    {
      register_sidebar(array(
      'name' => 'Single Unveryfie Listing Sidebar',
      'id' => 'single_unveryfie_siderbar',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h3>',
      'after_title' => '</h3>'
       ));
    }
// // keep users logged in for longer in wordpress
// function wcs_users_logged_in_longer( $expirein ) {
//     // 1 month in seconds
//     return 2628000;
// }
// add_filter( 'auth_cookie_expiration', 'wcs_users_logged_in_longer' );



function my_new_custom_cron_schedule( $schedules ) {
    $schedules['every_day_10_minutes'] = array( 
        'interval' => 60, // Every 6 hours
        'display'  => __( 'Every 10 Minutes' ),
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'my_new_custom_cron_schedule' );

//Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'my_new_cron_hook' ) ) {
    wp_schedule_event( time(), 'every_day_10_minutes', 'my_new_cron_hook' );
}

///Hook into that action that'll fire every six hours
add_action( 'my_new_cron_hook', 'my_new_cron_function' );

//create your function, that runs on cron
function my_new_cron_function() {
    global $wpdb;
	$now_temp_time = current_time('timestamp');
	$results = $wpdb -> get_results( "SELECT * FROM `" . $wpdb->prefix . "listeo_core_messages` 
		WHERE  reminded_10 = 0 && created_at+10*60<".$now_temp_time."");
	$message_list = '';
	$_list_options = array ();   

	if(!empty($results)){
		foreach( $results as $result ) {
			$to="";
			
			$results_conversations = $wpdb -> get_results( "SELECT user_1, user_2 FROM `" . $wpdb->prefix . "listeo_core_conversations` WHERE  id = $result->conversation_id");
			$remind_receiver_id = 0;
			foreach( $results_conversations as $result_conversations ) {
				if($result_conversations->user_1==$result->sender_id) {
					$remind_receiver = get_userdata($result_conversations->user_2);
					$remind_sender = get_userdata($result_conversations->user_1);
					$remind_receiver_id = $result_conversations->user_2;
				}
				else {
					$remind_receiver = get_userdata($result_conversations->user_1);
					$remind_sender = get_userdata($result_conversations->user_2);
					$remind_receiver_id = $result_conversations->user_1;
				}
			}
			
			//print_r($remind_receiver);

			$update_result  = $wpdb->update( 
				$wpdb->prefix . 'listeo_core_messages', 
				array( 'reminded_10' => 1 ),
				array( 'id' => $result->id ) 
			);
			
			
			
			if(!empty($_list_options[$remind_receiver_id])){
				
				$_list_options[$remind_receiver_id]['message'] .= '<p style="color: blue">'.$result->message.'</p><br/><hr><br/>';
			$reply_to = $result->conversation_id.'__'.$result->sender_id;
				
			}else{
			
				$_list_options[$remind_receiver_id] = array(
												'remind_receiver' => $remind_receiver->data,
												'remind_sender' => $remind_sender->data,  
												'reply_to' => $reply_to,
												'subject' => $subject,
												'conversation_id' => $result->conversation_id,
												'sender_id' => $result->sender_id,
												'message' => '<br/><hr><p style="color: blue">'.$result->message.'</p><br/><hr><br/>'
											);
			}

		}
		//mail("shankhab@ghrix.com","My subject",print_r($_list_options,true));  
		
		$subject = 'Reminder Of Unread Messages';
		$message = '';
		$conversation_id = '';
		$display_name = '';
		$user_email = '';
		$reply_to = '';
		if(!empty($_list_options)){
			foreach($_list_options as $key => $_listoptions){
				$message = $_listoptions['message'];
				$conversation_id = $_listoptions['conversation_id'];
				$display_name = $_listoptions['remind_receiver']->display_name;
				$user_email = $_listoptions['remind_receiver']->user_email;
				//$user_email = 'shankhab@ghrix.com';
				$reply_to = $_listoptions['conversation_id'].'__'.$_listoptions['sender_id'];
				
				
				$body = '<div>'.
							'<b>'.$display_name.'</b> is waiting for your response for over 10 Minutes.<br/><br/><br/>'.
							'New messages:<br/>'.
							$message
							.'<div style="text-align: center">
								<a href="https://hypley.com/ravi/messages/?action=view&conv_id='.$conversation_id.'">
									<button style="background: #0088cf; color: white;padding: 10px 30px;border: none;font-weight: 600; font-size: 20px;">
									Go to message box
									</button>
								</a>
							</div><br/>'.
							'<p> Or send a message to <b>'.$display_name.'<b> by replying to this email. </p>'.
						'</div>';
				$reply_to = $reply_to;							
				sendNew( $user_email, $subject, $body ,'', $reply_to);
				
			}
		}
				
	}
        return $update_result;
}

function sendNew( $emailto, $subject, $body , $activation_link='', $reply_to=''){

	$from_name 	= get_option('listeo_emails_name',get_bloginfo( 'name' ));
	$from_email = get_option('listeo_emails_from_email', get_bloginfo( 'admin_email' ));
	$headers 	= sprintf( "From: %s <%s>\r\n Content-type: text/html; charset=UTF-8\r\n", $from_name, $from_email );
	if($reply_to != ''){
		$headers .='Reply-To: '.$reply_to.' <cristian@hypley.com>';
	}

	if( empty($emailto) || empty( $subject) || empty($body) ){
		return ;
	}
	
	ob_start();
		?>
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="content-type" content="text/html; charset=utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0;">
				<meta name="format-detection" content="telephone=no"/>
				<style>
					/* Reset styles */ 
					body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
					body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
					table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; width: 100%; }
					img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
					#outlook a { padding: 0; }
					.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
					.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
					.container { border-radius: 0px; box-shadow: 0 0 18px rgba(0,0,0,0); }
					.paragraph { line-height: 26px !important; }
					/* Rounded corners for advanced mail clients only */ 
					@media all and (min-width: 560px) {
						.container { border-radius: 4px;}
						table, td { width: 560px; }
						.container {box-shadow: 0 0 18px rgba(0,0,0,0.05);}
					}

					/* Set color for auto links (addresses, dates, etc.) */ 
					a, a:hover {
						color: #127DB3;
					}
					.footer a, .footer a:hover {
						color: #999999;
					}

				</style>


			</head>

			<!-- BODY -->
				<!-- Set message background color (twice) and text color (twice) -->
				<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;
					background-color: #f6f6f6;
					color: #666666;"
					bgcolor="#f6f6f6"
					text="#000000">

				<!-- SECTION / BACKGROUND -->
				<!-- Set message background color one again -->
				<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
					bgcolor="#f6f6f6">
				<tr><td>
				<!-- WRAPPER -->
				<!-- Set wrapper width (twice) -->
				<table border="0" cellpadding="0" cellspacing="0" align="center"
					width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
					max-width: 560px;" class="wrapper">

					<tr>
						<td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
							padding-top: 20px;
							padding-bottom: 20px;">

							<!-- LOGO -->
							<!-- Image text color should be opposite to background color. Set your url, image src, alt and title. Alt text should fit the image size. Real image size should be x2. URL format: http://domain.com/?utm_source={{Campaign-Source}}&utm_medium=email&utm_content=logo&utm_campaign={{Campaign-Name}} -->
						<?php 
						$logo = get_option( 'pp_logo_upload', '' ); 
						if($logo) { ?>
							<img border="0" vspace="0" hspace="0"
								src="<?php echo get_option( 'pp_logo_upload', '' );  ?>"
								alt="<?php  bloginfo( 'name' ); ?>" title="<?php  bloginfo( 'name' ); ?>" style="
								color: #333;
								max-height: 50px; transform: translate3d(0,0,0);
								font-size: 10px; margin: 10px 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;" />
						<?php } else { 
							echo "<h3>"; bloginfo( 'name' ); echo "</h3>"; 
						} ?>
						</td>
					</tr>

				<!-- End of WRAPPER -->
				</table>

				<!-- WRAPPER / CONTEINER -->
				<!-- Set conteiner background color -->
				<table border="0" cellpadding="0" cellspacing="0" align="center"
					bgcolor="#FFFFFF"
					width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
					max-width: 560px;" class="container">
			<tr>
				<td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 25px; padding-right: 25px; padding-bottom: 28px; width: 87.5%; font-size: 16px; font-weight: 400; 
				padding-top: 28px; 
				color: #666;
				font-family: sans-serif;" class="paragraph">
				<?php 
					echo $body;
				?>
				</td>
			</tr>
			<!-- End of WRAPPER -->
	</table>

	<!-- WRAPPER -->
	<!-- Set wrapper width (twice) -->
	<table border="0" cellpadding="0" cellspacing="0" align="center"
		width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
		max-width: 560px;" class="wrapper">

		<!-- PARAGRAPH -->
		<!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->
		<tr>
			<td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 16px; font-weight: 400; line-height: 160%;
				padding-top: 20px;
				padding-bottom: 25px;
				color: #777;
				font-family: sans-serif;" class="paragraph">
					<?php esc_html_e('Have a question?','listeo_core'); ?> <a href="mailto:<?php echo get_option('listeo_emails_from_email', get_bloginfo( 'admin_email' )); ?>" target="_blank" style="color: #127DB3; font-family: sans-serif; font-size: 16px; font-weight: 400; line-height: 160%;"><?php echo get_option('listeo_emails_from_email', get_bloginfo( 'admin_email' )); ?></a>
			</td>
		</tr>
	<!-- End of WRAPPER -->
	</table>

	<!-- End of SECTION / BACKGROUND -->
	</td></tr></table>

	</body>
	</html>
	<?php
		$content = ob_get_clean();

	wp_mail( @$emailto, @$subject, @$content, $headers );

}


//echo '<pre>'; print_r($_list_options); print_r($results); echo '</pre>';






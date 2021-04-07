
<?php 
   if(isset($data)) :
       $style        = (isset($data->style)) ? $data->style : '' ;
       $grid_columns        = (isset($data->grid_columns)) ? $data->grid_columns : '2' ;
   endif; 
   
   $template_loader = new Listeo_Core_Template_Loader;
   $listing_type = get_post_meta( $post->ID,'_listing_type',true ); 
   $is_featured = listeo_core_is_featured($post->ID);
   ?>
<!-- Listing Item -->
<?php if(isset($style) && $style == 'grid') {
   if($grid_columns == 2 ) : ?>
<!-- <div class="col-lg-6 col-md-12">  -->
<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
<?php else: ?>
<div class="col-lg-4 col-md-6">
<?php endif ?>
<?php 
   } 
   // if get_the_listing_price_range has "starts from" string, than delete it
   $cristian_min_price = (get_the_listing_price_range() && (strpos(get_the_listing_price_range(), 'Starts from') !== false))?substr(get_the_listing_price_range(),11):"$ 0";
   ?>
<div class="listing-item-container listing-geo-data listo-main-box-sec"  <?php echo listeo_get_geo_data($post); ?> data-minPrice="<?=$cristian_min_price?>">
   <!--<a href="<?php //the_permalink(); ?>" class="listing-item-container listing-geo-data"  <?php echo listeo_get_geo_data($post); ?>>-->
   <div class="listing-item listeo_grid_view_item listo-list-iteam <?php if($is_featured){ ?>featured-listing<?php } ?>">
      <div class="listing-small-badges-container listo-new-container">
         <?php if($is_featured){ ?>
         <div class="listing-small-badge featured-badge listeo-featured-listing-box">
            <!-- <i class="fa fa-star"></i> <?php esc_html_e('Featured','listeo_core'); ?> -->
            <?php esc_html_e('SUPERVENDOR','listeo_core'); ?>
          </div>
         <?php } ?>
         <?php  
            if( $listing_type  == 'event') { 
                
                $date_format = listeo_date_time_wp_format_php();
                $_event_datetime = get_post_meta($post->ID,'_event_date', true); // mm/dd/yy
                if($_event_datetime) {
                    $_event_date = list($_event_datetime) = explode(' -', $_event_datetime);
                
                    if($_event_date) : 
                        if(substr($date_format, 0, 1) === 'd'){
                            $_event_date[0] = str_replace('/', '-', $_event_date[0]);
                        }
                        ?>
                        <div class="listing-small-badge"><i class="fa fa-calendar-check-o"></i><?php echo esc_html(date($date_format, strtotime($_event_date[0]))); ?></div>
                    <?php endif;
                }
            }
            ?>
      </div>
      <?php 
         $template_loader->get_template_part( 'content-listing-image');  ?> 
      <?php
         if( $listing_type  == 'service' && get_post_meta( $post->ID,'_opening_hours_status',true )) { 
                 if( listeo_check_if_open() ){ ?>
      <div class="listing-badge now-open"><?php esc_html_e('Now Open','listeo_core'); ?></div>
      <?php } else { 
         if( listeo_check_if_has_hours() ) { ?>
      <div class="listing-badge now-closed"><?php esc_html_e('Now Closed','listeo_core'); ?></div>
      <?php } ?>
      <?php } 
         } 
         ?>
      <div class="listing-item-content listo-new-listing-iteam">
         <?php 
            $terms = get_the_terms( get_the_ID(), 'listing_category' );            
            if ( $terms && ! is_wp_error( $terms ) ) :  
                $main_term = array_pop($terms); ?>
         <!-- <span class="tag"><?php //echo $main_term->name; ?></span> -->
         <?php endif; ?>
         <?php
            if(!get_option('listeo_disable_reviews'))
            {
                 $rating = get_post_meta($post->ID, 'listeo-avg-rating', true); 
                    if(isset($rating) && $rating > 0 ) : $rating_type = get_option('listeo_rating_type','star');
                    if($rating_type == 'numerical') 
                    { ?>
         <div class="numerical-rating" data-rating="<?php $rating_value = esc_attr(round($rating,1)); printf("%0.1f",$rating_value); ?>">
            <?php 
               } 
               else 
               { 
                $number = listeo_get_reviews_number($post->ID);  
               ?>
            <div class="star-rating listo-new-star-rating" data-rating="1">
               <!-- cristian made change -->
               <h6><?php echo number_format( $rating,1 ); ?></h6>
               <div class="rating-counter">(&nbsp;<?= $number ?>&nbsp;)</div>
               <?php 
                  } 
                  ?>
            </div>
            <?php else: ?>
            <div class="star-rating listo-new-star-rating" >
               <!-- 
                  <div class="rating-counter"><span><?php esc_html_e('No reviews yet','listeo_core') ?></span></div>-->
            </div>
            <?php endif;
               } ?>
            <!-- <?php //if(get_the_listing_address()) { ?><span><?php //the_listing_address(); ?></span><?php //} ?> -->
            <h3 class="listeo_single_list_title listo-hed-h3-new <?php echo (isset($rating) && $rating > 0)?"":"full_width";?>">
              <a  href="<?php the_permalink(); ?>">
                <?php the_title(); ?> <?php if( get_post_meta($post->ID,'_verified',true ) == 'on') : ?><i class="verified-icon"></i><?php endif; ?>
              </a></h3>

            <p class="single_listing_description">
              <a  href="<?php the_permalink(); ?>" style="color: #707070;">
               <?php 
                  $content = get_the_content();
                  $content = strip_tags($content);
                  if(strlen($content)>165) echo $content = substr($content,0,165)."..."; 
                  else echo $content;       
                  ?>
                </a>
            </p>
         </div>
         <?php 
            if( listeo_core_check_if_bookmarked($post->ID) ) { 
                    $nonce = wp_create_nonce("listeo_core_bookmark_this_nonce"); ?>
         <span class="like-icon listeo_core-unbookmark-it liked listo-bookmark-icon-new"
            data-post_id="<?php echo esc_attr($post->ID); ?>" 
            data-nonce="<?php echo esc_attr($nonce); ?>" ></span>
         <?php } else { 
            if(is_user_logged_in()){ 
                $nonce = wp_create_nonce("listeo_core_remove_fav_nonce"); ?>
         <span class="save listeo_core-bookmark-it like-icon listo-bookmark-icon-new" 
            data-post_id="<?php echo esc_attr($post->ID); ?>" 
            data-nonce="<?php echo esc_attr($nonce); ?>" ></span>
         <?php } else { ?>
         <span class="save like-icon tooltip left listo-bookmark-icon-new"  title="<?php esc_html_e('Login To Bookmark Items','listeo_core'); ?>"  ></span>
         <?php } ?>
         <?php } ?>
        <!-- // developer added starts here --> 
         <span style=" font-size: 19px; position: absolute; z-index: 101111111111; text-align: center;right: 40px; bottom: -1px;cursor: normal;display: block;height: 36px; width: 34px; border-radius: 50%;transition: all 0.4s; color: #0bee0f;" ><a href="javascript:open_popup('<?php echo esc_attr($post->ID)?>','<?php echo $post->post_author ?>', '<?php echo esc_attr(get_the_ID()); ?>')" class="fa "  style="color: inherit;"> &#xf075;</a></span>
        <!-- // developer added end --> 
         <div class="listing-small-badge pricing-badge listo-new-badge">
            <!--<i class="fa fa-<?php echo esc_attr(get_option('listeo_price_filter_icon','tag')); ?>"></i>-->
            <a style="color: #000;" href="<?php the_permalink(); ?>">
            <?php 
               echo __('From ', 'listeo_core').' '.$cristian_min_price;
               // echo get_the_listing_regular_price();
               ?>
              </a>
         </div>
         <?php //if(get_listing_bookmarks_number()>0)echo " <span class='bookmarks_num'>( ".get_listing_bookmarks_number()." likes )</span>";?>
      </div>
   </div>
   <!--</a>-->
   <?php if(isset($style) && $style == 'grid') { ?>
</div>
<?php } ?>
<!-- Listing Item / End -->

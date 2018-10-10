<?php
 /*Template Name: Single gallery Template
 */
get_header();
if( post_password_required() ):
    echo get_the_password_form();
else:
?> 	
	<div class="demo-gallery">
		<div id="container" class="list-unstyled row mycontainer" data-masonry='{ "itemSelector": ".item", "columnWidth": 200 }'>
			<?php
				$mypost = array( 'post_type' => 'vc_gallery', 'p' => get_the_ID() );
				$loop = new WP_Query( $mypost );
				while ( $loop->have_posts() ) : $loop->the_post(); 
					if ( get_post_gallery() ) {
						$gallery        = get_post_gallery( get_the_ID(), false );
						$galleryIDS     = $gallery['ids'];
						$pieces         = explode(",", $galleryIDS);
						foreach ( $pieces as $key => $value ) { 

							 $image_medium   = wp_get_attachment_image_src( $value, 'medium' );
							
							 $image_full     = wp_get_attachment_image_src( $value, 'full' ); ?>
							<div class="col-xs-6 col-sm-4 col-md-4 item" data-responsive="<?php echo $image_medium[0]; ?> 375, <?php echo $image_medium[0]; ?> 480, <?php echo $image_medium[0]; ?> 800" data-src="<?php echo $image_full[0]; ?>" data-sub-html="<h4><?php the_title();?></h4>">
								<a href="">
									<img class="img-responsive image" src="<?php echo $image_medium[0]; ?>">
								</a>
							</div>
						 <?php
						}
					} 
				endwhile;	
			?>
		</div>
	</div>
<?php 
endif;
get_footer(); 
?>
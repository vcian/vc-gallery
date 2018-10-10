<?php
/*
Plugin Name: Easy Gallery Management - ( Password protected, Masonary layout, Share and download gallery image )
Plugin URI: https://wordpress.org/plugins/vc-gallery/
Description: Displays gallery in wordpress with Password protected, vc gallery, Masonary layout, Share and download gallery image.
Version: 1.0
Author: ViitorCloud
Author URI: http://viitorcloud.com/
License: GPLv2
*/


add_action('admin_menu', 'VCGL_welcome_menu');

function VCGL_welcome_menu() {
	add_dashboard_page('Gallery Plugin Dashboard', 'Welcome to Easy Gallery Management', 'read', 'welcome-vcgallery', 'VCGL_welcome_screen_function');
}
function VCGL_welcome_screen_function(){ ?>
	<div class="wrap about-wrap">
		
                <h1 style="font-size: 2.1em;"><?php printf(__('Welcome to Easy Gallery Management', 'vc-gallery')); ?></h1>

                <div class="about-text">
                   An easy way to display Gallery from backend with masonary layout and lightbox.
                    <img class="version_logo_img" src="<?php echo plugin_dir_url(__FILE__) . 'img/galleryicon.jpg'; ?>">
                </div>

                <?php
                $setting_tabs_wc = apply_filters('vc_setting_tab', array("about" => "Overview"));
                $current_tab_wc = (isset($_GET['tab'])) ? $_GET['tab'] : 'about';
                $aboutpage = isset($_GET['page'])
                ?>
                <h2 id="analytics-tab-wrapper" class="nav-tab-wrapper">
				
				<?php
				foreach ($setting_tabs_wc as $name => $label)
					echo '<a  href="' . home_url('wp-admin/index.php?page=welcome-vcgallery&tab=' . $name) . '" class="nav-tab ' . ( $current_tab_wc == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
				?>
				</h2>

                <hr/>
                <div class="return-to-dashboard">
				 <div class="changelog">
                </br>
                <div class="changelog about-integrations">
                    <div class="wc-feature feature-section col three-col">
                        <div>
                            <p class="gamc_overview"><?php _e('First free plugin which provides functionality to display gallery with password protected feature, masonary layout, share and download gallery image on social media using easy gallery management plugin.
.'); ?></p>
                            <p class="gamc_overview"><strong>Plugin Functionality: </strong></p> 
                            <div class="gamc_ul">
                                <ul>
                                    <li>Easy gallery setup from backend</li>
                                    <li>Create gallery by following below steps:<br>
									1) Create gallery from gallery menu from backend<br>
									2) Add [easygallerylist] shortcode in any post or page<br>
									</li>
                                </ul>
                            </div>

                            <p class="gamc_overview"><strong>Plugin Supports: </strong></p> 
                            <div class="gamc_ul">
                                <ul>
                                    <li>This plugin includes a functionality to display gallery with masonary layout, open image in slider</li>
                                    <li>This plugin includes a functionality to share image on social media, download image, zoom in zoom out image, copy image</li>
                                </ul>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            </div>
        </div>
<?php
}
register_activation_hook(__FILE__, 'VCGL_plugin_activate');
add_action('admin_init', 'VCGL_plugin_redirect');

function VCGL_plugin_activate() {
add_option('vcgl_plugin_do_activation_redirect', true);
}

function VCGL_plugin_redirect() {
if (get_option('vcgl_plugin_do_activation_redirect', false)) {
    delete_option('vcgl_plugin_do_activation_redirect');
    if(!isset($_GET['welcome-vcgallery']))
    {
        wp_redirect("index.php?page=welcome-vcgallery");
    }
 }
}

add_action( 'init', 'create_vc_gallery' );
function create_vc_gallery() {
    register_post_type( 'vc_gallery',
        array(
            'labels' => array(
                'name' => 'Gallery',
                'singular_name' => 'gallery',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Gallery',
                'edit' => 'Edit',
                'edit_item' => 'Edit Gallery',
                'new_item' => 'New Gallery',
                'view' => 'View',
                'view_item' => 'View Gallery',
                'search_items' => 'Search Gallery',
                'not_found' => 'No Gallery found',
                'not_found_in_trash' => 'No Gallery found in Trash',
                'parent' => 'Parent Gallery'
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-admin-media',
            'has_archive' => true
        )
    );
}


/*---------------------------------------------------- */
//Create custom template
/*---------------------------------------------------- */
add_filter( 'template_include', 'VCGL_include_template', 1 );

function VCGL_include_template( $template_path ) {
    if ( get_post_type() == 'vc_gallery' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-gallery.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-gallery.php';
            }
        }
    }
    return $template_path;
}


function VCGL_list_Shortcode( $atts ) {
	ob_start();
	$loop = new WP_Query( array( 'post_type' => 'vc_gallery', 'posts_per_page' => 10 ) );?>
	<div class="demo-gallery">
	<div id="container" class="list-unstyled row " data-masonry='{ "itemSelector": ".item", "columnWidth": 200 }'>
		<div class="gutter-sizer"></div>
		<div class="grid-sizer"></div>
		<?php
		while ( $loop->have_posts() ) : $loop->the_post(); 
			if( has_post_thumbnail() ){
				$content_img_path = get_the_post_thumbnail_url( get_the_ID(), 'full' ); 
				echo  '<div class="col-xs-6 col-sm-4 col-md-4 item"><a href="'. get_permalink() . '"><img class="img-responsive image" src="'. $content_img_path .'"/></a><h2>'.get_the_title().'</h2></div>';
				//echo '<p>'.get_the_title() .'</p>';
			}   
		endwhile; 
		?>
	</div>
	</div>
	<?php
	wp_reset_postdata();
	$myvariable = ob_get_clean();
    return $myvariable;
}
add_shortcode( 'easygallerylist', 'VCGL_list_Shortcode' );


function vcgallery_scripts() {

    wp_enqueue_style( 'vcgallery-style', plugin_dir_url( __FILE__ ) . 'css/lightgallery.css', array(), null );
	wp_enqueue_style( 'vcgallery-bootstrap-style', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), null );
    wp_enqueue_style( 'vcgallery-custom-style', plugin_dir_url( __FILE__ ) . 'css/custom.css', array(), null );
	wp_enqueue_script( 'vcgallery-lightgallery', plugin_dir_url( __FILE__ ) . 'js/lightgallery-all.min.js', array(), '', true );
	wp_enqueue_script('masonry');
	wp_enqueue_script('imagesloaded.min');
    wp_enqueue_script( 'vcgallery-custom', plugin_dir_url( __FILE__ ) . 'js/custom.js', array(), '', true );
    wp_enqueue_script( 'vcgallery-bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array(), '', true );

}
add_action( 'wp_enqueue_scripts', 'vcgallery_scripts' );

?>
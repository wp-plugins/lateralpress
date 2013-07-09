<?php
/*
Plugin Name: LateralPress
Plugin URI: http://www.plumeriawebdesign.com/lateralpress-timeline-plugin
Description: Add Lateral On-Scroll Sliding to your Wordpress site. Based on the Lateral On-Scroll Sliding by Mary Lou - Manoela Ilic.
Author: Elke Hinze, Plumeria Web Design
Version: 1.0.0
Author URI:http://www.plumeriawebdesign.com
*/

function lateral_menu(){
  $file = dirname(__FILE__) . '/index.php';
  $plugin_dir = plugin_dir_url($file);
	
  add_menu_page('LateralPress', 'LateralPress', 'manage_options', 'lateral-options', 'lateral_setup', $plugin_dir.'images/logo-16.png');

}

add_action('admin_menu', 'lateral_menu');

function lateral_setup() {
$plugin_url = plugins_url();?>
<div id="wrap">
<h1 id="lateralh1">LateralPress</h1>

<div class="lateral-help">
<p>LateralPress is a plugin used to generate a timeline of events defined by the user.  The dates in the timeline are based on the publish date of the post created for the timeline.  For past events
be sure to set the post date to the specific date, since WordPress will default to today's date.</p>
<h2>Help</h2>
<p>The plugin creates a custom post type called "lateralpress" and then a shortcode to display those posts in an on-screen scrolling timeline.  The plugin is a conversion of the script Lateral On-SCroll Sliding with Jquery written by
<a href="http://tympanus.net/codrops/author/crnacura/" target="_blank">Mary Lou - Manoela Ilic</a>.</p>
<h3>Usage</h3>
<ul>
<li>To add LateralPress to your posts, pages, or widgets use the following shortcode:<br/><code>[lateralpress]</code></li>
<li>To add LateralPress to your WordPress theme use the following shortcode inside your template:<br/><code>echo do_shortcode('[lateralpress]');</code></li>
</ul>
<h3>Creating a Post</h3>
<p>LateralPress creates a custom post type for displaying on the timeline.  The only requirements are that the post contains a title and a featured image.  If no featured image is set, the
timeline will display only a blank image.</p>
<h3>Example</h3>
<p>Visit <a href="http://predestinedguild.com/timeline/" target="_blank">Predestined Timeline</a> to view a fully functioning demo of LateralPress.</p>
</div>
<div style="width:45%;float:right;">
  <div class="metabox-holder postbox" style="padding-top:0;margin:10px;cursor:auto;width:30%;float:left;min-width:320px">
    <h3 class="hndle" style="cursor: auto;"><span><?php  _e( 'Thank you for using LateralPress', 'lateralpress' ); ?></span></h3>
    <div class="inside lateralpress">
      <img src="<?php echo $plugin_url;?>/lateralpress/images/banner-772x250.jpg" alt="LateralPress" />
  	  <?php _e( 'Please support Plumeria Web Design so we can continue making rocking plugins for you. If you enjoy this plugin, please consider offering a small donation. We also look forward
	  to your comments and suggestions so that we may further improve our plugins to better serve you.', 'lateralpress' ); ?>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="SLYFNBZU8V87W">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
    </div>
  </div>
</div>

</div>
<?php
}

function add_lateral_scripts() {
  global $post;

  $file = dirname(__FILE__) . '/index.php';
  $plugin_dir = plugin_dir_url($file);
  
  if(!wp_script_is('jquery')) {
   wp_enqueue_script('jquery');
  } 
  
  wp_register_script( 'add-lateral', $plugin_dir.'js/lateralscrolling.js');
  wp_enqueue_script('add-lateral');
  
  wp_register_script( 'add-lateral-modernizr', $plugin_dir.'js/modernizr.custom.11333.js');
  wp_enqueue_script('add-lateral-modernizr');
  
  wp_register_script( 'add-lateral-easing', $plugin_dir.'js/jquery.easing.1.3.js');
  wp_enqueue_script('add-lateral-easing');
  
  wp_register_style( 'add-lateral', $plugin_dir.'css/style.css','','', 'screen' );
  wp_enqueue_style( 'add-lateral' );

}
add_action('wp_enqueue_scripts', 'add_lateral_scripts');

function add_lateral_ie() {
  global $post;
  if (is_page(array(75, 'timeline'))) {
	  $file = dirname(__FILE__) . '/index.php';
	  $plugin_dir = plugin_dir_url($file);

	  echo " <!--[if lt IE 9]>\n";
	  echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$plugin_dir."css/styleIE.css\" />\n";
	  echo "<![endif]-->\n";
  }
}
add_action('wp_head', 'add_lateral_ie');

function display_lateral_press() {
  global $wpdb;  
  $table_pre = $wpdb->base_prefix;
  $getmonthyear = $wpdb->get_results("select distinct(date_format(post_date, '%b \'%y')) as monthyear from ".$table_pre."posts where post_type = 'lateralpress' and post_status = 'publish'");	
?>
<div id="ss-links" class="ss-links">
<span id="top"></span>
<select id="selLinks" name="selLinks">
<option>--Jump to a Specific Date --</option>
<?php
  foreach ($getmonthyear as $monthyear) {
?>
  <option value="#<?php echo $monthyear->monthyear;?>"><?php echo $monthyear->monthyear;?></option>
<?php  }
?>
</select>
<script type="text/javascript">
(function($) {
$(document).ready(function() {
    $("#selLinks").change(function() {
        location.hash = $(this).val();		
    });
});
})(jQuery);
</script>
</div>
<div id="ss-container" class="ss-container">
 <?php
 $args = array(
    'post_type'=> 'lateralpress', 'posts_per_page' => -1, 'order' => 'asc'
    );              

$the_query = new WP_Query( $args );
$size_arr = array("small", "small-medium", "medium", "medium-large", "large");
$i = 1;
$oldmonthyear = "";
global $post;

if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); 
  $random_val = rand(0,4);
  $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
  $curmonthyear = get_the_time('M \'y');  
?>
<?php if ($curmonthyear !== $oldmonthyear) { ?>
<div class="ss-row">
    <div class="ss-left">
        <h2 id="<?php the_time('M \'y');?>"><a href="#top"><?php the_time('F');?></a></h2>
    </div>
    <div class="ss-right">
        <h2><a href="#top"><?php the_time('Y');?></a></h2>
    </div>
</div>
<?php } ?>
 <div class="ss-row ss-<?php echo $size_arr[$random_val];?>">
 <?php  if ($i % 2 != 0) { ?>
   <div class="ss-left"><a href="<?php echo $thumbnail[0];?>" rel="lightbox"><span class="ss-circle ss-circle-<?php echo $i;?>" style="background-image: url('<?php echo $thumbnail[0];?>');"><?php the_title(); ?></span></a></div>
   <div class="ss-right"><h4><span><?php echo get_the_date(); ?></span> <a href="#"><?php the_title(); ?></a></h4></div>
  <?php } else { ?>
   <div class="ss-left"><h4><span><?php echo get_the_date(); ?></span> <a href="#"><?php the_title(); ?></a></h4></div>
   <div class="ss-right"><a href="<?php echo $thumbnail[0];?>" rel="lightbox"><span class="ss-circle ss-circle-<?php echo $i;?>" style="background-image: url('<?php echo $thumbnail[0];?>');"><?php the_title(); ?></span></a></div>  
  <?php } ?>
  <?php $oldmonthyear = $curmonthyear; ?>
 </div>
<?php
$i++;
endwhile;
endif;
 ?>
</div>
<?php
}

function lateral_shortcodes() {
	add_shortcode( 'lateralpress', 'display_lateral_press');
}
add_action('init', 'lateral_shortcodes');

function create_lateral_post_type() {
	register_post_type( 'lateralpress',
		array(
			'labels' => array(
				'name' => __( 'LateralPress' ),
				'singular_name' => __( 'LateralPress' )
			),
		'public' => true,
		'has_archive' => true,
		'supports' => array( 'title', 'editor', 'comments', 'excerpt', 'custom-fields', 'thumbnail' ),
		)
	);
} 
add_action( 'init', 'create_lateral_post_type' );

function plumwd_lateralpress_footer_text($lateralpress_footer_text) {
  $plugin_url = plugins_url();
  $lateralpress_footer_text = "<span class=\"credit\"><img src=\"$plugin_url/lateralpress/images/plumeria.png\" alt=\"Plumeria Web Design Logo\"/><a href=\"http://www.plumeriawebdesign.com/lateralpress\">LateralPress</a>. Developed by <a href=\"http://www.plumeriawebdesign.com\">Plumeria Web Design</a></span>";
  return $lateralpress_footer_text;
}

if(isset($_GET['page'])) {
  if ($_GET['page'] == "lateral-options") {
    add_filter('admin_footer_text', 'plumwd_lateralpress_footer_text');
  }
}

function plumwd_lateralpress_enqueue_scripts() {
  $file = dirname(__FILE__) . '/index.php';
  $plugin_dir = plugin_dir_url($file);

  wp_register_style('plumwd-lateralpress', $plugin_dir.'css/admin-style.css', '', '', 'screen');
  wp_enqueue_style('plumwd-lateralpress');
}
add_action('admin_enqueue_scripts', 'plumwd_lateralpress_enqueue_scripts');

//let's make the button to add the shortcode
function add_button_sc_plumwd_lateralpress() {
 add_filter('mce_external_plugins', 'add_plugin_sc_plumwd_lateralpress');  
 add_filter('mce_buttons', 'register_button_sc_plumwd_lateralpress');  
}
add_action('init', 'add_button_sc_plumwd_lateralpress');

//we need to register our button
function register_button_sc_plumwd_lateralpress($lateralpress_buttons) {
array_push($lateralpress_buttons, "lateralpress");
return $lateralpress_buttons;  
}

function add_plugin_sc_plumwd_lateralpress($lateralpress_plugin_array) {
$plugin_url = plugins_url();
$script_url = $plugin_url.'/lateralpress/js/shortcode.js';
$lateralpress_plugin_array['lateralpress'] = $script_url; 
return $lateralpress_plugin_array;
}

?>
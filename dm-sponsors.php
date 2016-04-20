<?php

/*
Plugin Name: DM Sponsors
Plugin URI: http://www.designmissoula.com/
Description: This is not just a plugin, it makes WordPress better.
Author: Bradford Knowlton
Version: 1.0.5
Author URI: http://bradknowlton.com/
GitHub Plugin URI: https://github.com/DesignMissoula/DM-sponsors
*/

    add_action( 'init', 'register_taxonomy_sponsor_levels' );
    function register_taxonomy_sponsor_levels() {
    $labels = array(
    'name' => _x( 'Sponsor Levels', 'sponsor_levels' ),
    'singular_name' => _x( 'Sponsor Level', 'sponsor_levels' ),
    'search_items' => _x( 'Search Sponsor Levels', 'sponsor_levels' ),
    'popular_items' => _x( 'Popular Sponsor Levels', 'sponsor_levels' ),
    'all_items' => _x( 'All Sponsor Levels', 'sponsor_levels' ),
    'parent_item' => _x( 'Parent Sponsor Level', 'sponsor_levels' ),
    'parent_item_colon' => _x( 'Parent Sponsor Level:', 'sponsor_levels' ),
    'edit_item' => _x( 'Edit Sponsor Level', 'sponsor_levels' ),
    'update_item' => _x( 'Update Sponsor Level', 'sponsor_levels' ),
    'add_new_item' => _x( 'Add New Sponsor Level', 'sponsor_levels' ),
    'new_item_name' => _x( 'New Sponsor Level', 'sponsor_levels' ),
    'separate_items_with_commas' => _x( 'Separate sponsor levels with commas', 'sponsor_levels' ),
    'add_or_remove_items' => _x( 'Add or remove sponsor levels', 'sponsor_levels' ),
    'choose_from_most_used' => _x( 'Choose from the most used sponsor levels', 'sponsor_levels' ),
    'menu_name' => _x( 'Sponsor Levels', 'sponsor_levels' ),
    );
    $args = array(
    'labels' => $labels,
    'public' => true,
    'show_in_nav_menus' => true,
    'show_ui' => true,
    'show_tagcloud' => true,
    'show_admin_column' => true,
    'hierarchical' => true,
    'rewrite' => true,
    'query_var' => true
    );
    register_taxonomy( 'sponsor_levels', array('sponsor'), $args );
    } 
    

    add_action( 'init', 'register_cpt_sponsor' );
    function register_cpt_sponsor() {
    $labels = array(
    'name' => _x( 'Sponsors', 'sponsor' ),
    'singular_name' => _x( 'Sponsor', 'sponsor' ),
    'add_new' => _x( 'Add New', 'sponsor' ),
    'add_new_item' => _x( 'Add New Sponsor', 'sponsor' ),
    'edit_item' => _x( 'Edit Sponsor', 'sponsor' ),
    'new_item' => _x( 'New Sponsor', 'sponsor' ),
    'view_item' => _x( 'View Sponsor', 'sponsor' ),
    'search_items' => _x( 'Search Sponsors', 'sponsor' ),
    'not_found' => _x( 'No sponsors found', 'sponsor' ),
    'not_found_in_trash' => _x( 'No sponsors found in Trash', 'sponsor' ),
    'parent_item_colon' => _x( 'Parent Sponsor:', 'sponsor' ),
    'menu_name' => _x( 'Sponsors', 'sponsor' ),
    );
    $args = array(
    'labels' => $labels,
    'hierarchical' => false,
    'supports' => array( 'title', 'thumbnail', 'custom-fields' ), // 'author', 'editor', 'excerpt',
    'taxonomies' => array( 'sponsor_levels' ),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => false,
    'publicly_queryable' => true,
    'exclude_from_search' => true,
    'has_archive' => false,
    'query_var' => true,
    'can_export' => false,
    'rewrite' => true,
    'menu_icon' => 'dashicons-groups',
    'capability_type' => 'post'
    );
    register_post_type( 'sponsor', $args );
    } 

// Creating the widget 
class dm_sponsor_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'dm_sponsor_widget', 

// Widget name will appear in UI
__('Sponsor Widget', 'dm_widget_domain'), 

// Widget description
array( 'description' => __( 'Widget To Show Off Sponsors', 'dm_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
echo __( 'Hello, World!', 'wpb_widget_domain' );
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'wpb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function dm_load_widget() {
	register_widget( 'dm_sponsor_widget' );
}
add_action( 'widgets_init', 'dm_load_widget' );
    
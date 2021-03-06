<?php

/*
Plugin Name: DM Sponsors
Plugin URI: http://www.designmissoula.com/
Description: This is not just a plugin, it makes WordPress better.
Author: Bradford Knowlton
Version: 3.3.2
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
		'hierarchical' => true,
		'supports' => array( 'title', 'thumbnail' ), // 'author', 'editor', 'excerpt', , 'custom-fields'
		'taxonomies' => array( 'sponsor_levels' ),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'has_archive' => false,
		'query_var' => true,
		'can_export' => false,
		'rewrite' => true,
		'menu_icon' => 'dashicons-groups',
		'capability_type' => 'post'
	);
	register_post_type( 'sponsor', $args );

	$labels = array(
		'name' => _x( 'Contacts', 'contact' ),
		'singular_name' => _x( 'Contact', 'contact' ),
		'add_new' => _x( 'Add New', 'contact' ),
		'add_new_item' => _x( 'Add New Contact', 'contact' ),
		'edit_item' => _x( 'Edit Contact', 'contact' ),
		'new_item' => _x( 'New Contact', 'contact' ),
		'view_item' => _x( 'View Contact', 'contact' ),
		'search_items' => _x( 'Search Contacts', 'contact' ),
		'not_found' => _x( 'No contacts found', 'contact' ),
		'not_found_in_trash' => _x( 'No contacts found in Trash', 'contact' ),
		'parent_item_colon' => _x( 'Parent Contact:', 'contact' ),
		'menu_name' => _x( 'Contacts', 'contact' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes'  ), // 'author', 'editor', 'excerpt', 'thumbnail',  'custom-fields', 
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'has_archive' => false,
		'query_var' => true,
		'can_export' => false,
		'rewrite' => false,
		'menu_icon' => 'dashicons-businessman',
		'capability_type' => 'post'
	);
	register_post_type( 'contact', $args );	

	new dm_sponsor_meta_box();
	new dm_contact_meta_box();
	
	add_image_size( 'sponsor-banner', 330, 200, false );

	add_action( 'wp_head', 'dm_sponsor_enqueue_style' );

	
}

function dm_sponsor_enqueue_style(){
	wp_register_style( 'dm-sponsor-style', plugins_url( 'css/style.css', __FILE__ ) );
	wp_enqueue_style( 'dm-sponsor-style' );
}


// Creating the widget
class dm_contact_meta_box {

	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'remove_meta_box' ), 100 );
		
	}

	function admin_init() {
	    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	    add_action( 'save_post', array( $this, 'save_post' ), 10);	    
	  }

	// Create the meta box
	function add_meta_boxes() {
	      add_meta_box(
	          'select_item',
	          'Enter Contact Details',
	          array( $this, 'content' ),
	          'contact',
	          'normal',
	          'high'
	      );
	}

	// Create the meta box content
	function content() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), 'dm-sponsor_nonce' );
	    $phone_number = get_post_meta( $post->ID, '_phone_number', true );
	    $selected_item = get_post_meta($post->ID,'_selected_item', true);
	    $static_args = array(
	        'post_type' => 'sponsor', 
	        'show_option_none' => 'Choose Sponsor Organization',
	        'name' => 'selected_item',
	        'id' => 'selected_item',
	        'selected' => $selected_item
	    );
	    
	    ?>
	     <table class="form-table">
		    <tbody>
		        <tr>
		        	<th scope="row">
		        		<label for="selected_item" class="selected_item"><?php _e( 'Sponsor Organization', 'dm-sponsor' )?></label>
					</th>
					<td>
				        <?php wp_dropdown_pages($static_args); ?>
				        <br>
				        <span class="description">Sponsor Company.</span>
				    </td>
				</tr>
				<tr>
		        	<th scope="row">
		        		 <label for="phone_number" class="prfx-row-title"><?php _e( 'Phone Number', 'dm-sponsor' )?></label>
					</th>
					<td>
				        <input type="tel" name="phone_number" id="phone_number" class="regular-text" value="<?php if ( isset ( $phone_number ) ) echo $phone_number; ?>" />
				        <br>
				        <span class="description">Contact Phone Number.</span>
				    </td>
				</tr>
		    </tbody>
		</table>
	    <?php
	   
	}

	// Save the selection
	function save_post( $post_id ) {
	    $selected_item = null;
	    
	    // Checks save status
	    $is_autosave = wp_is_post_autosave( $post_id );
	    $is_revision = wp_is_post_revision( $post_id );
	    $is_valid_nonce = ( isset( $_POST[ 'dm-sponsor_nonce' ] ) && wp_verify_nonce( $_POST[ 'dm-sponsor_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	 
	    // Exits script depending on save status
	    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
	        return;
	    }
	 
	    // Checks for input and sanitizes/saves if needed
	    if( isset( $_POST[ 'phone_number' ] ) ) {
	    	$phone = preg_replace('/[^0-9]/', '', $_POST[ 'phone_number' ] );
	        update_post_meta( $post_id, '_phone_number', sanitize_text_field( $phone ) );
	    
	    }
	    
	    // your form data is in the $_POST array
	    if ( isset( $_POST['selected_item'] ) && $_POST['selected_item'] ) {
	        $selected_item = $_POST['selected_item'];     
	        // then use the $post_id to save your post meta
			update_post_meta( $post_id, '_selected_item', $selected_item );  	        
	    }
	    
		
	    
	}
	
	/**
	* Remove the WooThemes metabox on new page
	* @since 1.15.2
	* http://codex.gravityview.co/class-gravityview-theme-hooks-woothemes_8php_source.html
	*/
	public function remove_meta_box() {
	 remove_meta_box( 'woothemes-settings', 'contact', 'normal' );	  
	 remove_meta_box( 'woothemes-settings', 'sponsor', 'normal' );	  
	
	}
	
	// http://wordpress.stackexchange.com/questions/56104/get-list-of-registered-meta-boxes-and-removing-them
	public function get_meta_boxes( $screen = null, $context = 'normal' ) {
	    global $wp_meta_boxes;
	
	    if ( empty( $screen ) )
	        $screen = get_current_screen();
	    elseif ( is_string( $screen ) )
	        $screen = convert_to_screen( $screen );
	
	    $page = $screen->id;
	
	    return $wp_meta_boxes[$page][$context];          
	}

}

// Creating the widget
class dm_sponsor_meta_box {

	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		
		
	}

	function admin_init() {
	    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	    add_action( 'save_post', array( $this, 'save_post' ), 10);	    
	  }

	// Create the meta box
	function add_meta_boxes() {
	      add_meta_box(
	          'select_item',
	          'Enter Sponsor Details',
	          array( $this, 'content' ),
	          'sponsor',
	          'normal',
	          'high'
	      );
	}

	// Create the meta box content
	function content() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), 'dm-sponsor_nonce' );
	    $website_url = get_post_meta( $post->ID, '_website_url', true );
	    ?>
	    <table class="form-table">
		    <tbody>
		        <tr><th scope="row">
		        <label for="website_url" class="prfx-row-title"><?php _e( 'Website URL', 'dm-sponsor' )?></label>    </th>
		 
		    <td>
		        <input type="text" name="website_url" id="website_url" class="regular-text" value="<?php if ( isset ( $website_url ) ) echo $website_url; ?>" />
		        <br>
		        <span class="description">Sponsor Website URL.</span>
		    </td></tr>
		    </tbody>
		</table>
 
	    <?php
	   
	}

	// Save the selection
	function save_post( $post_id ) {
	    $selected_item = null;
	    
	    // Checks save status
	    $is_autosave = wp_is_post_autosave( $post_id );
	    $is_revision = wp_is_post_revision( $post_id );
	    $is_valid_nonce = ( isset( $_POST[ 'dm-sponsor_nonce' ] ) && wp_verify_nonce( $_POST[ 'dm-sponsor_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	 
	    // Exits script depending on save status
	    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
	        return;
	    }
	 
	    // Checks for input and sanitizes/saves if needed
	    if( isset( $_POST[ 'website_url' ] ) ) {
	        update_post_meta( $post_id, '_website_url', sanitize_text_field( $_POST[ 'website_url' ] ) );
	    
	    }
	    	    
	}
	
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
		
		$titles = array('platinum','gold','silver');
		
		foreach($titles as $title){
		
			?>
			<div class="clearfix">
			<?php
		
			if ( ! empty( $title ) ){
				echo $args['before_title'] . 'Thank you to our '.  $title . ' Sponsors'. $args['after_title'];
			}
				
	
			// This is where you run the code and display the output
			// echo __( 'Hello, World!', 'wpb_widget_domain' );
			// echo do_shortcode('[display-posts post_type="sponsor" taxonomy="sponsor_levels" tax_term="'.$title.'" image_size="medium" wrapper="div"]');
			
			$sponsors = new WP_Query( "post_type=sponsor&taxonomy=sponsor_levels&term=$title" );
			if ( $sponsors->have_posts() ) { 
				while ( $sponsors->have_posts() ) { 
					$sponsors->the_post();
					// the_title();
					
					// wrapper
					?>
					<div class="fivecol-one <?php echo (++$x%5==0)?' last':''; ?>">
					<?php
					
					if( 'platinum' == $title ){
						// platinum					
					
						if ( has_post_thumbnail() ) {
							$website_url = get_post_meta( get_the_id(), '_website_url', true );
							echo '<a href="'.$website_url.'" target="_BLANK">';
						    the_post_thumbnail('sponsor-banner');
						    echo '</a>';
						}
						
					}else if( 'gold' == $title ){
						// gold
	
					
						if ( has_post_thumbnail() ) {
							$website_url = get_post_meta( get_the_id(), '_website_url', true );
							// echo '<a href="'.$website_url.'" target="_BLANK">';
						    the_post_thumbnail('sponsor-banner');
						    // echo '</a>';
						}
						
					}else{
						// silver
							$website_url = get_post_meta( get_the_id(), '_website_url', true );
							echo '<a href="'.$website_url.'" target="_BLANK">';
						    // the_post_thumbnail(get_the_id(), 'sponsor-banner');
						    the_title();
						    echo '</a>';
						
						
					}
					$contacts = new WP_Query( "post_type=contact&meta_key=_selected_item&meta_value=".get_the_id() );
					if ( $contacts->have_posts() ) { 
					echo '<ul>';
					
						while ( $contacts->have_posts() ) { 
							$contacts->the_post();
							$phone = get_post_meta( get_the_id(), '_phone_number', true );
							?>
							<li>
								<?php the_title(); ?> &bull; <a href="tel:<?php echo $phone; ?>">
								<?php echo $this->format_phone_number( $phone ); ?>
								</a>
							</li>
							<?php
							
						}
					echo '</ul>';
					}
					?>
					</div>
					<?php
				
					
				}
			}
			wp_reset_postdata();

			?>
			</div>
			<?php
		
		}
		
				
		
		echo $args['after_widget'];
	}

	public function format_phone_number($phone){
	
	if( 10 == strlen($phone) ){
	
		$phone = substr($phone,0,3).'.'.substr($phone,3,3).'.'.substr($phone,6,4);
	
	}else if( 11 == strlen($phone)){
	
		$phone = substr($phone,0,1).'.'.substr($phone,1,3).'.'.substr($phone,4,3).'.'.substr($phone,7,4);
	
	}else if( 11 < strlen($phone) && 1 == substr($phone,0,1) ){
	
		$phone = substr($phone,0,1).'.'.substr($phone,1,3).'.'.substr($phone,4,3).'.'.substr($phone,7,4).' x'.substr( $phone, 11 );
	
	}else if( 11 < strlen($phone) && 1 != substr($phone,0,1) ){
	
		$phone = substr($phone,0,3).'.'.substr($phone,3,3).'.'.substr($phone,6,4).' x'.substr($phone, 10 );
	
	}else{
		// unknonw format
		
	}
	
	
	
	return $phone;
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

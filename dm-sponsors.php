<?php

/*
Plugin Name: DM Sponsors
Plugin URI: http://www.designmissoula.com/
Description: This is not just a plugin, it makes WordPress better.
Author: Bradford Knowlton
Version: 1.0.1
Author URI: http://bradknowlton.com/
GitHub Plugin URI: https://github.com/DesignMissoula/DM-sponsors
*/

    add_action( 'init', 'register_taxonomy_sponsor_levels' );
    function register_taxonomy_sponsor_levels() {
    $labels = array(
    'name' => _x( 'Sponsor Categories', 'sponsor_levels' ),
    'singular_name' => _x( 'Sponsor Category', 'sponsor_levels' ),
    'search_items' => _x( 'Search Sponsor Categories', 'sponsor_levels' ),
    'popular_items' => _x( 'Popular Sponsor Categories', 'sponsor_levels' ),
    'all_items' => _x( 'All Sponsor Categories', 'sponsor_levels' ),
    'parent_item' => _x( 'Parent Sponsor Category', 'sponsor_levels' ),
    'parent_item_colon' => _x( 'Parent Sponsor Category:', 'sponsor_levels' ),
    'edit_item' => _x( 'Edit Sponsor Category', 'sponsor_levels' ),
    'update_item' => _x( 'Update Sponsor Category', 'sponsor_levels' ),
    'add_new_item' => _x( 'Add New Sponsor Category', 'sponsor_levels' ),
    'new_item_name' => _x( 'New Sponsor Category', 'sponsor_levels' ),
    'separate_items_with_commas' => _x( 'Separate sponsor levels with commas', 'sponsor_levels' ),
    'add_or_remove_items' => _x( 'Add or remove sponsor levels', 'sponsor_levels' ),
    'choose_from_most_used' => _x( 'Choose from the most used sponsor levels', 'sponsor_levels' ),
    'menu_name' => _x( 'Sponsor Categories', 'sponsor_levels' ),
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
    'menu_icon' => 'dashicons-groups',
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
    'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ), // 'author',
    'taxonomies' => array( 'sponsor_levels' ),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => false,
    'has_archive' => true,
    'query_var' => true,
    'can_export' => true,
    'rewrite' => true,
    'capability_type' => 'post'
    );
    register_post_type( 'sponsor', $args );
    } 
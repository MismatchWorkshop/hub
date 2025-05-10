<?php
/*
Plugin Name: CICF Content Hub
Description: Dynamically load content from the CICF Content Hub.
Version: 1.0
Author: Smallbox
Author URI: https://smallbox.com
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class CICF_Content_Hub {

    public function __construct() {
        // Register rewrite rules and query variables.
        add_action( 'init', array( $this, 'add_rewrite_rules' ) );
        add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
        // Hook into template_redirect to load our custom templates.
        add_action( 'template_redirect', array( $this, 'template_redirect_handler' ) );

        // Enqueue scripts and styles.
        add_action('wp_enqueue_scripts', array( $this, 'enqueue_grants_scripts' ));
        add_action('wp_enqueue_scripts', array( $this, 'enqueue_grants_styles' ));

        // Include the AJAX handler.
        include_once plugin_dir_path( __FILE__ ) . 'ajax/grants.php';
        
        // Flush rewrite rules on activation and deactivation.
        register_activation_hook( __FILE__, array( $this, 'plugin_activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivate' ) );
    }

    public function enqueue_grants_scripts() {
      if ( get_query_var('grants_archive') == 1 ) {
          wp_enqueue_script( 'grants-ajax', plugin_dir_url( __FILE__ ) . 'js/grants-ajax.js', array('jquery'), '1.0', true );
          wp_localize_script( 'grants-ajax', 'grants_ajax_obj', array(
              'ajax_url' => admin_url( 'admin-ajax.php' )
          ) );
      }
    }

    public function enqueue_grants_styles() {
      if ( get_query_var('grants_archive') == 1 || get_query_var('grant_slug') ) {
          wp_enqueue_style( 'grants-styles', plugin_dir_url( __FILE__ ) . 'css/cicf-grants.css' );
      }
    }
    
    public function add_rewrite_rules() {
        // Rewrite rule for the grants archive page (/grants).
        add_rewrite_rule( '^grants/?$', 'index.php?grants_archive=1', 'top' );
        // Rewrite rule for a single grant (/grants/{slug}).
        add_rewrite_rule( '^grants/([^/]+)/?$', 'index.php?grant_slug=$matches[1]', 'top' );
    }
    
    public function add_query_vars( $query_vars ) {
        $query_vars[] = 'grants_archive';
        $query_vars[] = 'grant_slug';
        return $query_vars;
    }
    
    public function template_redirect_handler() {
        global $wp_query;

        // Check if the grants archive query variable is set.
        if ( get_query_var( 'grants_archive' ) == 1 ) {
            $wp_query->is_404 = false; // Prevent WP from showing 404.
            $template = $this->locate_template( 'grants-archive.php' );
            if ( $template ) {
                include $template;
                exit;
            }
        }
        // Check if a single grant is requested.
        if ( $slug = get_query_var( 'grant_slug' ) ) {
            $wp_query->is_404 = false; // Prevent WP from showing 404.
            $template = $this->locate_template( 'grant-single.php' );
            if ( $template ) {
                include $template;
                exit;
            }
        }
    }
    
    /**
     * Locate a template in the theme folder first and then in the plugin's templates directory.
     */
    private function locate_template( $template_name ) {
        // Look for the template in the active theme.
        if ( $theme_template = locate_template( $template_name ) ) {
            return $theme_template;
        }
        // Fallback to the plugin's templates folder.
        return plugin_dir_path( __FILE__ ) . 'templates/' . $template_name;
    }
    
    public function plugin_activate() {
        // Ensure rewrite rules are added on activation.
        $this->add_rewrite_rules();
        flush_rewrite_rules();
    }
    
    public function plugin_deactivate() {
        flush_rewrite_rules();
    }
}

new CICF_Content_Hub();

<?php
function cicf_get_template_part( $slug, $name = null, $args = array() ) {
    // Construct the file name
    $template = $name ? "{$slug}-{$name}.php" : "{$slug}.php";

    // Check for a theme override in a subfolder (e.g., "cicf-content-hub/")
    $template_path = ( function_exists( 'locate_template' ) )
        ? locate_template( array( "cicf-content-hub/{$template}", $template ) )
        : false;

    if ( ! $template_path ) {
        // Fallback to the plugin directory
        $template_path = plugin_dir_path( __FILE__ ) . "template-parts/{$template}";
    }

    if ( file_exists( $template_path ) ) {
        // Extract args to variables
        if ( ! empty( $args ) && is_array( $args ) ) {
            extract( $args );
        }
        include $template_path;
    }
}
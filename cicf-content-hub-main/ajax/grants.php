<?php
include_once plugin_dir_path( __FILE__ ) . '../helpers.php';
function search_grants_ajax_handler() {
    // Sanitize the incoming search query.
    $search_query = isset( $_POST['query'] ) ? sanitize_text_field( $_POST['query'] ) : '';

    // Modify the API URL as needed; here we assume the API supports a 'search' parameter.
    $api_url = 'https://www.cicf.org/wp-json/wp/v2/grant?property=245';
    if ( ! empty( $search_query ) ) {
        $api_url .= '&search=' . urlencode( $search_query );
    }

    $response = wp_remote_get( $api_url );
    if ( is_wp_error( $response ) ) {
        echo '<p>Error retrieving grants.</p>';
        wp_die();
    }

    $grants = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ! empty( $grants ) ) { ?>
        <ul class="grant-list">
          <?php foreach ( $grants as $grant ) :?>
            <?php cicf_get_template_part( 'grant-list-item', null, array( 'grant' => $grant ) ); ?>
          <?php endforeach; ?>
        </ul>

    <?php } else {
        echo '<p>No grants found for "' . esc_html( $search_query ) . '".</p>';
    }
    
    wp_die();
}
add_action( 'wp_ajax_search_grants', 'search_grants_ajax_handler' );
add_action( 'wp_ajax_nopriv_search_grants', 'search_grants_ajax_handler' );
?>

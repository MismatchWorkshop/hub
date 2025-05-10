<?php 
// Functions for the REST API on the host site
// Add custom query parameters to the grants endpoint

function add_custom_orderby_param_to_grant( $params ) {
    if ( isset( $params['orderby'] ) && is_array( $params['orderby'] ) ) {
        $params['orderby']['enum'][] = 'meta_value_num';
    }
    return $params;
}
add_filter( 'rest_grant_collection_params', 'add_custom_orderby_param_to_grant' );

// Modify the query to order by a meta value
function add_open_param_to_grant( $params ) {
    $params['open'] = array(
        'description' => 'Set to true to only include grants with a close_date greater than yesterday.',
        'type'        => 'string',
        'default'     => 'false'
    );
    return $params;
}
add_filter( 'rest_grant_collection_params', 'add_open_param_to_grant' );

// Modify the query to order by a meta value
function modify_grant_query_for_orderby( $args, $request ) {
    if ( isset( $request['orderby'] ) && 'meta_value_num' === $request['orderby'] ) {
        // Set the meta key for ordering
        $args['meta_key'] = 'close_date';
        $args['orderby']  = 'meta_value_num';
    }
    return $args;
}
add_filter( 'rest_grant_query', 'modify_grant_query_for_orderby', 10, 2 );

// Filter grants by close date
function filter_grants_by_close_date( $args, $request ) {
    // Check if the 'open' parameter is set to true
    if ( 'true' === $request->get_param( 'open' ) ) {
        // Determine yesterday's date – adjust the format as needed (e.g., Y-m-d)
        $yesterday = date( 'Y-m-d', strtotime( 'yesterday' ) );
        
        // Ensure a meta_query array exists
        if ( ! isset( $args['meta_query'] ) || ! is_array( $args['meta_query'] ) ) {
            $args['meta_query'] = array();
        }
        
        // Add the condition: close_date must be greater than yesterday
        $args['meta_query'][] = array(
            'key'     => 'close_date',
            'value'   => $yesterday,
            'compare' => '>',
            'type'    => 'DATE'
        );
    }
    return $args;
}
add_filter( 'rest_grant_query', 'filter_grants_by_close_date', 10, 2 );
?>
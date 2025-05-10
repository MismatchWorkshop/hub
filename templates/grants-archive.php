<?php
get_header();
?>
<main class="cicf-grants-archive">
  <section class="grants-search">
    <div class="inner">
      <form id="grant-search-form" action="" method="post">
          <input type="text" id="grant-search-input" name="grant_search" placeholder="Search grant opportunities" />
          <button type="submit">Search</button>
      </form>
    </div>
  </section>

  <section id="grant-results" class="grants-list">
    <?php
    $response = wp_remote_get( 'https://www.cicf.org/wp-json/wp/v2/grant?property=245&meta_key=close_date&orderby=meta_value_num&order=asc&open=true' );
    if ( is_wp_error( $response ) ) {
        echo 'Error retrieving data';
    } else {
        // Decode as associative array
        $grants = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( ! empty( $grants ) ) :?>
          <ul class="grant-list">
            <?php foreach ( $grants as $grant ) :?>
              <?php cicf_get_template_part( 'grant-list-item', null, array( 'grant' => $grant ) ); ?>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
            <p>No grants found.</p>
        <?php endif; ?>
    <?php } ?>
  </section>
</main>
<?php
get_footer();

<?php
/* Template for displaying a single grant page. */
get_header();

$slug = get_query_var( 'grant_slug' );
$response = wp_remote_get( 'https://www.cicf.org/wp-json/wp/v2/grant?slug=' . urlencode( $slug ) );
if ( is_wp_error( $response ) ) : ?>
    <p>Sorry, something went wrong.</p>
<?php else: ?>
    <?php
    // Decode as associative array
    $_grant = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ! empty( $_grant ) ) : ?>
        <?php 
            $grant = $_grant[0]; 
            $closeDate = $grant['acf']['close_date'];
            $closeDate = date_create($closeDate);
            $closeDateFormatted = date_format($closeDate, 'M j, Y');
            $today = date_create();
            $diff = date_diff($today, $closeDate);
            $daysLeft = $diff->format('%a');
            $status = $closeDate > $today ? 'open' : 'closed';
            $showAlert = false;
            if ($status === 'open' && $daysLeft <= 30) {
                $showAlert = true;
            }
        ?>
        <main class="single-grant">
            <div class="inner">
                <section class="breadcrumbs">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                            <circle cx="6.5" cy="6.5" r="2.5" fill="#00A9CE"/>
                            <circle cx="6.5" cy="6.5" r="5.5" stroke="#00A9CE" stroke-width="2"/>
                        </svg>
                    </span>
                    <a class="breadcrumb-base" href="<?php echo esc_url( home_url( '/grants-resources' ) ); ?>">Grants & Resources</a>
                    <span class="divider">
                        <svg xmlns="http://www.w3.org/2000/svg" width="7" height="10" viewBox="0 0 7 10" fill="none">
                            <path d="M3.83333 5L0 1.16667L1.16667 0L6.16667 5L1.16667 10L0 8.83333L3.83333 5Z" fill="#002D72"/>
                        </svg>
                    </span>
                    <a class="breadcrumb-parent" href="<?php echo esc_url( home_url( '/grants' ) ); ?>">Grants</a>
                </section>
                <section class="title">
                    <h1><?php echo esc_html( $grant['title']['rendered'] ); ?></h1>
                </section>
                <section class="page-navigation">
                    <nav class="page-sections" aria-label="Page sections">
                        <ul>
                            <li><a data-active href="#description">Description</a></li>
                            <li><a href="#application">Application</a></li>
                            <li><a href="#resources">Resources</a></li>
                        </ul>
                    </nav>
                </section>
                <section id="description" class="section-main">
                    <div class="inner">
                        <div class="content">
                            <?php echo $grant['content']['rendered']; ?>
                        </div>
                    </div>
                </section>
                <section id="application" class="section-main">
                    <div class="inner">
                        <h2 class="label">
                            <?php echo $grant['acf']['application_block']['label']; ?>
                        </h2>
                        <div class="headline">
                            <?php echo $grant['acf']['application_block']['headline']; ?>
                        </div>
                        <div class="content">
                            <?php echo $grant['acf']['application_block']['content']; ?>
                        </div>
                    </div>
                </section>
                <section id="resources" class="section-main">
                    <div class="inner">
                        <h2 class="label">
                            <?php echo $grant['acf']['resources_block']['label']; ?>
                        </h2>
                        <?php if(isset($grant['acf']['resources_block']['headline'])): ?>
                            <div class="headline">
                                <?php echo $grant['acf']['resources_block']['headline']; ?>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grant['acf']['resources_block']['content'])): ?>
                            <div class="content">
                                <?php echo $grant['acf']['resources_block']['content']; ?>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grant['acf']['resources_block']['resources'])):?>
                            <div class="resources-list">
                                <?php $resources = $grant['acf']['resources_block']['resources']; ?>
                                <?php if ( ! empty( $resources ) ) : ?>
                                    <ul>
                                        <?php foreach ( $resources as $resource ) : ?>
                                            <li>
                                                <?php $link = $resource['link'] ? $resource['link'] : "#"; ?>
                                                <a href="<?php echo esc_url( $link ); ?>" target="_blank">
                                                    <h3><?php echo esc_html( $resource['title'] ); ?></h3>
                                                    <p><?php echo esc_html( $resource['description'] ); ?></p>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </main>
        <?php //print_r($grant); ?>
    <?php else : ?>
        <p>No grant found</p>
    <?php endif; ?>
<?php endif; ?>
<?php get_footer(); ?>
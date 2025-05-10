<?php
$grant = $args['grant'];
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
<li class="grant-list-item">
  <article class="grant" data-status="<?=$status?>" <?php if ($showAlert) echo 'data-alert="true"'; ?>>
    <?php if ($showAlert): ?>
      <div class="alert">Closing soon</div>
    <?php endif; ?>

    <section class="grant-status">
      <div class="status">
        <?=$status === 'open' ? 'Open' : 'Closed'; ?>
      </div>
      <span class="divider"></span>
      <div class="close-date">
        <?php if ($status === 'open'): ?>
          <span class="status-text">Closes</span>
        <?php endif; ?>
        <?php if ($status === 'closed'): ?>
          <span class="status-text">Closed</span>
        <?php endif; ?>
        <?=$closeDateFormatted?>
      </div>
    </section>
    <section class="grant-body">
      <a href="<?php echo esc_url( home_url( '/grants/' . $grant['slug'] ) ); ?>">
          <h2><?php echo esc_html( $grant['title']['rendered'] ); ?></h2>
          <div class="excerpt"><?php echo $grant['excerpt']['rendered']; ?></div>
          <div class="button">
            <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19.5 9L10.5 18L8.4 15.9L13.8 10.5L-3.27835e-07 10.5L-4.5897e-07 7.5L13.8 7.5L8.4 2.1L10.5 -4.5897e-07L19.5 9Z" fill="#002D72"/>
            </svg>
          </div>
      </a>
    </section>
  </article>
</li>

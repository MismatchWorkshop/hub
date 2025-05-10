<?php
$grant = $args['grant'];
// $closeDate = $grant['acf']['close_date'];
// $closeDate = date_create($closeDate);
// $closeDateFormatted = date_format($closeDate, 'F j, Y');
// $today = date_create();
// $diff = date_diff($today, $closeDate);
// $daysLeft = $diff->format('%a');
// $status = $closeDate > $today ? 'open' : 'closed';
// $showAlert = false;
// if ($status === 'open' && $daysLeft <= 30) {
//     $showAlert = true;
// }
?>
<li class="grant-list-item">
  <article>
   <pre>
    <?php print_r($grant); ?>
   </pre>
  </article>
</li>

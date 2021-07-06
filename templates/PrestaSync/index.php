<?php

/** @var String $syncResult Result of the sync process */
$syncResult;

?>

<h1>Test!</h1>

<?php if (isset($syncResult)) : ?>
    <h3>Result: <?= $syncResult ?></h3>
<?php endif; ?>
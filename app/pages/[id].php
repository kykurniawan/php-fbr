<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get');

// Get the id from the URL parameter
$id = request()->parameter('id');

?>

<?php page_start('main') ?>
ID: <?= $id ?>
<?php page_end() ?>

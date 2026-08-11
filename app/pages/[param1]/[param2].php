<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get');

$param1 = request()->parameter('param1');
$param2 = request()->parameter('param2');
?>

<?php page_start('main') ?>
Param1: <?= $param1 ?>
<br/>
Param2: <?= $param2 ?> 
<?php page_end() ?>

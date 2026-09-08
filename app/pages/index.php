<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get');

$hello = retrieve('hello');

?>

<?php page_start('main') ?>

<div>
    <?= $hello->world() ?>
</div>
<div>
    <?= xfn('greet', 'Pandaku', 'Kucingku') ?>
</div>
<?php page_end() ?>

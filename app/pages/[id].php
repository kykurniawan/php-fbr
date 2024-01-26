<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get');

$id = fbr()->request()->parameter('id');

$user = fbr()->object('user')->find($id);
?>

<?php page_start('main') ?>
<div class="container">
    <div class="mb-3">
        <a href="<?= url() ?>" class="btn btn-sm btn-primary">Back</a>
        <form action="<?= url($user['id'] . '/delete') ?>" method="post" class="d-inline">
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>
    </div>
    <div class="card">
        <div class="card-body">
            <p class="card-text">
                <strong>Id:</strong>
                <?= $user['id'] ?>
            </p>
            <p class="card-text">
                <strong>Name:</strong>
                <?= $user['name'] ?>
            </p>
            <p class="card-text">
                <strong>Email:</strong>
                <?= $user['email'] ?>
            </p>
            <p class="card-text">
                <strong>Phone:</strong>
                <?= $user['phone'] ?>
            </p>
        </div>
    </div>
</div>
<?php page_end() ?>
<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get');

$users = fbr()->object('user')->all();
?>
<?php page_start('main') ?>
<div class="container">
    <div class="mb-3">
        <a href="<?= url('create') ?>" class="btn btn-sm btn-primary">Create</a>
    </div>
    <?php if (session()->has('message')) : ?>
        <div class="alert alert-info">
            <?= session()->get('message') ?>
        </div>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td>
                        <a href="<?= url($user['id']) ?>">
                            <?= $user['id'] ?>
                        </a>
                    </td>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['phone'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php page_end() ?>
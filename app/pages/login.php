<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get', 'post');

if (request()->method() === 'post') {
    $username = request()->post('username');
    $password = request()->post('password');

    $adminUsername = getenv('ADMIN_USERNAME') ?: 'admin';
    $adminPassword = getenv('ADMIN_PASSWORD') ?: 'admin';

    if ($username === $adminUsername && $password === $adminPassword) {
        session()->set('uid', 1);
        return redirect(url());
    }

    session()->flash('error', 'Invalid username or password');
    return redirect(url('login'));
}
?>
<?php page_start('main') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card mt-5">
                <div class="card-body">
                    <h1 class="h4 mb-4 text-center">Login</h1>
                    <?php if (session()->has('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session()->get('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->has('message')) : ?>
                        <div class="alert alert-info">
                            <?= session()->get('message') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= url('login') ?>" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" placeholder="Username" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php page_end() ?>

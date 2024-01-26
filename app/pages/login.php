<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get', 'post');

if (request()->method() === 'post') {
    $username = request()->post('username');
    $password = request()->post('password');

    if ($username === 'admin' && $password === 'admin') {
        session()->set('uid', 1);
        return redirect(url());
    }

    session()->set('error', 'Invalid username or password');
    return redirect(url('login'));
}

if (session()->has('error')) {
    echo session()->get('error');
}
?>

<form action="<?= url('login') ?>" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>
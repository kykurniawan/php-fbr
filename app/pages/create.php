<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('post', 'get');

if (request()->method() === 'post') {
    $user = fbr()->object('user');

    $data = request()->post();
    $data['id'] = uniqid();

    $user->store($data);

    session()->flash('message', 'User created successfully');


    return redirect(url());
}

?>
<?php page_start('main') ?>
<div class="container">
    <div class="mb-3">
        <a href="<?= url() ?>" class="btn btn-sm btn-primary">Back</a>
    </div>
    <form action="<?= url('create') ?>" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" placeholder="Name" class="form-control">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" id="email" name="email" placeholder="Email" class="form-control">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" id="phone" name="phone" placeholder="Phone" class="form-control">
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-sm btn-primary">Create</button>
        </div>
    </form>
</div>
<?php page_end() ?>
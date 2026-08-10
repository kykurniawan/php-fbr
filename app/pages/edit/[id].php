<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get', 'post');

$id = request()->parameter('id');

$userModel = fbr()->object('user');

if (request()->method() === 'post') {
    $userModel->update($id, request()->post());

    session()->flash('message', 'User updated successfully');

    return redirect(url($id));
}

$user = $userModel->find($id);
?>
<?php page_start('main') ?>
<div class="container">
    <div class="mb-3">
        <a href="<?= url($user['id']) ?>" class="btn btn-sm btn-primary">Back</a>
    </div>
    <form action="<?= url('edit/' . $user['id']) ?>" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES) ?>" placeholder="Name" class="form-control">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES) ?>" placeholder="Email" class="form-control">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '', ENT_QUOTES) ?>" placeholder="Phone" class="form-control">
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </div>
    </form>
</div>
<?php page_end() ?>

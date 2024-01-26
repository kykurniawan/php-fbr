<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('post');

$id = request()->parameter('id');

fbr()->object('user')->delete($id);

session()->flash('message', 'User deleted successfully.');

return redirect(url());

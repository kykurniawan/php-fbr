<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('post');

session()->flush();
session()->flash('message', 'You have been logged out.');

return redirect(url('login'));

<?php
/**
 * Smoke test for the User model.
 *
 * Run from the project root:
 *   php tests/smoke.php
 *
 * Uses a throwaway SQLite database. Exits non-zero on any failure.
 */

define('RUN', true);

require_once __DIR__ . '/../core/loader.php';
require_once __DIR__ . '/../app/modules/User.php';

$database = sys_get_temp_dir() . '/fbr-smoke-' . uniqid() . '.sqlite';
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $database);

$failures = 0;

function check(string $name, bool $condition): void
{
    global $failures;

    if ($condition) {
        echo "[PASS] {$name}" . PHP_EOL;
    } else {
        echo "[FAIL] {$name}" . PHP_EOL;
        $failures++;
    }
}

$userModel = new User();

// store
check('store returns true', $userModel->store([
    'id' => 'u1', 'name' => 'Alice', 'email' => 'alice@example.com', 'phone' => '0811',
]) === true);
check('store with missing fields does not warn', $userModel->store(['id' => 'u2']) === true);

// find
$alice = $userModel->find('u1');
check('find returns the stored user', $alice['name'] === 'Alice' && $alice['email'] === 'alice@example.com');
check('find throws 404 for unknown id', (function () use ($userModel) {
    try {
        $userModel->find('missing');
        return false;
    } catch (PageNotFoundException $e) {
        return $e->getCode() === 404;
    }
})());

// all
check('all returns every user', count($userModel->all()) === 2);

// update
check('update returns true', $userModel->update('u2', ['name' => 'Bob', 'email' => 'bob@example.com', 'phone' => '0822']) === true);
$bob = $userModel->find('u2');
check('update persists changes', $bob['name'] === 'Bob' && $bob['email'] === 'bob@example.com');

// delete
check('delete returns true', $userModel->delete('u2') === true);
check('delete removes the user', count($userModel->all()) === 1);

// cleanup
unlink($database);

echo PHP_EOL . ($failures === 0 ? 'ALL TESTS PASSED' : $failures . ' TEST(S) FAILED') . PHP_EOL;
exit($failures === 0 ? 0 : 1);

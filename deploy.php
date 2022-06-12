<?php
namespace Deployer;

$hostname = getenv('HOSTNAME');
$username = getenv('USERNAME');
$deployPath = getenv('DEPLOY_PATH');
$phpPath = getenv('PHP_PATH');

require 'recipe/laravel.php';
require 'recipe/rsync.php';

set('http_user', $username);

// Shared files/dirs between deploys
add('shared_files', [
    '.env'
]);

add('shared_dirs', [
    'storage'
]);

set('bin/php', function () use ($phpPath) {
    return $phpPath;
});

set('bin/composer', function () {
    if (empty($composer)) {
        run("cd {{release_path}} && curl -sS https://getcomposer.org/installer | {{bin/php}}");
        $composer = '{{bin/php}} {{release_path}}/composer.phar';
    }

    return $composer;
});

set('rsync_src', function () {
    return __DIR__; // If your project isn't in the root, you'll need to change this.
});

// Configuring the rsync exclusions.
// You'll want to exclude anything that you don't want on the production server.
add('rsync', [
    'exclude' => [
        '.git',
        '/.env',
        '/storage/app/*',
        '/storage/framework/*',
        '/storage/framework/cache/*',
        '/storage/framework/sessions/*',
        '/storage/framework/views/*',
        '/storage/logs/*',
        '/vendor/',
        '/node_modules/',
        '/.gitlab-ci.yml',
        '/deploy.php',
    ],
]);

set('allow_anonymous_stats', false);

host('main')
    ->hostname($hostname)
    ->user($username)
    ->port('22')
    ->forwardAgent(true)
    ->multiplexing(false)
    ->set('deploy_path', $deployPath)
    ->addSshOption('StrictHostKeyChecking', 'no');

task('generate-api', function (){
    run('cd {{release_path}} && {{bin/php}} artisan openapi:generate > public/open-api.json');
});

task('add-nova-config', function (){
    run('cd {{release_path}} && {{bin/composer}} config http-basic.nova.laravel.com ' . getenv('NOVA_USERNAME') . ' ' . getenv('NOVA_LICENSE_KEY'));
});

task('generate-docs', function (){
    run('cd {{release_path}} && {{bin/composer}} run generate-docs');
});


task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'rsync', // Deploy code & built assets
    'deploy:shared',
    'add-nova-config',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link', // |
    'artisan:view:cache',   // |
    'artisan:config:cache', // | Laravel specific steps
    'artisan:optimize',     // |
    'artisan:migrate',      // |
    'deploy:symlink',
    'deploy:unlock',
    'cleanup'
]);
// Recipe modifiers:

// Unlock after failed deploy process
after('deploy:failed', 'deploy:unlock');

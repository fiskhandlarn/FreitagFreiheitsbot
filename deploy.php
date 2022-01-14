<?php
namespace Deployer;

require 'recipe/common.php';
require 'recipe/rsync.php';

// Project name
set('application', 'wiki.visbypirater.org');

set('allow_anonymous_stats', false);

// Append to Deployer defaults (https://deployer.org/recipes/rsync.html#sample-configuration)
$rsyncOptions = get('rsync');
$rsyncOptions['exclude'] = array_merge($rsyncOptions['exclude'], [
    '.editorconfig',
    '.env.docker',
    '.env.example',
    '.github',
    '.gitignore',
    'composer.json',
    'composer.lock',
    'docker-compose.yml',
    'Dockerfile.phpfpm',
    'LICENSE',
    'Makefile',
    'README.md',
]);
$rsyncOptions['options'] = array_merge($rsyncOptions['options'], [
    'chown="'. getenv('DEPLOY_CHOWN') . '"',
    'chmod="ug=rwX,o=rX"',
]);
set('rsync', $rsyncOptions);

set('rsync_src', function () {
    return __DIR__;
});

task('chown', function() {
    run('sudo chmod -R ug=rwX,o=rX {{deploy_path}}');
    run('sudo chown -R '. getenv('DEPLOY_CHOWN') . ' {{deploy_path}}');
});

task('restart:service', function() {
    run('sudo systemctl restart php7.4-fpm');
});

// Hosts
host('prod')
        ->hostname(getenv('SSH_HOST'))
    ->stage('production')
        ->user(getenv('SSH_USER'))
    ->set('deploy_path', getenv('DEPLOY_PATH'));

// Tasks
task('deploy', [
    'deploy:info',
    'chown',
    'deploy:prepare',
    'deploy:release',
    'rsync',
    'deploy:shared',
    'deploy:symlink',
    'cleanup',
    'chown',
    'restart:service',
    'success'
]);

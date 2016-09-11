<?php
/*
 * This file has been generated automatically.
 * Please change the configuration for correct use deploy.
 */

require 'recipe/symfony3.php';

// Set configurations
set('repository', 'https://github.com/IhorKru/officejobsguru.com.git');
set('shared_files', ['app/config/parameters.yml']);
set('shared_dirs', ['app/logs']);
set('writable_dirs', ['app/cache', 'app/logs']);

// Configure servers
server('production', 'mediaff.com', '22')
    ->user('mediazmj')
    ->password('3+f9mJ5#1n0q')
    ->env('deploy_path', '/home/mediazmj/public_html/officejobsguru.com');

/**
 * Restart php-fpm on success deploy.
 */
task('php-fpm:restart', function () {
    // Attention: The user must have rights for restart service
    // Attention: the command "sudo /bin/systemctl restart php-fpm.service" used only on CentOS system
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('sudo /bin/systemctl restart php-fpm.service');
})->desc('Restart PHP-FPM service');

after('success', 'php-fpm:restart');


/**
 * Attention: This command is only for for example. Please follow your own migrate strategy.
 * Attention: Commented by default.  
 * Migrate database before symlink new release.
 */
 
// before('deploy:symlink', 'database:migrate');
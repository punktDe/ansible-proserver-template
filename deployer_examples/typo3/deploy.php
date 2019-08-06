<?php
namespace Deployer;
require 'recipe/common.php';

set('application', 'PTW');
set('repository', 'git@github.com:punktDe/404.git');
set('git_tty', empty(getenv('CI')));
set('typo3_webroot', 'Web');
set('shared_dirs', [
    '{{typo3_webroot}}/fileadmin',
    '{{typo3_webroot}}/typo3temp',
    '{{typo3_webroot}}/uploads'
]);
set('shared_files', [
    '{{typo3_webroot}}/.htaccess'
]);
set('deploy_path', '/var/www/typo3');
host('proserver@vpro0000.proserver.punkt.de')->stage('staging');
task('deploy:phpfpm_reload', 'sudo /usr/local/etc/rc.d/php-fpm reload');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:phpfpm_reload',
    'deploy:unlock',
    'cleanup',
    'success',
]);
after('deploy:failed', 'deploy:unlock');

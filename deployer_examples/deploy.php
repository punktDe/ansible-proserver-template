<?php
set('application', 'neos');
set('deploy_path', '/var/www/neos');
//set('default_stage', 'staging');

host('proserver@vpro0000.proserver.punkt.de')
    ->stage('production');
    
host('proserver@vpro0000.proserver.punkt.de')
    ->stage('staging');

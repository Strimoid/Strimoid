<?php // https://github.com/deployphp/deployer

require 'recipe/laravel.php';

// Define a server for deployment.
server('box', getenv('HOST'))
    ->user('strimoid')
    ->identityFile()
    ->stage('staging')
    ->env('deploy_path', '/www/strimoid'); // Define the base path to deploy your project to.

set('ssh_type', 'ext-ssh2');
set('repository', 'https://github.com/Strimoid/Strimoid.git');

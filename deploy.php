<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'amandla.mobi');

// Project repository
set('repository', 'git@github.com:DeGoedeZaak/amandla-wp.git');

set( 'keep_releases', 3 );

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server
set('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host('134.122.56.99')
    ->user('web-admin')
    ->set('deploy_path', '~/{{application}}');


// Tasks

desc('Deploy your project');
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
    'permission:change',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

after('deploy', 'link:enviroment-file', 'permission:change');

task('deploy:vendors', function(){
    writeln( '<info>  Updating composer</info>' );
    cd( '{{release_path}}/src');
    run( 'composer update --no-dev' );
});

task('permission:change', function(){
    writeln( '<info>  Change owner </info>' );
    run ('sudo chown -R app:app {{release_path}}');
});

task('link:enviroment-file', function(){
    writeln( '<info>  Link .env file </info>' );
    run ('sudo ln -s {{deploy_path}}/shared/production/enviroment {{release_path}}/src/web/app/uploads');
});

task('link:enviroment-file', function(){
    writeln( '<info>  Link uploads directory </info>' );
    run ('sudo ln -s {{deploy_path}}/shared/uploads {{release_path}}/src/');
});

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

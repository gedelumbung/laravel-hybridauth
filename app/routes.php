<?php

$app = App::getFacadeApplication();
$router = $app['router'];

$namespace = 'App\Controllers\\';

$router->controller('/', "{$namespace}HomeController", [
        'getIndex'     	=> 'web.index',
        'getFacebook' 	=> 'web.facebook',
        'getLinkedin'	=> 'web.linkedin',
    ]);

$router->get('social/{auth}', [
        'as'   => 'web.social',
        'uses' => "{$namespace}HomeController@getSocial"
    ])->where(array('auth' => '[A-Za-z0-9-]+'));

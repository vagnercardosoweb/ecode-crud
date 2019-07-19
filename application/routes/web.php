<?php

/*
 * VCWeb Networks <https://www.vcwebnetworks.com.br/>
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 18/07/2019 Vagner Cardoso
 */

$app->route('get', '/', 'IndexController', 'web.index');
$app->route('get', '/logoff', 'IndexController@logoff', 'web.logoff', 'auth');
$app->route('get,post,put,delete', '/pessoas[/{type}[/{id}]]', 'PersonController', 'web.person', 'auth');

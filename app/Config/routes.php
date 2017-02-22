<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
Router::connect('/', array('controller' => 'users', 'action' => 'dashboard'));
Router::connect('/admin', array('controller' => 'users', 'action' => 'login', 'admin' => TRUE));
Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
Router::connect('/gkbytes', array('controller' => 'questions', 'action' => 'gkbytes'));


//    Router::connect(
//		'/auth/*', 
//		array('plugin'=>'Opauth','controller' => 'OpauthController')
//	);
//Router::connect('/auth/*', array('plugin' => 'Opauth', 'controller' => 'Opauth', 'action' => 'index'));
//    Router::connect(
//        '/opauth-complete/*', array('controller' => 'users', 'action' => 'opauth_complete')
//    );
//Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

CakePlugin::routes();

/* Pages controller make work with REST api json - dharmendra */
Router::mapResources("pages");
Router::parseExtensions();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';

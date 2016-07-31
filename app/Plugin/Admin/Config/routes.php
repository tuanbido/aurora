<?php
Router::connect('/download/*', array('plugin' => 'admin', 'controller' => 'downloads', 'action' => 'get'));
Router::connect('/img/*', array('plugin' => 'admin', 'controller' => 'thumbs', 'action' => 'view'));
Router::connect('/thumbs/*', array('plugin' => 'admin', 'controller' => 'thumbs', 'action' => 'generate'));
Router::connect('/admin', array('plugin' => 'admin', 'controller' => 'admin', 'action' => 'login'));
Router::connect('/admin/:controller/:action/*', array('plugin' => 'admin'));

Configure::write('Routing.prefixes', array('admin'));

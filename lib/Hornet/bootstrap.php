<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

/**
 * Simple bootstrap file
 */
namespace Hornet {

	use Hornet\Loaders\StandardLoader;

	if (!defined('HORNET_LIB_PATH')){
		
		define('HORNET_LIB_PATH', __DIR__);
		
	} // if
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH, 'Loaders', 'Interfaces', 'LoaderInterface.php'));
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH, 'Loaders', 'StandardLoader.php'));
	
	new StandardLoader(HORNET_LIB_PATH, __NAMESPACE__);
	
} // namespace
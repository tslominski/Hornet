<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Loaders\Interfaces {

	/**
	 * Interface for class loaders
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2010-09-15
	 * @version 1.2
	 * @package Loaders
	 */
	interface LoaderInterface {

		/**
		 * Register loadClass method on spl autoload stack
		 * @return Hornet\Loaders\Interfaces\LoaderInterface
		 */
		public function register();

		/**
		 * Unregister loadClass method from spl autoload stack
		 * @return Hornet\Loaders\Interfaces\LoaderInterface
		 */
		public function unregister();

		/**
		 * Loads given class
		 * @param string $sClassName Class to load
		 * @return Hornet\Loaders\Interfaces\LoaderInterface
		 */
		public function loadClass($sClassName);

	} // interface

} // namespace
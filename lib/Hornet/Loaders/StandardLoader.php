<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Loaders {

	use Hornet\Loaders\Interfaces\LoaderInterface;

	# for convenience only
	if (!defined('NAMESPACE_SEPARATOR')){

		define('NAMESPACE_SEPARATOR', '\\');

	} // if

	/**
	 * Universal class loader, PCR-0 compliant
	 * @see http://groups.google.com/group/php-standards/web/psr-0-final-proposal?pli=1
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2010-07-20
	 * @version 1.8
	 * @package Loaders
	 */
	class StandardLoader implements LoaderInterface{

	# PROTECTED VARIABLES

		protected $sSourceDir  = '';

		protected $sNamespace  = '';

		protected $sExtension   = '.php';

	# PUBLIC METHODS

		/**
		 * Class constructor
		 * @param string|null $sSourceDir Source directory for class lookup
		 * @param string $sNamespace Base namespace. Will be strip off the beginnig of class name.
		 * @params boolean $bAutoRegister If set, class autoregisters self on spl stack. Default true
		 */
		public function __construct($sSourceDir, $sNamespace = '', $bAutoRegister = true){

			$this->sSourceDir = rtrim($sSourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

			$this->sNamespace = rtrim($sNamespace, NAMESPACE_SEPARATOR) . NAMESPACE_SEPARATOR;

			if ($bAutoRegister){

				$this->register();

			} // if

		} //  __construct

		/**
		 * Register loadClass method on spl autoload stack
		 * @return Hornet\Loaders\Loader
		 */
		public function register(){

			if (array_search(array($this,'loadClass'), (array)spl_autoload_functions()) === false){

				spl_autoload_register(array($this,'loadClass'));

			} // if

			return $this;

		} // register

		/**
		 * Unregister loadClass method from spl autoload stack
		 * @return Hornet\Loaders\Loader
		 */
		public function unregister(){

			spl_autoload_unregister(array($this, 'loadClass'));

			return $this;

		} // unregister

		/**
		 * Loads given class
		 * @param string $sClassName Class to load
		 * @return Hornet\Loaders\Loader
		 */
		public function loadClass($sClassName){

			$sClassName = ltrim($sClassName, NAMESPACE_SEPARATOR);

			if (!empty($this->sNamespace) && substr($sClassName, 0, strlen($this->sNamespace)) == $this->sNamespace){

				$sClassName = substr($sClassName,  strlen($this->sNamespace));

			} // if

			$sFilePath = str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $this->sSourceDir . $sClassName . $this->sExtension);

			if (is_readable($sFilePath)){

				# @todo Cannot it be include ?
				include_once $sFilePath;

			} // if

			return $this;

		} // loadClass

	} // class

} // namespace
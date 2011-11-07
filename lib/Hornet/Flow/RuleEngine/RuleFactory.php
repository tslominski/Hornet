<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine {
	
	use ReflectionClass;
	
	if (!defined('NAMESPACE_SEPARATOR')){
		
		define('NAMESPACE_SEPARATOR', '\\');
		
	} // if
	
	/**
	 * Simple rule factory
	 *
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2011-11-04
	 * @version 1.0
	 * @package Flow
	 **/
	class RuleFactory {
		
		/**
		 * Simple rule factory. Searches rule in Hornet\Flow\RuleEngine\Rules
		 * @param string $sRuleName Name of rule (last element of class name)
		 * @param mixed $mArgument1, ... (optional) Rule constructor arguments
		 * @return Hornet\Flow\RuleEngine\Interfaces\RuleInterface Rule
		 */
		public static function create($sRuleName){
			
			$sFullClassName = implode(NAMESPACE_SEPARATOR, array(__NAMESPACE__, 'Rules', $sRuleName));

			$aArgs = func_get_args();
			
			array_shift($aArgs);
			
			$oRuleReflector = new ReflectionClass($sFullClassName);
			
			return $oRuleReflector->newInstanceArgs($aArgs);
		
		} // create
		
	} // class
	
} // namespace
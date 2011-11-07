<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Extractors {

	use Hornet\Flow\RuleEngine\Interfaces\ExtractorInterface;
   
   /**
	* Extracts public method return value from object.
	*
	* @author Tomasz Słomiński <tomasz@slominski.it>
	* @since 2011-11-04
	* @version 1.0
	* @package Flow
	*/
	class MethodExtractor implements ExtractorInterface {

		/**
		 * Name of method
		 * @var string
		 */
		protected $sMethodName 	 = null;

		/**
		 * Called method arguments (optional)
		 * @var array
		 */
		protected $aArgs  = array();
		
		/**
		* Default value
		* @var mixed
		*/
		protected $mDefaultValue = null;
		
		/**
		 * Sets name and arguments of method to extract and default value
		 * @param string $sMethodName
		 * @param array $aArgs
		 * @param mixed $mDefaultValue
		 */
		public function __construct($sMethodName, $aArgs = array(), $mDefaultValue = null){
			
			$this->sMethodName = $sMethodName;
						
			$this->aArgs = $aArgs;

			$this->mDefaultValue = $mDefaultValue;
						
		} // __construct
		
		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Interfaces.ExtractorInterface::__invoke()
		 */
		public function __invoke($mContext){
			
			if (is_object($mContext) && is_callable(array($mContext, $this->sMethodName))){
				
				return call_user_func_array(array($mContext, $this->sMethodName), $this->aArgs);
				
			} else {
				
				return $this->mDefaultValue;
				
			} // if
			
		} // __invoke
				
	} // class
	
} // namespace
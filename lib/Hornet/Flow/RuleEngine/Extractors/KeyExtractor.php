<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Extractors {
	
	use ArrayAccess;
	use Hornet\Flow\RuleEngine\Interfaces\ExtractorInterface;
   
   /**
	* Extracts value for given key from array or object implementing 
	* ArrayAccess interface
	*
	* @author Tomasz Słomiński <tomasz@slominski.it>
	* @since 2011-11-04
	* @version 1.0
	* @package Flow
	*/
	class KeyExtractor implements ExtractorInterface {

		/**
		 * Key name
		 * @var mixed
		 */
		protected $mKey = null;
		
		/**
		 * Default value
		 * @var mixed
		 */
		protected $mDefaultValue = null;
		
		public function __construct($mKey, $mDefaultValue = null){
			
			$this->mKey = $mKey;
			
			$this->mDefaultValue = $mDefaultValue;
			
		} // __construct
		
		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Interfaces.ExtractorInterface::__invoke()
		 */
		public function __invoke($mContext){
			
			if ((is_array($mContext) && array_key_exists($this->mKey, $mContext)) || (is_object($mContext) && $mContext instanceof ArrayAccess && $mContext->offsetExists($this->mKey))){
				
				return $mContext[$this->mKey];
				
			} else {
				
				return $this->mDefaultValue;
				
			} // if
			
		} // __invoke
				
	} // class
	
} // namespace
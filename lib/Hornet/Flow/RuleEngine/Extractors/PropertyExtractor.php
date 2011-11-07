<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Extractors {

	use Hornet\Flow\RuleEngine\Interfaces\ExtractorInterface;

	/**
	 * Extracts public property from object.
	 *
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2011-11-04
	 * @version 1.0
	 * @package Flow
	 */
	class PropertyExtractor implements ExtractorInterface {

		/**
		 * Name of property to extract
		 * @var string
		 */
		protected $sPropertyName = null;
		
		/**
		 * Default value
		 * @var mixed
		 */
		protected $mDefaultValue = null;
		
		/**
		 * Sets name of property to extract and default value
		 * @param string $sPropertyName
		 * @param mixed $mDefaultValue
		 */
		public function __construct($sPropertyName, $mDefaultValue = null){
			
			$this->sPropertyName = (string)$sPropertyName;
			
			$this->mDefaultValue = $mDefaultValue;
			
		} // __construct
		
		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Interfaces.ExtractorInterface::__invoke()
		 */
		public function __invoke($mContext){
			
			if (is_object($mContext) && property_exists($mContext, $this->sPropertyName)){
				
				return $mContext->{$this->sPropertyName};
				
			} else {
				
				return $this->mDefaultValue;
				
			} // if
			
		} // __invoke
				
	} // class
	
} // namespace
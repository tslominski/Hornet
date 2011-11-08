<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Interfaces {

   /**
	* Interface for rules.
	* Rules are classes which can match given context
	*
	* @author Tomasz Słomiński <tomasz@slominski.it>
	* @since 2011-11-04
	* @version 1.0
	* @package Flow
	*/	
	interface RuleInterface {
		
		/**
		 * Tests whether rule matches given context
		 * @return boolean True if rule matches context
		 */
		public function match($mContext);
		
		/**
		 * Sets extractor which extracts comparable value from context
		 * Extractor is a callable which takes a context as only argument
		 * and returns mixed value that will be passed to comparison function
		 * If extractor is null, whole context is passed to comparison function
		 * @param callable|null $cExtractor
		 * @return Hornet\Flow\RuleEngine\Interfaces\RuleInterface
		 */
		public function setExtractor($cExtractor);
		
		/**
		 * Sets rule product - value used if rule matches context
		 * @param mixed $mProduct Product
		 * @return Hornet\Flow\RuleEngine\Interfaces\RuleInterface
		 */
		public function setProduct($mProduct);
		
		/**
		 * Sets product callback - used to generate product value if rule matches context
		 * @param callable $cCallback Product callback
		 * @throws Hornet\Flow\RuleEngine\Exceptions\RuleEngineException If callback is not callable
		 * @return Hornet\Flow\RuleEngine\Interfaces\RuleInterface
		 */
		public function setProductCallback($cCallback);
		
		/**
		 * Gets rule product (see doc for setProduct for description)
		 * @param mixed $mContext Context (can be used to generate product value by callback) 
		 * @return mixed Product 
		 */
		public function getProduct($mContext = null);
		
	} // interface

} // namespace
<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Rules {

	use Hornet\Flow\RuleEngine\Exceptions\RuleEngineException;
	use Hornet\Flow\RuleEngine\Interfaces\RuleInterface;

	/**
	 * Base class for rules
	 *
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2011-11-04
	 * @version 1.0
	 * @package Flow
	 **/
	abstract class AbstractRule implements RuleInterface {

		/**
		 * Extractor
		 * @var callable
		 */
		protected $cExtractor = null;
		
		/**
		 * Rule product
		 * @var mixed
		 */
		protected $mProduct   = null;

		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Interfaces.RuleInterface::match()
		 */
		public function match($mContext){
		
			$mValue = $this->cExtractor === null ? $mContext : call_user_func($this->cExtractor, $mContext);
		
			return $this->compare($mValue);
				
		} // match
		
		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Interfaces.RuleInterface::setExtractor()
		 */
		public function setExtractor($cExtractor){
			
			if (is_callable($cExtractor) || $cExtractor === null){
			
				$this->cExtractor = $cExtractor;
			
			} else {
				
				throw new RuleEngineException(sprintf(RuleEngineException::MSG_INVALID_EXTRACTOR, gettype($cExtractor)), RuleEngineException::EX_INVALID_EXTRACTOR);
				
			} // if
			
			return $this;
			
		} // setExtractor
		
		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Interfaces.RuleInterface::setProduct()
		 */
		public function setProduct($mProduct){
				
			$this->mProduct = $mProduct;
			
			return $this;
				
		} // setProduct
		
		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Interfaces.RuleInterface::getProduct()
		 * @todo See if getProduct should'nt return class name if not set
		 * @todo Support for dynamic product (ie. callbacks etc.)
		 */
		public function getProduct(){
		
			return $this->mProduct;
		
		} // getProduct

		/**
		 * Internal comparison function, used by match.
		 * Tests whether given value fulfills rule
		 * @param mixed $mValue Tested value
		 * @return boolean True if value matches
		 */
		abstract protected function compare($mValue);
		
	} // class

} // namespace
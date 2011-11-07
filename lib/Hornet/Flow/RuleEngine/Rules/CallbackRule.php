<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Rules {

	use Hornet\Flow\RuleEngine\Exceptions\RuleEngineException;
	use Hornet\Flow\RuleEngine\Rules\AbstractRule;

	/**
	 * Simple rule using custom callback as compare function
	 *
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2011-11-04
	 * @version 1.0
	 * @package Flow
	 **/
	class CallbackRule extends AbstractRule {

		/**
		 * Comparing callback. Have to accept variable to test
		 * and should return boolean.
		 * @var callable
		 */
		protected $cCallback  = null;
		
		/**
		 * Class constructor
		 * @param callable $cCallback Comparison callback
		 * @throws RuleEngineException If callback is not callale
		 */
		public function __construct($cCallback){
			
			if (is_callable($cCallback)){
			
				$this->cCallback = $cCallback;
			
			} else {

				throw new RuleEngineException(sprintf(RuleEngineException::MSG_INVALID_CALLBACK, gettype($cCallback)), RuleEngineException::EX_INVALID_CALLBACK);
				
			} // if
			
		} // __construct
		
		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Rules.AbstractRule::compare()
		 */
		protected function compare($mValue){

			return (boolean)call_user_func($this->cCallback, $mValue);
			
		} // compare
		
	} // class

} // namespace
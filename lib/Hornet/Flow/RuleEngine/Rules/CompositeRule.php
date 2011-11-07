<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Rules {

	use Hornet\Flow\RuleEngine\Exceptions\RuleEngineException;

	use Hornet\Flow\RuleEngine\RuleEngine;
	use Hornet\Flow\RuleEngine\RuleSet;
	use Hornet\Flow\RuleEngine\Rules\AbstractRule;

	
	/**
	 * Composite rule for creating nested rulesets
	 *
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2011-11-04
	 * @version 1.0
	 * @package Flow
	**/
	class CompositeRule extends AbstractRule {
		
		# for convenience
		const ANY 		= 'any';
		const SOME  	= 'some';
		const ALL		= 'all';
		const EXACTLY	= 'exactly';
		const MIN		= 'min';
		const MAX		= 'max';
	
		/**
		 * Rule engine
		 * @var Hornet\Flow\RuleEngine\RuleEngine
		 */
		protected $oRuleEngine  = null;
		
		/**
		 * Operator to call on inner RuleEngine (ie. all, any)
		 * @var callable
		 */
		protected $cCallback    = null;
		
		/**
		 * Additional callback arguments
		 * @var array
		 */
		protected $aArgs	    = array();
		
		/**
		 * 
		 * Enter description here ...
		 * @param Hornet\Flow\RuleEngine\RuleSet $oRuleSet
		 * @param string $sCallback
		 */
		public function __construct(RuleSet $oRuleSet, $sCallback = self::ALL){

			$this->oRuleEngine = new RuleEngine($oRuleSet);
									
			$this->cCallback = array($this->oRuleEngine, (string)$sCallback);
			
			if (!is_callable($this->cCallback)){
				
				throw new RuleEngineException(sprintf(RuleEngineException::MSG_INVALID_ENGINE_CALLBACK,  (string)$sCallback), RuleEngineException::EX_INVALID_ENGINE_CALLBACK);
				
			} // if

			if (func_num_args() > 2){
				
				$this->aArgs = array_slice(func_get_args(), 2);
				
			} // if
			
		} // __construct
		
		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Rules.AbstractRule::compare()
		 */
		protected function compare($mValue){
			
			$this->oRuleEngine->setContext($mValue);
				
			return (boolean)call_user_func_array($this->cCallback, $this->aArgs);
			
		}
	
	} // interface

} // namespace
<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Rules {

	use Hornet\Flow\RuleEngine\Rules\AbstractRule;
	
	/**
	 * Simple rule testing equality
	 *
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2011-11-04
	 * @version 1.0
	 * @package Flow
	 **/
	class EqualRule extends AbstractRule {
	
		/**
		 * Value to comapre with
		 * @var mixed
		 */
		protected $mCompareTo  = null;
		
		/**
		 * Strict comparisons?
		 * @var boolean
		 */
		protected $bStrict = true;
		
		/**
		 * Class constructor
		 * @param mixed $mCompareTo Value to compare with
		 * @param booelan $bStrict If true uses strict mode to comparison
		 */
		public function __construct($mCompareTo = null, $bStrict = true){
			
			$this->mCompareTo = $mCompareTo;
			
			$this->bStrict = (boolean)$bStrict;
			
		} // __construct
		
		/**
		 * (non-PHPdoc)
		 * @see Hornet\Flow\RuleEngine\Rules.AbstractRule::compare()
		 */
		protected function compare($mValue){

			return $this->bStrict ? $this->mCompareTo ===  $mValue : $this->mCompareTo ==  $mValue;
			
		} // compare
		
	} // class

} // namespace
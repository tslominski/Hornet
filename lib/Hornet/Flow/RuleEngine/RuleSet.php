<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine {
	
	use Countable;
	use Iterator;
	
	use Hornet\Flow\RuleEngine\Interfaces\RuleInterface;
	
   /**
	* Container for rules
	*
	* @author Tomasz Słomiński <tomasz@slominski.it>
	* @since 2011-11-04
	* @version 1.0
	* @package Flow
	*/	
	class RuleSet implements Countable, Iterator {
		
		/**
		 * Rules
		 * @var array of Hornet\Flow\RuleEngine\Interfaces\RuleInterface
		 */
		protected $aRules 	= array();
		
		/**
		 * Current rule
		 * @var integer
		 */
		protected $nCurrent = 0;
				
		/**
		 * Class constructor
		 * All arguments will be added as rules
		 * @param Hornet\Flow\RuleEngine\Interfaces\RuleInterface $oRule1,... (optional) list of rules
		 */
		public function __construct(){
			
			foreach (func_get_args() as $oRule){
				
				$this->addRule($oRule);
				
			} // foreach
			
		} // __construct
		
		/**
		 * Adds new rule
		 * @param Hornet\Flow\RuleEngine\Interfaces\RuleInterface $oRule
		 * @return Hornet\Flow\RuleEngine\RuleSet self
		 */
		public function addRule(RuleInterface $oRule){
			
			$this->aRules[] = $oRule;
			
			return $this;
			
		} // addRule
				
		/**
		 * (non-PHPdoc)
		 * @see Countable::count()
		 */
		public function count(){
			
			return count($this->aRules);
			
		}
		
		/**
		 * (non-PHPdoc)
		 * @see Iterator::rewind()
		 */
		public function rewind() {
			
			$this->nCurrent = 0;
		
		} // rewind
		
		/**
		 * (non-PHPdoc)
		 * @see Iterator::current()
		 * @return Hornet\Flow\RuleEngine\Interfaces\RuleInterface
		 */
		public function current() {
			
			return $this->aRules[$this->nCurrent];
		
		} // current
		
		/**
		 * (non-PHPdoc)
		 * @see Iterator::key()
		 * @return integer
		 */
		public function key() {
			
			return $this->nCurrent;
		
		} // key
		
		/**
		 * (non-PHPdoc)
		 * @see Iterator::next()
		 */
		public function next() {
			
			++$this->nCurrent;

		} // next
		
		/**
		 * (non-PHPdoc)
		 * @see Iterator::valid()
		 * @return boolean
		 */
		public function valid() {
			
			return isset($this->aRules[$this->nCurrent]);
		
		} // valid
			
	} // class
		
} // namespace
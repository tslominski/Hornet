<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine {
   
	use Hornet\Flow\RuleEngine\Exceptions\RuleEngineException;
	
	
   /**
	* Rule engine.
	*
	* @author Tomasz Słomiński <tomasz@slominski.it>
	* @since 2011-11-04
	* @version 1.0
	* @package Flow
	**/	
	class RuleEngine {

	# Which rule's product use
		const GET_FIRST 	= 1;
		const GET_LAST  	= 2;
		const GET_BEST		= 4;
		const GET_WORST		= 8;	
		const GET_FOLD_LEFT	= 16;
		const GET_FOLD_RIGHT= 32;
		const GET_ALL		= 64;

	# Use matching or non matching rules
		const MATCHING		= 512;
		const NOT_MATCHING	= 1024;
		
		/**
		 * Context
		 * @var mixed
		 */
		protected $mContext = null;
		
		/**
		 * Ruleset
		 * @var Hornet\Flow\RuleEngine\RuleSet
		 */
		protected $oRuleSet = null;
		
		/**
		 * Folding function
		 * Technially, it is not a folding (reducing) function, it is a wrapper for folding function
		 * (That's all about limitations of first-order functions in PHP ;/) 
		 * @var callable 
		 */
		protected $cFolding 	= 'array_sum';

		/**
		 * Comparing function, as used by usort
		 * @var callable
		 */
		protected $cComparator  = 'strcmp';
		
		/**
		 * Class constructor. Sets ruleset if given.
		 * @param Hornet\Flow\RuleEngine\RuleSet $oRuleSet Ruleset
		 */
		public function __construct($oRuleSet = null){
			
			if ($oRuleSet !== null){
				
				$this->setRuleSet($oRuleSet);
				
			} // if
			
		} // __construct

		/**
		 * Sets ruleset
		 * @param Hornet\Flow\RuleEngine\RuleSet $oRuleSet Ruleset
		 * @return Hornet\Flow\RuleEngine\RuleEngine Self
		 */
		public function setRuleSet(RuleSet $oRuleSet){
				
			$this->oRuleSet = $oRuleSet;
				
			return $this;
		
		} // setRuleSet
		
		/**
		 * Sets context
		 * @param mixed $mContext
		 * @return Hornet\Flow\RuleEngine\RuleEngine Self
		 */
		public function setContext($mContext){
			
			$this->mContext = $mContext;
			
			return $this;
			
		} // setContext
 		
		/**
		 * Sets folding callback, used to fold matching rules products into one value
		 * @param callable $cFolding Folding callback
		 * @throws Hornet\Flow\RuleEngine\Exceptions\RuleEngineException If argument is not callable
		 * @return Hornet\Flow\RuleEngine\RuleEngine Self
		 */
		public function setFolding($cFolding){
			
			if (is_callable($cFolding)){
				
				$this->cFolding = $cFolding;
				
			} else {
				
				throw new RuleEngineException(sprintf(RuleEngineException::MSG_INVALID_FOLDING, gettype($cFolding)), RuleEngineException::EX_INVALID_FOLDING);
				
			} // if
			
			return $this;
			
		} // setFolding
		
		/**
		 * Sets comparator callback, used to select best/worst product
		 * @param callable $cComparator Comparator callback (as in usort)
		 * @throws Hornet\Flow\RuleEngine\Exceptions\RuleEngineException If argument is not callable
		 * @return Hornet\Flow\RuleEngine\RuleEngine Self
		 */		
		public function setComparator($cComparator){
				
			if (is_callable($cComparator)){
		
				$this->cComparator = $cComparator;
		
			} else {
				
				throw new RuleEngineException(sprintf(RuleEngineException::MSG_INVALID_COMPARATOR, gettype($cComparator)), RuleEngineException::EX_INVALID_COMPARATOR);
				
			} // if
				
			return $this;
				
		} // setComparator
	
		/**
		 * Tests whether at least one rule matches context
		 * @return boolean True if at leas one rule matches context
		 */
		public function some(){
			
			foreach ($this->oRuleSet as $oRule){
			
				if ($oRule->match($this->mContext)){
					
					return true;
					
				} // if
					
			} // foreach
			
			return false;
			
		} // some
		
		/**
		 * Tests whether all rules are matching context
		 * @return boolean True if all rules are matching
		 */
		public function all(){
				
			foreach ($this->oRuleSet as $oRule){
					
				if (!$oRule->match($this->mContext)){
						
					return false;
						
				} // if
					
			} // foreach
				
			return true;
				
		} // all
		
		/**
		 * Tests whether there is no rule matching context
		 * @return boolean True if any rule is matching
		 */
		public function any(){
				
			return !$this->some();
		
		} // any
				
		/**
		 * Return number of rules matching context
		 * @return integer Number of rules matching
		 */
		public function count(){
			
			$nResult = 0;
			
			foreach ($this->oRuleSet as $oRule){
					
				if ($oRule->match($this->mContext)){
						
					$nResult++;
						
				} // if
					
			} // foreach
				
			return $nResult;
					
		} // count
		
		/**
		 * Tests if there is at least N rules matching
		 * @param integer $nCount Minimal number of matching rules
		 * @return boolean True if number of matching rules is ge than passed argument
		 */
		public function min($nCount){
						
			return (int)$nCount <= $this->count();
				
		} // min
		
		/**
		 * Tests if there is maximum N rules matching
		 * @param integer $nCount Maximal number of matching rules
		 * @return boolean True if number of matching rules is le than passed argument
		 */		
		public function max($nCount){
		
			return (int)$nCount >= $this->count();
		
		} // max

		/**
 		 * Tests if there is exactly N rules matching
		 * @param integer $nCount Exact number of matching rules
		 * @return boolean True if number of matching rules equals argument
				 */
		public function exactly($nCount){
		
			return (int)$nCount == $this->count();
		
		} // exactly
		
		/**
		 * Gets product from every matching rule, and calculates return according to mode. Possible modes are:
		 * 	self::GET_FIRST 		- returns product of first matching rule
		 *  self::GET_LAST  		- returns product of last matching rule
		 *  self::GET_BEST  		- returns best product (defined as first in result of sorting all products using self::$cComparator)
		 *  self::GET_WORST 		- returns worst product
		 *  self::GET_FOLD_LEFT		- returns result of folding function (such as array_sum or array_product) called on all products' array
		 *  self::GET_FOLD_RIGHT	- returns result of folding function applicated tu reversed array of products
		 *  self::GET_GET_ALL		- returns array with products of all matching rules
		 * @param integer $nMode Defines way of calculating product
		 * @param integer $nMatching By default, matching rules are accounted. If equals self::NOT_MATCHING, behavior is reversed
		 * @param mixed $mDefaultReturn Return if any rule doesn't match
		 * @return mixed Calculated product of matching rules
		 */
		public function resolve($nMode = self::GET_FIRST, $nMatching = self::MATCHING, $mDefaultReturn = null){
			
			$aProducts = array();
			
			$bExpectedResult = ($nMatching === self::MATCHING);
			
			foreach ($this->oRuleSet as $oRule){
				
				if ($oRule->match($this->mContext) === $bExpectedResult){
					
					$aProducts[] = $oRule->getProduct();
					
					if ($nMode === self::GET_FIRST){
						
						break;
						
					} // if
					
				} // if
					
			} // foreach

			return $this->getProduct($nMode, $aProducts, $mDefaultReturn);
			
		} // resolve
		
		/**
		 * Gets product of matching rules according to $nMode parameter
		 * @param integer $nMode
		 * @param array $aProducts
		 * @param mixed $mDefaultReturn
		 * @return mixed Product
		 */
		protected function getProduct($nMode, $aProducts, $mDefaultReturn){
			
			$mReturn   = $mDefaultReturn;
			
			switch ($nMode){
			
				case self::GET_FIRST:
				case self::GET_LAST:
							
					if (count($aProducts) > 0){
			
						$mReturn = $nMode === self::GET_FIRST ? reset($aProducts) : end($aProducts);
			
					} // if				
			
				break;

				case self::GET_BEST:
				case self::GET_WORST:
				
					if (count($aProducts) > 0) {
						
						usort($aProducts, $this->cComparator);
						
						$mReturn = ($nMode === self::GET_BEST ? reset($aProducts) : end($aProducts));
						
					} // if
							
				break;
				
				case self::GET_FOLD_LEFT:
				case self::GET_FOLD_RIGHT:
					
					$mReturn = call_user_func($this->cFolding, $nMode === self::GET_FOLD_LEFT ? $aProducts : array_reverse($aProducts));
				
				break;
								
				case self::GET_ALL:
					
					$mReturn = $aProducts;
				
				break;				
				
			} // switch
						
			return $mReturn;
			
		} // getProduct
		
	} // class
	
} // namespace
<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Flow\RuleEngine\Rules {

	use Hornet\Flow\RuleEngine\RuleEngine;

	use Hornet\Flow\RuleEngine\RuleSet;

	use Hornet\Flow\RuleEngine\Rules\EqualRule;

	use PHPUnit_Framework_TestCase;
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'ExtractorInterface.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'RuleInterface.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Exceptions', 'RuleEngineException.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Extractors', 'PropertyExtractor.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Rules', 'AbstractRule.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Rules', 'EqualRule.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'RuleSet.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'RuleEngine.php'));
	
	class RuleEngineTest extends PHPUnit_Framework_TestCase {
		
		protected function getRuleSet($aRules){
			
			$oRuleSet = new RuleSet();
			
			foreach ($aRules as $mProduct=>$mRuleValue){
				
				$oRule = new EqualRule($mRuleValue);
				$oRule->setProduct($mProduct);
				$oRuleSet->addRule($oRule);
				
			}

			return $oRuleSet;
						
		}
		
		
		public function testBasicSelectorsAreWorkingProperly(){
			
			$oRuleEngine = new RuleEngine($this->getRuleSet(array(1,2,2,3,3,3)));
			
			$oRuleEngine->setContext(2);
			$this->assertEquals(2, $oRuleEngine->count());
			$this->assertTrue($oRuleEngine->some());
			$this->assertFalse($oRuleEngine->all());
			$this->assertFalse($oRuleEngine->any());
			$this->assertTrue($oRuleEngine->exactly(2));
			$this->assertTrue($oRuleEngine->min(2));
			$this->assertTrue($oRuleEngine->max(2));
			$this->assertTrue($oRuleEngine->min(1));
			$this->assertTrue($oRuleEngine->max(3));				
			$this->assertFalse($oRuleEngine->min(3));
			$this->assertFalse($oRuleEngine->max(1));

			$oRuleEngine = new RuleEngine($this->getRuleSet(array(2,2,2)));
				
			$oRuleEngine->setContext(2);
			$this->assertEquals(3, $oRuleEngine->count());
			$this->assertTrue($oRuleEngine->some());
			$this->assertTrue($oRuleEngine->all());
			$this->assertFalse($oRuleEngine->any());	

			$oRuleEngine = new RuleEngine($this->getRuleSet(array(1,3,4)));
			
			$oRuleEngine->setContext(2);
			$this->assertEquals(0, $oRuleEngine->count());
			$this->assertFalse($oRuleEngine->some());
			$this->assertFalse($oRuleEngine->all());
			$this->assertTrue($oRuleEngine->any());
			$this->assertTrue($oRuleEngine->exactly(0));
			$this->assertTrue($oRuleEngine->min(0));
			$this->assertTrue($oRuleEngine->max(0));
			$this->assertFalse($oRuleEngine->min(1));
				
		}
		
		public function testResolveFirstAndLastIsWorkingProperly(){
			
			$oRuleEngine = new RuleEngine($this->getRuleSet(array(1,2,2,3,3,3)));
				
			$oRuleEngine->setContext(3);
			
			$this->assertEquals(3, $oRuleEngine->resolve(RuleEngine::GET_FIRST));
			$this->assertEquals(5, $oRuleEngine->resolve(RuleEngine::GET_LAST));			
			$this->assertEquals(0, $oRuleEngine->resolve(RuleEngine::GET_FIRST, RuleEngine::NOT_MATCHING));
			$this->assertEquals(2, $oRuleEngine->resolve(RuleEngine::GET_LAST, RuleEngine::NOT_MATCHING));			
			
			
		}

		public function testResolveFoldingIsWorkingProperly(){
				
			$oRuleEngine = new RuleEngine($this->getRuleSet(array(0=>1,1=>2,2=>2,10=>3,3=>3,4=>3)));
		
			$oRuleEngine->setContext(3);
				
			$this->assertEquals(17, $oRuleEngine->resolve(RuleEngine::GET_FOLD_LEFT));
			$this->assertEquals(3, $oRuleEngine->resolve(RuleEngine::GET_FOLD_LEFT, RuleEngine::NOT_MATCHING));
			$this->assertEquals(17, $oRuleEngine->resolve(RuleEngine::GET_FOLD_RIGHT));
			$this->assertEquals(3, $oRuleEngine->resolve(RuleEngine::GET_FOLD_RIGHT, RuleEngine::NOT_MATCHING));
							
			
			$oRuleEngine->setFolding(function($a){
				$s = array_shift($a); 
					return array_reduce($a, function($v, $w){return $v-$w;}, $s); });
			 
			$this->assertEquals(3, $oRuleEngine->resolve(RuleEngine::GET_FOLD_LEFT));
			$this->assertEquals(-3, $oRuleEngine->resolve(RuleEngine::GET_FOLD_LEFT, RuleEngine::NOT_MATCHING));
			$this->assertEquals(-9, $oRuleEngine->resolve(RuleEngine::GET_FOLD_RIGHT));
			$this->assertEquals(1, $oRuleEngine->resolve(RuleEngine::GET_FOLD_RIGHT, RuleEngine::NOT_MATCHING));
						
			
				
		}
		
		public function testResolveAllIsWorkingProperly(){
				
			$oRuleEngine = new RuleEngine($this->getRuleSet(array(1,2,2,3,3,3)));
		
			$oRuleEngine->setContext(3);
				
			$this->assertEquals(array(3,4,5), $oRuleEngine->resolve(RuleEngine::GET_ALL));
			$this->assertEquals(array(0,1,2), $oRuleEngine->resolve(RuleEngine::GET_ALL, RuleEngine::NOT_MATCHING));
				
				
		}	
		
		public function testResolveBestAndWorstIsWorkingProperly(){
				
			$oRuleEngine = new RuleEngine($this->getRuleSet(array(2=>1,1=>3,0=>2,3=>2,4=>3,9=>3,6=>3)));
			
			$oRuleEngine
				->setContext(3)
				->setComparator(function($a, $b){return $a === $b ? 0 : ($a > $b ? -1 : 1);});
				
			$this->assertEquals(9, $oRuleEngine->resolve(RuleEngine::GET_BEST));
			$this->assertEquals(1, $oRuleEngine->resolve(RuleEngine::GET_WORST));
			$this->assertEquals(3, $oRuleEngine->resolve(RuleEngine::GET_BEST, RuleEngine::NOT_MATCHING));
			$this->assertEquals(0, $oRuleEngine->resolve(RuleEngine::GET_WORST, RuleEngine::NOT_MATCHING));
				
				
		}
		
		public function testDefaultValueIsReturnedIfThereIsNoMatch(){
				
			$oRuleEngine = new RuleEngine($this->getRuleSet(array(1,2,2,3,3,3)));
		
			$oRuleEngine->setContext(4);
			$this->assertTrue($oRuleEngine->any());
			$this->assertEquals(null, $oRuleEngine->resolve());
			$this->assertEquals(42, $oRuleEngine->resolve(RuleEngine::GET_FIRST, RuleEngine::MATCHING, 42));
				
			
		}
		
		/**
		 * @expectedException Hornet\Flow\RuleEngine\Exceptions\RuleEngineException
		 */
		public function testExceptionIsThrownIfComparatorIsNotCallable(){
			
			$oRuleEngine = new RuleEngine($this->getRuleSet(array(1,2,3)));
				
			$oRuleEngine
			->setComparator('fake_function');
			
		}
		
		/**
		* @expectedException Hornet\Flow\RuleEngine\Exceptions\RuleEngineException
		*/
		public function testExceptionIsThrownIfFoldingIsNotCallable(){
				
			$oRuleEngine = new RuleEngine($this->getRuleSet(array(1,2,3)));
		
			$oRuleEngine
			->setFolding('fake_function');
				
		}			
			
		
	}
		
}
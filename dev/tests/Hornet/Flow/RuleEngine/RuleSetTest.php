<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Flow\RuleEngine\Rules {

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
	
	class RuleSetTest extends PHPUnit_Framework_TestCase {
		
		public function testRuleSetAddsRules(){
			
			$oRule1 = new EqualRule(1);
			$oRule2 = new EqualRule(2);
			$oRule3 = new EqualRule(3);
				
			$oRuleSet = new RuleSet($oRule1, $oRule2);
			
			$oRuleSet->addRule($oRule3);
			
			$this->assertEquals($oRule1, $oRuleSet->current());
			$oRuleSet->next();			
			$this->assertEquals($oRule2, $oRuleSet->current());
			$oRuleSet->next();
			$this->assertEquals($oRule3, $oRuleSet->current());
				
				
		}
		
		public function testRuleSetIsCountable(){
				
			$oRule1 = new EqualRule(1);
			$oRule2 = new EqualRule(2);
			$oRule3 = new EqualRule(3);
		
			$oRuleSet = new RuleSet($oRule1);
				
			$oRuleSet
				->addRule($oRule2)
				->addRule($oRule3);
				
			$this->assertEquals(3, count($oRuleSet));
		
		}		
		public function testRuleSetIsIterable(){
		
			$aRules = array(			
			new EqualRule(1),
			new EqualRule(2),
			new EqualRule(3));
		
			$oRuleSet = new RuleSet();
			
			foreach ($aRules as $oRule){
				$oRuleSet
				->addRule($oRule);
			}

			foreach ($oRuleSet as $nIndex=>$oRule){
				
				$this->assertEquals($aRules[$nIndex], $oRule);
				
			}
			
		
		
		
		}		
		
	}
		
}
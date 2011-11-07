<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Flow\RuleEngine\Rules {

	use Hornet\Flow\RuleEngine\Rules\CompositeRule;

	use Hornet\Flow\RuleEngine\Rules\EqualRule;
	use Hornet\Flow\RuleEngine\RuleSet;
	
	use stdClass;

	use PHPUnit_Framework_TestCase;
	
	use Hornet\Flow\RuleEngine\Extractors\PropertyExtractor;
	
	use Hornet\Flow\RuleEngine\Rules\CallbackRule;
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'ExtractorInterface.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'RuleInterface.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Exceptions', 'RuleEngineException.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Extractors', 'PropertyExtractor.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'RuleSet.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'RuleEngine.php'));	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Rules', 'AbstractRule.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Rules', 'EqualRule.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Rules', 'CompositeRule.php'));
	
	class CompositelRuleTest extends PHPUnit_Framework_TestCase {
		
		protected function getRuleSet($aRules){
				
			$oRuleSet = new RuleSet();
				
			foreach ($aRules as $mProduct=>$mRuleValue){
		
				$oRule = new EqualRule($mRuleValue);
				$oRule->setProduct($mProduct);
				$oRuleSet->addRule($oRule);
		
			}
		
			return $oRuleSet;
		
		}
		
		public function testCompositeRuleWorksProperly(){
			
			$oRuleSet = $this->getRuleSet(array(1,2,2,3,3,3));
			
			$oRule = new CompositeRule($oRuleSet);
			
			$this->assertFalse($oRule->match(2));

			$oRule = new CompositeRule($oRuleSet, CompositeRule::SOME);
				
			$this->assertTrue($oRule->match(2));			
			
			$oRule = new CompositeRule($oRuleSet, CompositeRule::EXACTLY, 2);
			
			$this->assertTrue($oRule->match(2));

			$oRule = new CompositeRule($oRuleSet, CompositeRule::ANY);
				
			$this->assertTrue($oRule->match(4));
		} 
		
		/**
		* @expectedException Hornet\Flow\RuleEngine\Exceptions\RuleEngineException
		*/
		public function testCompositeRuleThrowsExceptionIfCallbackIsNotCallable(){
				
			$oRuleSet = $this->getRuleSet(array(1,2,2,3,3,3));
				
			$oRule = new CompositeRule($oRuleSet, 'nonexistant');
		}		
	} // class
	
} // namespace
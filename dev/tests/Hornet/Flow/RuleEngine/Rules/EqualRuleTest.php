<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Flow\RuleEngine\Rules {

	use Hornet\Flow\RuleEngine\Rules\EqualRule;

	use stdClass;

	use PHPUnit_Framework_TestCase;
	
	use Hornet\Flow\RuleEngine\Extractors\PropertyExtractor;
	
	use Hornet\Flow\RuleEngine\Rules\CallbackRule;
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'ExtractorInterface.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'RuleInterface.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Exceptions', 'RuleEngineException.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Extractors', 'PropertyExtractor.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Rules', 'AbstractRule.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Rules', 'EqualRule.php'));
	
	class EqualRuleTest extends PHPUnit_Framework_TestCase {
	
		public function testEqualRuleWorksProperly(){
			
			$oRule = new EqualRule(1);
			
			$this->assertTrue($oRule->match(1));
			$this->assertFalse($oRule->match(true));
				
			$oRule = new EqualRule(1, false);
			$this->assertTrue($oRule->match(1));
			$this->assertTrue($oRule->match(true));
			
		} 
		
	} // class
	
} // namespace
<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Flow\RuleEngine\Rules {

	use Hornet\Flow\RuleEngine\RuleFactory;

	use stdClass;

	use PHPUnit_Framework_TestCase;
	
	use Hornet\Flow\RuleEngine\Extractors\PropertyExtractor;
	
	use Hornet\Flow\RuleEngine\Rules\CallbackRule;
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'ExtractorInterface.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'RuleInterface.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Exceptions', 'RuleEngineException.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Extractors', 'PropertyExtractor.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Rules', 'AbstractRule.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Rules', 'CallbackRule.php'));
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'RuleFactory.php'));
	
	class RuleFactoryTest extends PHPUnit_Framework_TestCase {
		
		public function testRuleFactoryCreatesRuleProperly(){
			
			$oRule = RuleFactory::create('CallbackRule', function($mValue){return $mValue;});
			
			$this->assertInstanceOf('Hornet\Flow\RuleEngine\Rules\CallbackRule', $oRule);
						
			$this->assertTrue($oRule->match(true));
			$this->assertFalse($oRule->match(false));
				
		}
		
		
	}
		
}
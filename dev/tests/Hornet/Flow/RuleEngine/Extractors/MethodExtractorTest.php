<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Flow\RuleEngine\Extractors {

	use stdClass;
	
	use PHPUnit_Framework_TestCase;
	
	use Hornet\Flow\RuleEngine\Extractors\MethodExtractor;
		
	require_once implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', '..', '..', 'bootstrap.php'));
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'ExtractorInterface.php'));
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Extractors', 'MethodExtractor.php'));

	class MethodExtractorTest extends PHPUnit_Framework_TestCase {
		
		public function testMethodExtractorExtractsValue(){
			
			$oMock = $this->getMock('Test\Hornet\Flow\RuleEngine\Extractors\SomeClass', array('method1'));
			
			$oMock->expects($this->any())
			->method('method1')
			->will($this->returnValue('value1'));

			$oExtractor = new MethodExtractor('method1');

			$this->assertEquals('value1', $oExtractor($oMock));
		
		}
		
		public function testMethodExtractorPassesArgsToCalledMethod(){
				
			$oMock = $this->getMock('Test\Hornet\Flow\RuleEngine\Extractors\SomeClass', array('method1'));
				
			$oMock->expects($this->any())
			->method('method1')
			->will($this->returnArgument(1));
		
			$oExtractor = new MethodExtractor('method1', array('value0', 'value1'));
				
			$this->assertEquals('value1', $oExtractor($oMock));
		
		}		
		
		
		
		public function testMethodExtractorFallbacksToDefaultValue(){
		
			$oObject = new stdClass();
				
			$oObject->key1 = 'value1';
		
			$oExtractor = new MethodExtractor('method2');
				
			$this->assertEquals(null, $oExtractor($oObject));
		
			$oExtractor = new MethodExtractor('method2', array(), 'default_value');
			
			$this->assertEquals('default_value', $oExtractor($oObject));
		}
		
	}  //  class

}
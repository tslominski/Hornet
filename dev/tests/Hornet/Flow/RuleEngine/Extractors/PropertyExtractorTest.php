<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Flow\RuleEngine\Extractors {

	use stdClass;

	use PHPUnit_Framework_TestCase;
	
	use Hornet\Flow\RuleEngine\Extractors\PropertyExtractor;
	
	require_once implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', '..', '..', 'bootstrap.php'));
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'ExtractorInterface.php'));
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Extractors', 'PropertyExtractor.php'));

	class PropertyExtractorTest extends PHPUnit_Framework_TestCase {
		
		public function testPropertyExtractorExtractsValue(){
		
			$oObject = new stdClass();
			
			$oObject->key1 = 'value1';

			$oExtractor = new PropertyExtractor('key1');
			
			$this->assertEquals('value1', $oExtractor($oObject));
		
		}
		
		public function testPropertyExtractorFallbacksToDefaultValue(){
		
			$oObject = new stdClass();
				
			$oObject->key1 = 'value1';
		
			$oExtractor = new PropertyExtractor('key2');
				
			$this->assertEquals(null, $oExtractor($oObject));
		
			$oExtractor = new PropertyExtractor('key2', 'default_value');
			
			$this->assertEquals('default_value', $oExtractor($oObject));
		}
		
	}  //  class

} // namespace
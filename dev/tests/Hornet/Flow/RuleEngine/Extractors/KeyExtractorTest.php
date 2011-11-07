<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Flow\RuleEngine\Extractors {

	use ArrayObject;
	
	use ArrayIterator;
	
	use PHPUnit_Framework_TestCase;
	
	use Hornet\Flow\RuleEngine\Extractors\KeyExtractor;
	
	require_once implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', '..', '..', 'bootstrap.php'));
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Interfaces', 'ExtractorInterface.php'));
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'Flow', 'RuleEngine', 'Extractors', 'KeyExtractor.php'));

	class KeyExtractorTest extends PHPUnit_Framework_TestCase {
		
		public function testPropertyExtractorExtractsValueFromArray(){
		
			$aData = array('key1' => 'value1');
			
			$oExtractor = new KeyExtractor('key1');
			
			$this->assertEquals('value1', $oExtractor($aData));
		
		}
		
		public function testKeyExtractorExtractsValueFromArrayLikeObjects(){
		
			$oData = new ArrayIterator(array('key1' => 'value1'));
				
			$oExtractor = new KeyExtractor('key1');
				
			$this->assertEquals('value1', $oExtractor($oData));

			$oData = new ArrayObject(array('key1' => 'value1'));
			
			$oExtractor = new KeyExtractor('key1');
			
			$this->assertEquals('value1', $oExtractor($oData));
			
		}
				
		
		public function testKeyExtractorFallbacksToDefaultValue(){
		
			$aData = array('key1' => 'value1');
			
			$oExtractor = new KeyExtractor('key2');
			
			$this->assertEquals(null, $oExtractor($aData));
		
			$oExtractor = new KeyExtractor('key2', 'default_value');
			
			$this->assertEquals('default_value', $oExtractor($aData));

			$oData = new ArrayIterator(array('key1' => 'value1'));
			
			$oExtractor = new KeyExtractor('key2');
			
			$this->assertEquals(null, $oExtractor($oData));
			
			$oData = new ArrayObject(array('key1' => 'value1'));
				
			$oExtractor = new KeyExtractor('key2', 'default_value');
				
			$this->assertEquals('default_value', $oExtractor($oData));		
		
		}
		
	}  //  class

}
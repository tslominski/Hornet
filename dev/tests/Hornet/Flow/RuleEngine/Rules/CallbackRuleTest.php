<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Flow\RuleEngine\Rules {

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
	
	class CallbackRuleTest extends PHPUnit_Framework_TestCase {
	
		public function testCallbackRuleWorksProperly(){
	
			$oContext = new stdClass();
				
			$oContext->key1 = 'value1';
	
			$oExtractor = new PropertyExtractor('key1');

			$cCallback = function($mValue){return $mValue === 'value1';};
			
			$oRule = new CallbackRule($cCallback);
			
			$oRule
				->setExtractor($oExtractor);
			
			$this->assertTrue($oRule->match($oContext));
			
		} 
		
		/**
		 * @expectedException Hornet\Flow\RuleEngine\Exceptions\RuleEngineException
		 */
		public function testCallbackRuleThrowsExceptionIfExtractorIsNotCallable(){
			
			$cCallback = function($mValue){
				return $mValue === 'value1';
			};
				
			$oRule = new CallbackRule($cCallback);
				
			$oRule
			->setExtractor(array('nonexistantclass', '__call'));
			
		}
		
		/**
		 * @expectedException Hornet\Flow\RuleEngine\Exceptions\RuleEngineException
		 */
		public function testCallbackRuleThrowsExceptionIfCallbackIsNotCallable(){
			
			$oRule = new CallbackRule(array('nonexistantclass', '__call'));

		} 

		public function testCallbackRuleCastsCallbackReturnToBoolean(){
		
			$cCallback = function($mValue){
				return 1;
			};
				
			$oRule = new CallbackRule($cCallback);
				
			$mResult = $oRule->match(null);
			
			$this->assertTrue($mResult);
				
			$this->assertInternalType('boolean', $mResult);

			$cCallback = function($mValue){
				return 0;
			};
			
			$oRule = new CallbackRule($cCallback);
			
			$mResult = $oRule->match(null);
			
			$this->assertFalse($mResult);
							
			$this->assertInternalType('boolean', $mResult);
			
			
		}
		
		
		public function testProductIsSetAndGetProperly(){
		
			$cCallback = function($mValue){return 1;};
		
			$oRule = new CallbackRule($cCallback);		
		
			$this->assertEquals(null, $oRule->getProduct());
		
			$oRule->setProduct(1);
			
			$this->assertEquals(1, $oRule->getProduct());
				
		}
		
		
		/**
		* @expectedException Hornet\Flow\RuleEngine\Exceptions\RuleEngineException
		*/
		public function testCallbackRuleThrowsExceptionIfProductCallbackIsNotCallable(){
				
			$cCallback = function($mValue){
				return $mValue === 'value1';
			};
			
			$oRule = new CallbackRule($cCallback);
		
			$oRule->setProductCallback(array('nonexistantclass', '__call'));
					
		}

		public function testProductCallbackIsReturned(){
		
			$cCallback = function($mValue){
				return $mValue === 'value1';
			};
				
			$oRule = new CallbackRule($cCallback);
		
			$oRule
				->setProduct(42)
				->setProductCallback(function($mContext){return 23;});
			
			$this->assertEquals(23, $oRule->getProduct(null));

			
			$oRule
				->setProductCallback(function($mContext){return $mContext;});
				
			$this->assertEquals(66, $oRule->getProduct(66));
			
			
		}		
		
	} // class
	
} // namespace
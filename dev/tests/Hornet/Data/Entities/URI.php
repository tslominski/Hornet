<?php

namespace Test\Hornet\Data\Entities {
	
	use PHPUnit_Framework_TestCase;
	use Hornet\Data\Entities\URI;
	
	require_once('/home/tomek/workspace/Hornet/lib/Hornet/Data/Entities/URI.php');
	
	class URITest extends PHPUnit_Framework_TestCase{	
		
		
		public static function providerOfGoodUri(){
			
			return array_map(function($sString){return array(trim($sString));}, file(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'uri_good.txt'));
			
		}
		
		
		/**
		 * Round trip
		 * @dataProvider providerOfGoodUri
		 */
		public function testUriIsRecreatedFromString($sURI){
						
			$oURI = new URI($sURI);
						
			$this->assertEquals($sURI, (string)$oURI, sprintf('URI <%s> re-created from string <%s> doesn\'t match source.', (string)$oURI, $sURI));
			
		}
		
		public function testSchemeIsChangedCorrectly(){

			$oURI = new URI('http://google.com');
			
			$oURI->setScheme('https');
			
			$this->assertEquals('https://google.com', (string)$oURI, 'Scheme is not changed properly');
			
		}
		
		public function testPortIsChangedCorrectly(){
		
			$oURI = new URI('http://google.com');
				
			$oURI->setPort(81);
				
			$this->assertEquals('http://google.com:81', (string)$oURI, 'Port is not changed properly');

			$oURI->setPort(82);
			
			$this->assertEquals('http://google.com:82', (string)$oURI, 'Port is not changed properly');
			
			$oURI->setPort('83');
			
			$this->assertEquals('http://google.com:83', (string)$oURI, 'Port is not changed properly');
						
			
		}		
		
	}
	
	
}
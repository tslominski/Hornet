<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Data\Entities {
	
	use PHPUnit_Framework_TestCase;
	use Hornet\Data\Entities\URI;
	
	require_once('/home/tomek/workspace/Hornet/lib/Hornet/Data/Entities/URI.php');
	
	class URITest extends PHPUnit_Framework_TestCase{	
		
		/**
		 * Good URIs 
		 */
		public static function providerOfGoodUri(){
			
			return array_map(function($sString){return array(trim($sString));}, file(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'uri_good.txt'));
			
		} // providerOfGoodUri
		
		
		/**
		 * Round trip
		 * @dataProvider providerOfGoodUri
		 */
		public function testUriIsRecreatedFromString($sURI){
						
			$oURI = new URI($sURI);
						
			$this->assertEquals($sURI, (string)$oURI, sprintf('URI <%s> re-created from string <%s> doesn\'t match source.', (string)$oURI, $sURI));
			
		}

		/**
		* Two methods of parsing trip
		* @dataProvider providerOfGoodUri
		*/
		public function testTwoParsingMethodsAreEquivallent($sURI){
		
			$oURIPhp = new URI();
	
			$oURIPhp->setOption(URI::OPT_USE_PHP_PARSER, true);
				
			$oURINative = new URI();
			
			$oURINative->setOption(URI::OPT_USE_PHP_PARSER, false);
		
			$this->assertEquals((string)$oURIPhp->fromString($sURI), (string)$oURINative->fromString($sURI), sprintf('URI <%s> goves not the same result parsed different ways', $sURI));
				
		}		
		
		/**
		 * @expectedException InvalidArgumentException 
		 */
		public function testFromStringAcceptStringsOnly(){
			
			$oURI = new URI();
			
			$oURI->fromString(1);
				
			
			
			
		}
		
		/**
		 * 
		 */
		public function testSchemeIsChangedCorrectly(){

			$oURI = new URI('http://google.com');
			
			$this->assertEquals('http', $oURI->getScheme(), 'Scheme is not changed properly');
				
			$oURI->setScheme('https');
			
			$this->assertEquals('https://google.com', (string)$oURI, 'Scheme is not changed properly');
			
			$this->assertEquals('https', $oURI->getScheme(), 'Scheme is not changed properly');
				
			
		}
		
		/**
		 * 
		 */
		public function testPortIsChangedCorrectly(){
		
			$oURI = new URI('http://google.com');

			$this->assertEquals(null, $oURI->getPort(), 'Returned port is not valid');
							
			$oURI->setPort(81);
				
			$this->assertEquals('http://google.com:81', (string)$oURI, 'Port is not changed properly');

			$this->assertEquals('81', $oURI->getPort(), 'Returned port is not valid');
			
			$oURI->setPort(82);
			
			$this->assertEquals('http://google.com:82', (string)$oURI, 'Port is not changed properly');
			
			$this->assertEquals('82', $oURI->getPort(), 'Returned port is not valid');
			
			$oURI->setPort('83');
			
			$this->assertEquals('http://google.com:83', (string)$oURI, 'Port is not changed properly');
			
			$this->assertEquals('83', $oURI->getPort(), 'Returned port is not valid');
			
		}		

		/**
		* @expectedException InvalidArgumentException
		*/
		public function testInvalidSchemeThrowsAnException(){
				
			$oURI = new URI('http://google.com');
								
			$oURI->setScheme('!66');
				
		}
		
		/**
		 * @expectedException InvalidArgumentException 
		 */
		public function testDisallowedSchemeThrowsAnException(){
			
			$oURI = new URI('http://google.com');
			
			$oURI->setOption(URI::OPT_ALLOWED_SCHEMES, array('http', 'https'));
			
			$oURI->setScheme('ftp');
			
		}
		
	}
	
	
}
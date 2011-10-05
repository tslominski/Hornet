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
		 * Good URI as array 
		 */
		public static function providerOfUriArray(){
			
			return array(
				array(
					array(
						URI::SCHEME 	=> 'https',
						URI::USERINFO 	=> 'user:password',
						URI::HOST		=> 'example.net',
						URI::PORT		=> '81',
						URI::PATH		=> '/p/a/t/h',
						URI::QUERY		=> 'key1=value1&key2=value2',
						URI::FRAGMENT	=> 'top'	
					), 
					'https://user:password@example.net:81/p/a/t/h?key1=value1&key2=value2#top'
				)					
			);
			
		}
		
		
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
		
		public function testSchemeIsChangedCorrectly(){

			$oURI = new URI('http://example.net');
			
			$this->assertEquals('http', $oURI->getScheme(), 'Scheme is not changed properly');
				
			$oURI->setScheme('https');
			
			$this->assertEquals('https://example.net', (string)$oURI, 'Scheme is not changed properly');
			
			$this->assertEquals('https', $oURI->getScheme(), 'Scheme is not changed properly');
				
		}
		
		public function testHostIsChangedCorrectly(){
			
			$oURI = new URI('http://example.net');
				
			$this->assertEquals('example.net', $oURI->getHost(), 'Host is not parsed properly');
			
			$oURI->setHost('example.com');
				
			$this->assertEquals('http://example.com', (string)$oURI, 'Host not changed properly');
				
			$this->assertEquals('example.com', $oURI->getHost(), 'Host is not changed properly');
			
		}

		/**
		* @expectedException InvalidArgumentException
		*/
		public function testInvalidHostThrowsAnException(){
		
			$oURI = new URI('http://example.net');
		
			$oURI->setHost('its@bad');
		
		}
		
		public function testUserInfoIsChangedCorrectly(){
			
			$oURI = new URI('http://example.net');
						
			$this->assertEquals(null, $oURI->getUserInfo(), 'User info should be null by default');

			$oURI = new URI('http://user:pass@example.net');
			
			$this->assertEquals('user:pass', $oURI->getUserInfo(), 'User info not parsed properly');
		
			$oURI->setUserInfo('somebody:something');
						
			$this->assertEquals('somebody:something', $oURI->getUserInfo(), 'User info not parsed properly');
									
			$this->assertEquals('http://somebody:something@example.net', (string)$oURI, 'User info is not changed properly');
			
			$oURI->setUserInfo(null);
									
			$this->assertEquals(null, $oURI->getUserInfo(), 'User info not parsed properly');
				
			$this->assertEquals('http://example.net', (string)$oURI, 'User info is not changed properly');
										
		}
						
		/**
		* @expectedException InvalidArgumentException
		*/
		public function testInvalidUserInfoThrowsAnException(){
		
			$oURI = new URI('http://example.net');
		
			$oURI->setUserInfo('its@bad');
		
		}
		
		/**
		* @expectedException InvalidArgumentException
		*/
		public function testInvalidPortThrowsAnException(){
		
			$oURI = new URI('http://example.net');
		
			$oURI->setPort('its@bad');
		
		}
		
		public function testPortIsChangedCorrectly(){
		
			$oURI = new URI('http://example.net');

			$this->assertEquals(null, $oURI->getPort(), 'Returned port is not valid');
							
			$oURI->setPort(81);
				
			$this->assertEquals('http://example.net:81', (string)$oURI, 'Port is not changed properly');

			$this->assertEquals('81', $oURI->getPort(), 'Returned port is not valid');
			
			$oURI->setPort(82);
			
			$this->assertEquals('http://example.net:82', (string)$oURI, 'Port is not changed properly');
			
			$this->assertEquals('82', $oURI->getPort(), 'Returned port is not valid');
			
			$oURI->setPort('83');
			
			$this->assertEquals('http://example.net:83', (string)$oURI, 'Port is not changed properly');
			
			$this->assertEquals('83', $oURI->getPort(), 'Returned port is not valid');
			
		}		
		
		public function testEmptyPortIsNotIncludedIntoUri(){
			
			$oURI = new URI('ftp://example.org:/resource.txt');
			
			$this->assertEquals(null, $oURI->getPort(), 'Returned port is not valid (native parser)');

			$oURI = new URI('ftp://example.org:/resource.txt', array(URI::OPT_USE_PHP_PARSER=>false));
				
			$this->assertEquals(null, $oURI->getPort(), 'Returned port is not valid (regexp parser)');
			
		}
		
		
		/**
		* @expectedException InvalidArgumentException
		*/
		public function testInvalidSchemeThrowsAnException(){
		
			$oURI = new URI('http://example.net');
		
			$oURI->setScheme('!66');
		
		}
		
		/**
		 * @expectedException InvalidArgumentException 
		 */
		public function testDisallowedSchemeThrowsAnException(){
			
			$oURI = new URI('http://example.net');
			
			$oURI->setOption(URI::OPT_ALLOWED_SCHEMES, array('http', 'https'));
			
			$oURI->setScheme('ftp');
			
		}
		
		public function testConfigArrayIsPassedProperlyInConstructor(){
			
			$oURI = new URI(null, array(URI::OPT_ALLOWED_SCHEMES=>array('gopher'), URI::OPT_USE_PHP_PARSER => false));
			
			$this->assertEquals(false, $oURI->getOption(URI::OPT_USE_PHP_PARSER), 'Option URI::OPT_USE_PHP_PARSER not passed properly via constructor');
			
			$this->assertEquals(array('gopher'), $oURI->getOption(URI::OPT_ALLOWED_SCHEMES), 'Option URI::OPT_ALLOWED_SCHEMES not passed properly via constructor');
				
		}
		
		/**
		 * @dataProvider providerOfUriArray
		 */
		public function testUriIsCreatedFromArray($aURI, $sExpectedURI){
			
			$oURI = new URI();
			
			$oURI->fromArray($aURI);
			
			$this->assertEquals($sExpectedURI, (string)$oURI, sprintf('URI is not created properly from array (%s expected, %s returned)', $sExpectedURI, (string)$oURI));
			
		}
		
		/**
		 * @dataProvider providerOfUriArray
		 */
		public function testUriIsCreatedFromArrayInConstructor($aURI, $sExpectedURI){
			
			$oURI = new URI($aURI);
			
			$this->assertEquals($sExpectedURI, (string)$oURI, sprintf('URI is not created properly from array (%s expected, %s returned)', $sExpectedURI, (string)$oURI));
			
		}

	} // class
	
} // namespace
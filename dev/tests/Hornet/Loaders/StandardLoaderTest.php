<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\Loaders {
	
	use PHPUnit_Framework_TestCase;
	use Hornet\Loaders\StandardLoader;
	
	require_once implode(DIRECTORY_SEPARATOR, array(__DIR__,'..','bootstrap.php'));
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH ,'Loaders','Interfaces', 'LoaderInterface.php'));
	
	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH ,'Loaders', 'StandardLoader.php'));
		
	class StandardLoaderTest extends PHPUnit_Framework_TestCase{
	
		public function testStandardLoaderRegistersItself(){
			
			$nCurrentAutoloadStackSize = count(spl_autoload_functions());
			
			$oLoader = new StandardLoader(__DIR__);
			
			$this->assertEquals($nCurrentAutoloadStackSize + 1, count(spl_autoload_functions()), 'Standard loader not added to stack');
			
			$oLoader->unregister();
			
			$this->assertEquals($nCurrentAutoloadStackSize, count(spl_autoload_functions()), 'Standard loader not removed from stack');
			
			$oLoader = new StandardLoader(__DIR__, '', false);
			
			$this->assertEquals($nCurrentAutoloadStackSize, count(spl_autoload_functions()), 'Standard added to stack');
				
			$oLoader->register();
			
			$this->assertEquals($nCurrentAutoloadStackSize + 1, count(spl_autoload_functions()), 'Standard loader not added to stack');
						
			$oLoader->unregister();
				
			$this->assertEquals($nCurrentAutoloadStackSize, count(spl_autoload_functions()), 'Standard loader not removed from stack');
			
		}

		public function testStandardLoaderLoadsAClassWhenAskedNicely(){
			
			$sSourceDir = implode(DIRECTORY_SEPARATOR, array(__DIR__, 'fixtures'));
			
			$oLoader = new StandardLoader($sSourceDir);
			
			$oLoader->loadClass('TestLibraryOne\\TestClassOne');

			$this->assertTrue(in_array('TestLibraryOne\\TestClassOne', get_declared_classes()));

			$oLoader->loadClass('TestLibraryOne\\Subpackage\\TestClassTwo');
			
			$this->assertTrue(in_array('TestLibraryOne\\Subpackage\\TestClassTwo', get_declared_classes()));
						
			$oLoader->unregister();
			
		}

		public function testStandardLoaderStripsNamespace(){
				
			$sSourceDir = implode(DIRECTORY_SEPARATOR, array(__DIR__, 'fixtures', 'TestLibraryTwo'));
				
			$oLoader = new StandardLoader($sSourceDir, 'TestLibraryTwo');
				
			$oLoader->loadClass('TestLibraryTwo\\TestClassOne');
		
			$this->assertTrue(in_array('TestLibraryTwo\\TestClassOne', get_declared_classes()));
		
			$oLoader->loadClass('TestLibraryTwo\\Subpackage\\TestClassTwo');
				
			$this->assertTrue(in_array('TestLibraryTwo\\Subpackage\\TestClassTwo', get_declared_classes()));
		
			$oLoader->unregister();
				
		}		
		
	} // class
	
} // namespace
	
	
	
<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Test\Hornet\System {

	use PHPUnit_Framework_TestCase;
	
	use Hornet\System\Fs;

	require_once implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'bootstrap.php'));

	require_once implode(DIRECTORY_SEPARATOR, array(HORNET_LIB_PATH , 'System','Fs.php'));

class FsTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests for Fs::path
	 */
	public function testPathIsGeneratedProperly(){

		$sPath = 'a'.DIRECTORY_SEPARATOR.'b'.DIRECTORY_SEPARATOR.'c';

		$this->assertEquals($sPath, Fs::path('a','b','c'));

		$sPath = 'a'.DIRECTORY_SEPARATOR.'b'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'c';

		$this->assertEquals($sPath, Fs::path('a','b',-2,'c'));

	} // testPath

	/**
	 * Tests for Fs::realpath
	 */
	public function testRealpathIsGeneratedProperly(){

		$this->assertEquals(__DIR__, Fs::realpath(__DIR__, -1, 'System'));

	} // testRealpath

	/**
	 * Tests for Fs::normalize
	 */
	public function testPathIsNormalizedProperly(){

		$sPath = 'a'.DIRECTORY_SEPARATOR.'b'.DIRECTORY_SEPARATOR.'c'.DIRECTORY_SEPARATOR.'d';

		$this->assertEquals($sPath, Fs::normalize('a//b/\/\/\/c\d'));

	} // testNormalize

	/**
	 * Tests fs::convertChmodToOct
	 */
	public function testChmodStringIsConvertedToOctalFrom(){

		$this->assertEquals('0600', Fs::convertChmodToOct('-rw-------'));
		$this->assertEquals('0644', Fs::convertChmodToOct('-rw-r--r--'));
		$this->assertEquals('0666', Fs::convertChmodToOct('-rw-rw-rw-'));
		$this->assertEquals('0700', Fs::convertChmodToOct('-rwx------'));
		$this->assertEquals('0755', Fs::convertChmodToOct('-rwxr-xr-x'));
		$this->assertEquals('0777', Fs::convertChmodToOct('-rwxrwxrwx'));
		$this->assertEquals('0711', Fs::convertChmodToOct('-rwx--x--x'));
		$this->assertEquals('0700', Fs::convertChmodToOct('drwx------'));
		$this->assertEquals('0744', Fs::convertChmodToOct('drwxr--r--'));

	}

	/**
	 * Tests exception in fs::convertChmodToOct
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfChmodStringIsTooShort(){

		Fs::convertChmodToOct('-rw------');

	}

	/**
	 * Tests exception in fs::convertChmodToOct
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfChmodStringIsTooLong(){

		Fs::convertChmodToOct('-rw---rwrwr');

	}

	/**
	 * Tests exception in fs::convertChmodToOct
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfChmodStringContainsBadCharacter(){

		Fs::convertChmodToOct('drwx-drwxa');

	}

	/**
	 * Tests exception in Fs::convertChmodToOct
	 * @expectedException InvalidArgumentException
	 */
	public function testExceptionIsThrownIfChmodStringContainsBadCharacterSecondVersion(){

		Fs::convertChmodToOct('-drwx-drwx');

	}

	} // class

} // namespace
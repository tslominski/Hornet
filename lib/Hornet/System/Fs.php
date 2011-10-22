<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\System {

	use InvalidArgumentException;

	/**
	 * Filesystem operations helpers
	 *
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2011-02-13
	 * @version 1.3
	 * @package System
	 */
	class Fs {

		protected static $aChmodTrans = array('-' => '0', 'r' => '4', 'w' => '2', 'x' => '1');

	# PUBLIC METHODS

		/**
		 * Joins parameters with DIRECTORY_SEPARATOR.
		 * If an element is an integer less than zero, is converted to a
		 * corresponding number of '..' so Fs::path('a',-2,'b') becomes a/../../b
		 * @param string|integer $_,... Path element to join.
		 * @return string Joined path
		 */
		public static function path(/* ... */){

			$aArgs = func_get_args();

			foreach ($aArgs as &$mArg){

				# argument of type -N makes us descend N directories towards root
				if (is_int($mArg) && $mArg<0){

					$mArg = implode(DIRECTORY_SEPARATOR, array_fill(0, - $mArg, '..'));

				} // if

			} // foreach

			return implode(DIRECTORY_SEPARATOR, $aArgs);

		} // path

		/**
		 * Joins parameters with DIRECTORY_SEPARATOR and normalizes path.
		 * See Fs::path for details.
		 * @param string|integer $_,... Path element to join.
		 * @return string|boolean Normalized path or false if path doesn't exists
		 */
		public static function realpath(/* ... */){

			return realpath(call_user_func_array(array('self', 'path'), func_get_args()));

		} // realpath

		/**
		 * Replaces \ and / with DIRECTORY_SEPARATOR, compresses multiple sirectory separators to one
		 * @param string $sPath
		 * @return string Normalized path
		 */
		public static function normalize($sPath){

			return preg_replace('![\/\\\]+!', DIRECTORY_SEPARATOR, $sPath);

		} // realpath

		/**
		 * Converts unix-format permissions string (-rwx) to octal representation
		 * To get a number, use base_convert(Fs::convertChmodToOct($sChmod),8,10));
		 * If string is not valid chmod, throws InvalidArgumentException
		 * @throws InvalidArgumentException
		 * @param string $sChmod (fe '-rwxrwxrwx')
		 * @return string Octal representation of permissions (fe. '0777')
		 */
		public static function convertChmodToOct($sChmod) {

			$sChmod = (string)$sChmod;

			if (strlen($sChmod) !== 10 || trim(substr($sChmod,0,1),'-d')!='' || trim(substr($sChmod,1),'-wrx')!=''){

				throw new InvalidArgumentException(sprintf('%s is not a valid chmod string', $sChmod));

			} // if

		    $aChmod = str_split(substr(strtr($sChmod, self::$aChmodTrans), 1), 3);

		    return '0' . (array_sum(str_split($aChmod[0])) . array_sum(str_split($aChmod[1])) . array_sum(str_split($aChmod[2])));

		} // convertChmodToOct

	} // class

} // namespace

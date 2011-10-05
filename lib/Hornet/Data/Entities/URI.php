<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Data\Entities {
	
	use \InvalidArgumentException;
	
	/**
	 * Uniform Resource Identifier representation and handling
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @see RFC 3986 <http://tools.ietf.org/html/rfc3986> 
	 * @version 1.0
	 */
	class URI {
		
	# CLASS CONSTANTS
		
		# URI elements names
		const SCHEME 	= 'scheme';
		const USERINFO 	= 'userinfo';
		const HOST	 	= 'host';
		const PORT 		= 'port';
		const PATH 		= 'path';
		const QUERY 	= 'query';
		const FRAGMENT 	= 'fragment';
		
		# Exception messages
		const EX_INVALID_ELEMENT	= "Invalid %s : %s";
		const EX_INVALID_TYPE		= "Type of %s is invalid - should be string instead of %s";
		const EX_SCHEME_NOT_ALLOWED = "Scheme %s is not allowed, try one of %s";
		const EX_INVALID_URI		= "%s is not a valid URI";

				
		# Validation regexps
		const SCHEME_VALIDATION_RE 		= '/^[[:alpha:]]+[[:alnum:]+\-.]*$/i';
		const HOST_VALIDATION_RE		= '/^([[:alnum:]\-._~!$&\'()*+,;=]|%[0-9a-fA-F]{2})*$/i';
		
		const USERINFO_VALIDATION_RE 	= '/^([[:alnum:]\-._~!$&\'()*+,;=:]|%[0-9a-fA-F]{2})*$/i';
		const FRAGMENT_VALIDATION_RE	= '/^([[:alnum:]\-._~!$&\'()*+,;=:@\/\?]|%[0-9a-fA-F]{2})*$/i';
		
		# Conversion regexp
		const FROM_STRING_RE		= '|^(?P<xscheme>(?P<scheme>[^:/?#]+):)?(?P<xauthority>//(?P<authority>([^/?#@]*@)?([^/?#]*)?(:\d+)?))?(?P<path>[^?#]*)?(?P<xquery>\?(?P<query>[^#]*))?(?P<xfragment>#(?P<fragment>.*))?|';
		
		# Config options
		const OPT_USE_PHP_PARSER 	= 'use_php_parser'; # use parse_url instead FROM_STRING_RE
		const OPT_ALLOWED_SCHEMES	= 'allowed_schemes'; # allowed schemes (in lowercase)
		
	# PROTECTED VARIABLES

		/**
		 * Validation cache
		 * @var array of arrays
		 */
		protected static $aCache = array(
			self::SCHEME_VALIDATION_RE		=> array(),
			self::USERINFO_VALIDATION_RE	=> array(),
		);
		
		/**
		 * URI elements. Note that where PHP uses separate user and password,
		 * we, in spirit of RFC, are using one field for both
		 * @var array
		 */
		protected $aData = array(
			self::SCHEME 	=> null,
			self::USERINFO 	=> null,
			self::HOST	 	=> null,
			self::PORT 		=> null,
			self::PATH 		=> null,
			self::QUERY 	=> null,
			self::FRAGMENT	=> null
		);

		/**
		 * Class options
		 * @var array
		 */
		protected $aConfig = array(
			self::OPT_USE_PHP_PARSER 	=> true,
			self::OPT_ALLOWED_SCHEMES   => array()
		);
		
	# MAGIC METHODS
		
		/**
		 * Class constructor
		 * @param string|array|null $mURI URI
		 * @param array|null $aConfig Optional array of configuration options.
		 */
		public function __construct($mURI = null, $aConfig = null){
			
			if (is_array($aConfig)){
				
				$this->aConfig = $aConfig + $this->aConfig;
				
			} // if
			
			if (is_string($mURI)){
				
				$this->fromString($mURI);
				
			} else if (is_array($mURI)) {
				
				$this->fromArray($mURI);
				
			} // if
			
		} // __construct
		
		/**
		 * Returns URI in form of string
		 * @return string 
		 */
		public function __toString(){

			return $this->asString();
			
		} // __toString
		
	# PUBLIC METHODS

		/**
		 * Sets config option 
		 * @param mixed $mOption Option name
		 * @param mixed $mValue Option value
		 * @return Hornet\Data\Entities\URI Self
		 */
		public function setOption($mOption, $mValue){
					
			$this->aConfig[$mOption] = $mValue;
					
			return $this;
			
		} // setOption
		
		/**
		* Gets config option's value
		* @param mixed $mOption Option name
		* @return mixed|value Option value or null
		*/
		public function getOption($mOption){
				
			return isset($this->aConfig[$mOption]) ?  $this->aConfig[$mOption] : null;
				
		} // getOption
				
		/**
		 * Initializes URI elements from array. See # URI elements names for
		 * list of allowed elements
		 * @param array $aURI URI elements
		 * @return Hornet\Data\Entities\URI Self
		 */
		public function fromArray($aURI){
						
			foreach ($this->aData as $sKey => $_){
				
				if (isset($aURI[$sKey])){
					
					$cCallback = array($this, 'set' . $sKey);
					
					call_user_func($cCallback, $aURI[$sKey]);
					
				} else {
					
					$this->aData[$sKey] = null;
					
				} // if
				
			} // foreach
			
			return $this;
			
		} // fromArray
		
		/**
		 * Populates object from well formed URI string
		 * @param string $sURI URI to parse
		 * @throws InvalidArgumentException
		 * @return Hornet\Data\Entities\URI Self
		 */
		public function fromString($sURI){
			
			if (!is_string($sURI)){
				
				throw new InvalidArgumentException(sprintf(self::EX_INVALID_TYPE, 'URI', gettype($sURI)), 1);
				
			} // if
			
			if ($this->aConfig[self::OPT_USE_PHP_PARSER]){
				
				$aResult = $this->parseFromStringNative($sURI);
				
			} else {
				
				$aResult = $this->parseFromStringRegexp($sURI);
				
			} // if
			
			$this->fromArray($aResult);
			
			return $this;
			
		} // fromString
		
		/**
		 * Returns URI as string, implementing pseudocode from RFC 3986
		 * @return string URI
		 */
		public function asString(){
			
			$aResult = array();
				
			if ($this->aData[self::SCHEME] !== null){
			
				$aResult[] = $this->aData[self::SCHEME];
				
				$aResult[] = ':';
			
			} // if
			
			if (!empty($this->aData[self::USERINFO]) || $this->aData[self::HOST] !== null || !empty($this->aData[self::PORT])){
								
				$aResult[] = '//';
				
			} // if

			if (!empty($this->aData[self::USERINFO])){
			
				$aResult[] = $this->aData[self::USERINFO];
				
				$aResult[] = '@';
			
			} // if
			
			if ($this->aData[self::HOST] !== null){
			
				$aResult[] = $this->aData[self::HOST]; 
			
			} // if
			
			if (!empty($this->aData[self::PORT])){
				
				$aResult[] = ':';
				
				$aResult[] = $this->aData[self::PORT];
					
			} // if

			if ($this->aData[self::PATH] !== null){

				$aResult[] = $this->aData[self::PATH];

			} // if
			
			if ($this->aData[self::QUERY] !== null){
				
				$aResult[] = '?';
				
				$aResult[] = $this->aData[self::QUERY];
			
			} // if			

			if ($this->aData[self::FRAGMENT] !== null){
			
				$aResult[] = '#';
			
				$aResult[] = $this->aData[self::FRAGMENT];
					
			} // if			
			
			return implode('', $aResult);			
			
		} // asString
	
		/**
		 * Sets scheme if it is valid. Converts it to lowercase.
		 * @param string $sScheme
		 * @throws InvalidArgumentException
		 * @return Hornet\Data\Entities\URI Self
		 */
		public function setScheme($sScheme = ''){

			if ($this->isValidScheme($sScheme)){
				
				$sScheme = mb_strtolower($sScheme);
				
				if ($this->isAllowedScheme($sScheme)){
				
					$this->aData[self::SCHEME] = $sScheme;
				
				} else {
					
					throw new InvalidArgumentException(sprintf(self::EX_SCHEME_NOT_ALLOWED, $sScheme, implode(', ', $this->aConfig[self::OPT_ALLOWED_SCHEMES])), 2);
					
				} // if
				
			} else {
				
				throw new InvalidArgumentException($this->getExceptionMessage(self::SCHEME, $sScheme), 3);
				
			} // if
			
			return $this;
			
		} // setScheme
		
		/**
		 * Gets current scheme
		 * @return string|null Current scheme
		 */
		public function getScheme(){
			
			return $this->aData[self::SCHEME];
			
		} // getScheme
		
		/**
		 * Tests whether scheme is valid. Caches result for better efficiency.
		 * @param string $sScheme Scheme to verify
		 * @return boolean True if scheme is valid.
		 */
		public function isValidScheme($sScheme){
			
			return $this->isValidElement($sScheme, self::SCHEME_VALIDATION_RE);
			
		} // isValidScheme
		
		/**
		 * Tests whether given scheme is allowed
		 * @param string $sScheme Scheme to verify
		 * @return boolean True if scheme is allowed
		 */
		public function isAllowedScheme($sScheme){
			
			return empty($this->aConfig[self::OPT_ALLOWED_SCHEMES]) || (is_array($this->aConfig[self::OPT_ALLOWED_SCHEMES]) && in_array($sScheme, $this->aConfig[self::OPT_ALLOWED_SCHEMES]));
			
		} // isAllowedScheme
		
		/**
		 * Sets user info
		 * @param string|null $mUserInfo
		 * @throws InvalidArgumentException
		 * @return Hornet\Data\Entities\URI Self
		 */
		public function setUserInfo($mUserInfo = ''){
			
			if ($mUserInfo === null || $this->isValidUserInfo($mUserInfo)){
				
				$this->aData[self::USERINFO] = $mUserInfo;
				
			} else {
				
				throw new InvalidArgumentException($this->getExceptionMessage(self::USERINFO, $mUserInfo), 4);
								
			} // if
					
			return $this;
				
		} // setUserInfo
		
		/**
		 * Returns current user info
		 * @return string|null User info
		 */
		public function getUserInfo(){
			
			return $this->aData[self::USERINFO];
			
		} // getUserInfo
		
		/**
		* Tests whether user info is valid. Caches result for better efficiency.
		* @param string $sUserInfo User info to verify
		* @return boolean True if user info is valid.
		*/		
		public function isValidUserInfo($sUserInfo){
			
			return $this->isValidElement($sUserInfo, self::USERINFO_VALIDATION_RE);
						
		} // isValidUserInfo

		/**
		* Sets host
		* @param string $sHost
		* @throws InvalidArgumentException
		* @return Hornet\Data\Entities\URI Self
		*/		
		public function setHost($sHost){
				
			if ($this->isValidHost($sHost)){
					
				$this->aData[self::HOST] = $sHost;
					
			} else {
					
				throw new InvalidArgumentException($this->getExceptionMessage(self::HOST, $sHost), 5);
									
			} // if
		
			return $this;
				
		} // setHost
		
		/**
		 * Returns current host
		 * @return string|null Current host
		 */		
		public function getHost(){
		
			return $this->aData[self::HOST];
		
		} // getHost		

		/**
		 * Tests whether host is valid. Host can be a name or IP(4,6)
		 * @param string $sHost Host
		 * @return boolean True if host is valid
		 */
		public function isValidHost($sHost){
				
			return $this->isValidElement($sHost, self::HOST_VALIDATION_RE) || 
					filter_var($sHost, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
					(substr($sHost, 0, 1) == '[' && substr($sHost,-1) == ']' && filter_var(substr($sHost, 1, -1), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
				
			;
							
		} // isValidHost
		
		/**
		 * Sets port
		 * @param string|integer $mPort Port number
		 * @throws InvalidArgumentException
		 * @return \Hornet\Data\Entities\URI Self
		 */
		public function setPort($mPort){
				
			if ($this->isValidPort($mPort)){
		
				$this->aData[self::PORT] = (string)$mPort;
		
			} else {
					
				throw new InvalidArgumentException($this->getExceptionMessage(self::PORT, $mPort), 6);
									
			} // if
				
			return $this;
		
		} // setPort
		
		/**
		 * Gets current port
		 * @return string|null Current port
		 */
		public function getPort(){
				
			return $this->aData[self::PORT];
		
		} // getPort
		
		/**
		* Tests whether port is valid (integer or numeric string between 1 and 65355)
		* @param string|integer $mPort Port
		* @return boolean True if port is valid
		*/		
		public function isValidPort($mPort){
				
			return (is_int($mPort) || (is_string($mPort) && ctype_digit($mPort))) && (int)$mPort > 0 && (int)$mPort < 65536;
				
		} // isValidPort		

		/**
		 * Sets path
		 * @param string $sPath Path
		 * @throws InvalidArgumentException
		 * @return \Hornet\Data\Entities\URI Self
		 */
		public function setPath($sPath){
		
			if ($this->isValidPath($sPath)){
			
				$this->aData[self::PATH] = $sPath;
			
			} else {
			
				throw new InvalidArgumentException($this->getExceptionMessage(self::PATH, $sPath), 7);
							
			} // if
			
			return $this;
		
		} // setPath
				
		/**
		 * Returns current path
		 * @return string|null Current path
		 */
		public function getPath(){
		
			return $this->aData[self::PATH];
		
		} // getPath

		/**
		 * Tests whether path is valid.
		 * @param string $sPath Path
		 * @return boolean True if path is valid
		 * @stub
		 */
		public function isValidPath($sPath){
		
			return true;
		
		} // isValidPath	

		/**
		 * Sets query
		 * @param string $sQuery
		 * @throws InvalidArgumentException
		 * @return \Hornet\Data\Entities\URI Self
		 * @todo Allow query as array
		 * @todo Make query and fragment optional (for POST etc.)
		 */
		public function setQuery($sQuery){
				
			if ($this->isValidQuery($sQuery)){
		
				$this->aData[self::QUERY] = $sQuery;
		
			} else {
		
				throw new InvalidArgumentException($this->getExceptionMessage(self::QUERY, $sQuery), 8);
						
			} // if
				
			return $this;
				
		} // setQuery
		
		/**
		* Gets current query
		* @return string Current query
		*/
		public function getQuery(){
		
			return $this->aData[self::QUERY];
		
		} // getQuery		

		/**
		* Tests whether query is valid.
		* @param string $sQuery Query
		* @return boolean True if query is valid
		* @stub
		*/		
		public function isValidQuery($sQuery){
				
			return true;
				
		} // isValidQuery		
		
		/**
		 * Sets fragment
		 * @param string $sFragment
		 * @throws InvalidArgumentException
		 * @return \Hornet\Data\Entities\URI
		 */
		public function setFragment($sFragment){
			
			if ($this->isValidFragment($sFragment)){
			
				$this->aData[self::FRAGMENT] = $sFragment;
			
			} else {
			
				throw new InvalidArgumentException($this->getExceptionMessage(self::FRAGMENT, $sFragment), 9);
							
			} // if
				
			return $this;
			
		} // setFragment
		
		/**
		 * Gets current fragment
		 * @return string|null Current fragment
		 */
		public function getFragment(){
				
			return $this->aData[self::FRAGMENT];
				
		} // getFragment
		
		/**
		 * Tests whether fragment is valid.
		 * @param string $sFragment Fragment
		 * @return boolean True if path is valid
		 */
		public function isValidFragment($sFragment){
				
			return $this->isValidElement($sFragment, self::FRAGMENT_VALIDATION_RE);
				
		} // isValidFragment

	# PROTECTED METHODS
	
		/**
		 * Test whether given element is valid or not
		 * @param string $sElement Element Element to test
		 * @param string $sElementRegexp Regexp to match
		 * @param boolean $bIsEmptyAllowed True if element can be empty
		 * @return boolean True if element is valid
		 */
		protected function isValidElement($sElement, $sElementRegexp, $bIsEmptyAllowed = true){
			
			if (!is_string($sElement)){
				
				return false;
				
			} // if
			
			if (!isset(self::$aCache[$sElementRegexp][$sElement])){
					
				self::$aCache[$sElementRegexp][$sElement] = (($bIsEmptyAllowed && $sElement === '') || (preg_match($sElementRegexp, $sElement) === 1));
					
			} // if
				
			return self::$aCache[$sElementRegexp][$sElement];
			
		} // isValidElement
		
		/**
		 * Parses URI using PHP's parse_url() built-in function
		 * @param string $sURI URI to parse
		 * @return array Array of URI elements
		 */
		protected function parseFromStringNative($sURI){
			
			$aResult = parse_url($sURI);
			
			if (isset($aResult['user']) || isset($aResult['pass'])){
			
				$aResult['userinfo'] = (isset($aResult['user']) ? $aResult['user'] : '') . (isset($aResult['pass']) ? ':' . $aResult['pass'] : '');
			
			} // if
			
			return $aResult;
			
		} // parseFromStringNative
		
		/**
		 * Parses URI using sligthly modified regexp from RFC 3986. Actually, useless.
		 * @param string $sURI URI to parse
		 * @return array Array of URI elements
		 */
		protected function parseFromStringRegexp($sURI){
			
			preg_match(self::FROM_STRING_RE, $sURI, $aMatches);
					
			$aResult = array();
			
			if (!empty($aMatches['xscheme'])){
			
				$aResult['scheme'] = $aMatches['scheme'];
			
			} // if
			
			if (!empty($aMatches['authority'])){
				
				$sAuthority = $aMatches['authority'];
				
				$mAtPosition = strpos($sAuthority, '@');
				
				if ($mAtPosition!== false){
					
					$aResult['userinfo'] = substr($sAuthority,0,$mAtPosition);
					
					$sAuthority = substr($sAuthority, $mAtPosition+1);
					
				} // if
				
				$mDoubleDotPosition = strrpos($sAuthority, ':');
				
				if ($mDoubleDotPosition !== false){
					
					$nAuthorityLength = strlen($sAuthority);

					if ($mDoubleDotPosition == $nAuthorityLength - 1){
						
						$sAuthority = substr($sAuthority,0, $mDoubleDotPosition);
						
					} else if (is_numeric(substr($sAuthority, $mDoubleDotPosition+1))){
														
						$aResult['port'] = substr($sAuthority,$mDoubleDotPosition + 1);
								
						$sAuthority = substr($sAuthority,0, $mDoubleDotPosition);
						
					} // if 

				} // if
				
				if (!empty($sAuthority)){
					
					$aResult['host'] = $sAuthority;
					
				} // if
				
			} // if
			
			/*
			if (!empty($aMatches['xuserinfo'])){
							
				$aResult['userinfo'] = $aMatches['userinfo'];
			
			} // if			
			
			if (!empty($aMatches['host'])){
					
				$aResult['host'] = $aMatches['host'];
					
			} // if
			
			if (!empty($aMatches['xport'])){
						
				$aResult['port'] = $aMatches['port'];
						
			} // if
			
			*/
			
			if (!empty($aMatches['path'])){
			
				$aResult['path'] = $aMatches['path'];
							
			} // if
			
			if (!empty($aMatches['xquery'])){
			
				$aResult['query'] = $aMatches['query'];
			
			} // if
			
			if (!empty($aMatches['xfragment'])){
			
				$aResult['fragment'] = $aMatches['fragment'];
							
			} // if			
			
			return $aResult;
			
		} // parseFromStringRegexp
		
		/**
		 * Gets detailed exception message with variable type 
		 * @param string $sElement Name of element
		 * @param mixed $mVariable (Optional) Problematic variable
		 */
		protected function getExceptionMessage($sElement, $mVariable = null){

			if (func_num_args() == 2){
				
				return sprintf(self::EX_INVALID_ELEMENT, $sElement, var_export($mVariable, true));
				
			} else {
				
				return sprintf(self::EX_INVALID_ELEMENT, $sElement, '');
				
			}
			
		}
		
	} // class 
	
} // namespace
<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Interfaces {

	/**
	 * Interface for extractors.
	 * Extractors are classes (or functions) which are extracting variables
	 *  from context (object, table etc)
	 *
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2011-11-04
	 * @version 1.0
	 * @package Flow
	 */
	interface ExtractorInterface {
		
		/**
		 * Extracts given variable from context. Variable to extract
		 * should be defined in constructor (with some additional 
		 * parameters if applicable)
		 *
		 * @return mixed Extracted variable
		 */
		public function __invoke($mContext);
		
	} // interface

} // namespace
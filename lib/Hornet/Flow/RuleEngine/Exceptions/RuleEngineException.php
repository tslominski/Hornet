<?php

/*
* This file is part of the Hornet Framework.
* (c) Tomasz Słomiński <tomasz@slominski.it>
*/

namespace Hornet\Flow\RuleEngine\Exceptions {

	use Exception;
	
    /**
	 * Base exception for RuleEngine
	 *
	 * @author Tomasz Słomiński <tomasz@slominski.it>
	 * @since 2011-11-04
	 * @version 1.0
	 * @package Flow
	 */	
	class RuleEngineException extends Exception {

		const EX_INVALID_EXTRACTOR 			= 1;
		const EX_INVALID_CALLBACK			= 2;
		const EX_INVALID_FOLDING 			= 3;
		const EX_INVALID_COMPARATOR			= 4;
		const EX_INVALID_ENGINE_CALLBACK	= 5;
		
				
		const MSG_INVALID_EXTRACTOR 		= "Extractor should be callable - variable of type %s passed.";
		const MSG_INVALID_CALLBACK  		= "Callback should be callable - variable of type %s passed.";
		const MSG_INVALID_FOLDING 			= "Folding callback should be callable - variable of type %s passed.";
		const MSG_INVALID_COMPARATOR  		= "Comparator should be callable - variable of type %s passed.";
		const MSG_INVALID_ENGINE_CALLBACK  	= "RuleEngine doesn't have method named %s.";
				
		
		
	} // class

} // namespace

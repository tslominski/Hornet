<?php

namespace Hornet\Flow\RuleEngine\Rules {

	use Hornet\Flow\RuleEngine\Rules\AbstractRule;

	class DateTimeMatch extends AbstractRule {

		protected $sDateFormat = 'm-d';
		
		protected $sCompareTo  = null;
		
		public function __construct($mCompareTo = null, $sDateFormat = 'Y-m-d H:i:s'){
			
			$this->sDateFormat = $sDateFormat;
			
			if ($mCompareTo === null){
				
				$this->sCompareTo = date($this->sDateFormat);
				
			} else if (is_int($mCompareTo)){
				
				$this->sCompareTo = date($this->sDateFormat, $mCompareTo);
			
			} else if (is_string($mCompareTo)){
				
				$this->sCompareTo = $mCompareTo;
				
			}
			
		}
		
		protected function compare($mValue){
			
			if ($mValue === null){
				
				$sDate = date($this->sDateFormat);
				
			} else if (is_int($mValue)){
				
				$sDate = date($this->sDateFormat, $mValue);
			
			} else if (is_string($mValue)){
				
				$sDate = $mValue;
				
			} // if
			
			return $this->sCompareTo ==  $sDate;
			
		} 
		
		
	} // interface

} // namespace
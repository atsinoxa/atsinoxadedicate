<?php

	/**
	 * Application Common.
	 * 
	 * <p>Stores all of the common related functions.</p>
	 *
	 */
    /**
	 * The parent class for all common functions on the site
	 *
	 * @subpackage Classes
	 */
	class Common {
		
		/**
		 * Strips unwanted user input from variables to help prevent Cross Site Scripting(XSS).
		 */
		function cleanVar($var)
		{
			
			$var = strip_tags($var);
			$var = preg_replace('/[^0-9a-zA-Z@ -_\/:.]/','',$var);
			
			return $var;
			
		}
		
		/**
		 * Sanitizes a variable for use in a mysql query.
		 * @var mixed $var raw data, non-cleaned
		 * @param string $password sanitized beforehand
		 * @access public
		 * @return mixed 
		 */
		function cleanSqlVar($var, $quotes=FALSE)
		{
			
			$var = mysql_real_escape_string($var);
			
			if($quotes){
			
				return "'" . $var . "'";
				
			} else {
				
				return $var;
				
			}
			
		}
				
	}

?>
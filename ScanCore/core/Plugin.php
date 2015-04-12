<?php
/**
 * Plugin is the core plugin and is designed to be 
 * extended upon with your required functionality
 * */
class Plugin 
{
	/**
	 * Runs the plugins function
	 * 
	 * @param StdClass $pageInfo Contains the info of the page that has been crawled 
	 * 
	 * @return Array of StdClass Must contain error message, line number code on that line
	 * */
	public function perform($pageInfo) {}

	/**
	 * Takes a CSS style selector and returns an object
	 * with all matches
	 * 
	 * @param String $selector CSS Style selector
	 * @param String $document The document to select from
	 * 
	 * */
	final protected function findSelector($selector, $document)
	{
		$html = str_get_dom($document);
		return $html($selector);
	}

	/**
	 * When given a string, this method will find the line number in the haystack string
	 * 
	 * If not found, returns -1
	 * 
	 * @param String $needle 	The string to find within the haystack
	 * @param String $haystack  The string to search withing
	 * 
	 * @return Int The line number the string is found on.
	 * 
	 * */
	final protected function findStringLineNumber($needle, $haystack)
	{
		//Fixes a few decodin 
		$needle = utf8_decode(html_entity_decode($needle));
		$haystack = utf8_decode(html_entity_decode($haystack));

		//Break all seperate lines into array items
		$haystackLines = preg_split('/\n/', $haystack);
		$needleLines   = preg_split('/\n/', $needle);

		//Remove any tabbing and whitespace from the arrays
		for($i=0; $i < count($haystackLines); $i++)
		{
			$haystackLines[$i] = trim(preg_replace('/\t+/', '', $haystackLines[$i]));
		}

		for($i=0; $i < count($needleLines); $i++)
		{
			$needleLines[$i] = trim(preg_replace('/\t+/', '', $needleLines[$i]));
		}

		//Now, loop through the array and see if we can match the lines
		$haystackSize = count($haystackLines);
		for($i=0; $i < $haystackSize; $i++ )
		{
			//Even though the needle is generally multiple lines, we only want to match the first line.
			//Empty tags could match twice however, need to figure out how to stop this. Match context too?
			if(strpos($haystackLines[$i], $needleLines[0]) !== false)
			{
				return $i + 1;
			}
		}

		return -1;
	}

	final protected function findHTMLLineNumber($needle, $haystack)
	{
		$haystack = str_get_dom($haystack)->html();

		return $this->findStringLineNumber($needle, $haystack);
	}

}
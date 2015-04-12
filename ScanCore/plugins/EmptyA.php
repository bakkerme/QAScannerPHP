<?php
/**
 * Searches for A tags that are empty
 * 
 * @author Brandon Bakker 07/02/2014
 * */
class EmptyA extends Plugin 
{
	private $ERROR_MESSAGE = "Found anchor that might not be set correctly";

	public function perform($pageInfo)
	{
		$selectors = array('a[href=""]', 'a[href="#"]', 'a[href="javascript: ;"]');
		$resultsArray = array();

		foreach($selectors as $selector)
		{
			$results = $this->findSelector($selector, $pageInfo->source);

			if(count($results) > 0)
			{
				foreach($results as $result)	
				{
					$lineNumber = $this->findHTMLLineNumber($result->html(), $pageInfo->source);

					//TODO make an actual results class
					$results = new StdClass();
					$results->error = $this->ERROR_MESSAGE;
					$results->lineNumber = $lineNumber;
					$results->code = $result->html();

					array_push($resultsArray, $results);
				}
			}
		}

		return $resultsArray;
	}

}














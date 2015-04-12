<?php
/**
 * Searches for inline script tags
 * 
 * @author Brandon Bakker 11/02/2014
 * */
class ScriptTags extends Plugin 
{
	private $ERROR_MESSAGE = "Found inline Javascript.";

	public function perform($pageInfo)
	{
		$selectors = array('script');
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
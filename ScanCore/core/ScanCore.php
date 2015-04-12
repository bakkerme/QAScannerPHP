<?php
class ScanCore extends PHPCrawler 
{
	private $scanId;//Id of the current scan
	private $conn;//Connection to the database
	private $plugins;

	public function setConnection($conn)
	{
		$this->conn = $conn;
	}

	/**
	 * Runs everytime PHPCrawler picks up a new URL
	 * 
	 * @param PHPCrawlerDocumentInfo $DocInfo The document info object from the crawler
	 * 
	 * */
	public function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) 
	{
		if(is_null($this->scanId))
		{
			echo "No scan id";
			flush();
			return false;
		}

		$pageId = md5($DocInfo->url . $this->scanId);

		try
        {
            //Create a statement with the required pages
            $stmt = $this->conn->prepare(
            "INSERT INTO pages
             VALUES(:scanId, :url, :body, :statusCode, :pageId)
            ");
            $result = $stmt->execute(array(':scanId' => $this->scanId, ':url' => $DocInfo->url, ':body' => $DocInfo->source, ':statusCode' => $DocInfo->http_status_code, ':pageId' => $pageId));

            if($result)
            {
            	echo "Found " . $DocInfo->url . ". ScanId is" . $this->scanId . "\n";

            	//Now check the plugins
            	foreach($this->plugins as $plugin)
            	{
            		$pluginResult = $plugin->perform($DocInfo);

            		//TODO Change this to reflect proper plugin result
            		if(!empty($pluginResult) && is_array($pluginResult))
            		{
            			foreach($pluginResult as $result)
            			{
							$stmt = $this->conn->prepare(
				            "INSERT INTO errors
				            (scanId, pageId, errorText, lineNumber, code) 
				             VALUES(:scanId, :pageId, :errorText, :lineNumber, :code)
				            ");
				            $result = $stmt->execute(array(':scanId' => $this->scanId, ':pageId' => $pageId, ':errorText' => $result->error, ':lineNumber' => $result->lineNumber, ':code' => $result->code));
            			}
            		}
            	}

            	return true;	
            }
            else
            {
				var_dump($DocInfo);
            	echo "\n Error in saving from URL:" . $DocInfo->url . ", scanId: " . $this->scanId . "\n";
            }

        }
    	catch(PDOException $e)
        {
           echo("BAD_DB_RETURN" .  $e->getMessage());
        }

		flush();
	}

	/**
	 * Sets the id for the current scan
	 * 
	 * @param Number $scanId
	 * */
	public function setScanId($scanId)
	{
		if(!is_null($scanId) && is_int($scanId))
		{
			$this->scanId = $scanId;
			return true;
		}	
		else
		{
			return false;
		}
	}

	/**
	 * Add plugins to use in this scan
	 * 
	 * @param Array Array of Classes
	 * */
	public function addPlugins($pluginArray)
	{
		if(!is_array($pluginArray))
		{
			return false;
		}
		
		$this->plugins = $pluginArray;
		return true;
	}
}

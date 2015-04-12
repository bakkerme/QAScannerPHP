<?php
class QAScanner 
{
	private $urlList = array();
	private $conn;
	private $plugins = array();
	
	public function __construct()
	{
		$db = MySQLDb::getInstance();
        $this->conn = $db->getConnection();
	}

	/**
	 * Add a url for the system to scan
	 * 
	 * @var String $url The URL of the site to scan
	 * */
	public function addURL($url) 
	{
		if(!in_array($url, $this->urlList))	
		{
			array_push($this->urlList, $url);
		}

		return true;
	}

	/**
	 * Get the array of URL's to be scanned
	 * 
	 * @return Array List of urls
	 * */
	public function getURLs()
	{
		return $urlList;
	}

	/**
	 * Get the sitemap and store the source data in the database
	 * 
	 * */
	public function scan()
	{
		if(empty($this->urlList))
		{
			return false;
		}

		echo "Start the crawl! \n";

		$scanId = $this->getNextScanId();

		foreach($this->urlList as $url)
		{
			echo "Site is:" . $url . "\n";
			// Create a instance of your class, define the behaviour
			// of the crawler (see class-reference for more options and details)
			// and start the crawling-process.
			$crawler = new ScanCore();
			$crawler->setScanId($scanId);
			$crawler->setConnection($this->conn);
			$crawler->addPlugins($this->plugins);

			// URL to crawl
			$crawler->setURL($url);

			// Only receive content of files with content-type "text/html"
			$crawler->addContentTypeReceiveRule("#text/html#");
			$crawler->addContentTypeReceiveRule("#text/javascript#");

			$crawler->setStreamTimeout(15);
			$crawler->setConnectionTimeout(20);

			// Ignore links to pictures, dont even request pictures
			$crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");
			$crawler->addURLFilterRule("#css# i");

			// Store and send cookie-data like a browser does
			$crawler->enableCookieHandling(true);

			// Thats enough, now here we go
			$crawler->goMultiProcessed(5);

			// At the end, after the process is finished, we print a short
			// report (see method getProcessReport() for more information)
			$report = $crawler->getProcessReport();

		}
	}

	/**
	 * Enable a plugin for scanning
	 * 
	 * @param String Name of plugin
	 * */
	public function addPlugin($plugin)
	{
		try
		{
		    require('plugins/' . $plugin . '.php');
			$plugin = new $plugin();

			//Make sure we have a plugin here.
			//Good UX an security	
			if(is_a($plugin, 'Plugin'))
				array_push($this->plugins, $plugin);
			else
				return false;
		}
		catch (Exception $e)
		{
			return false;
		}

		return true;
	}

	/**
	 * Checks the DB and gets the next id 
	 * for the scan to take place
	 * 
	 * @return Int Scan ID 
	 * 
	 * */
	private function getNextScanId()
	{
		try
		{
			$stmt = $this->conn->query("
			SELECT scanId
			FROM pages
			ORDER BY scanId DESC
			LIMIT 1
			");
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$results = $stmt->fetchAll();
			
			if(sizeof($results) == 0)
			{
				return 0;
			}

			return intval($results[0]['scanId']) + 1;
		}
    	catch(PDOException $e)
        {
            echo("BAD_DB_RETURN" .  $e->getMessage());
            exit();
        }		
	}

}
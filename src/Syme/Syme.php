<?php

/**
 * Syme - Lib PHP
 */

 namespace Syme;

class Syme {

	private $url;
	private $apikey;
	private $broker;
	private $application;
	private $debug;

	/**
	 * 
	 */
	public function __construct ($url, $apikey, $broker, $application) {

		$this->setUrl($url);
		$this->setApiKey($apikey);
		$this->setBroker($broker);
		$this->setApplication($application);

		$this->debug = false;

		return $this;

	}

	/**
	 * Private Method
	 */
	private function fetch ($message) {

		if ((!$message) || (!($message instanceof SymeMessage))) {

			throw new Exception('Message is required! And must be an instance of SymeMessage');

		} else if ($this->debug === true) {

			echo json_encode($message);

		}

		try {

			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, $this->url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($message));
			curl_setopt($curl, CURLOPT_TIMEOUT, 5); 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, [
				"Content-Type: application/json",
				"X-Syme-ApiKey: {$this->apikey}"
			]);

			$response = curl_exec($curl);

			if (curl_errno($curl)) {

				$error = curl_error($curl);

				curl_close($curl);
				
				throw new \Exception($error);

			} else {

				$statuscode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				$headersize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

				$responseheaders = substr($response, 0, $headersize);
				$responsebody = substr($response, $headersize);

				curl_close($curl);

				return array(
					"status" => $statuscode,
					"headers" => $responseheaders,
					"body" => $responsebody
				);

			}

		} catch (\Exception $fetchError) {

			throw $fetchError;

		}

	}

	/**
	 * 
	 */
	public function setUrl ($url) {

		if (!$url) {

			throw new Exception('Syme URL must be a valid URL!');

		} else {

			$this->url = $url;

		}

		return $this;

	}

	/**
	 * 
	 */
	public function setApiKey ($apikey) {

		if (!$apikey) {

			throw new Exception('Syme API Key must be a defined!');

		} else {

			$this->apikey = $apikey;

		}

		return $this;

	}

	/**
	 * 
	 */
	public function setBroker ($broker) {

		if (!$broker) {

			throw new Exception('Syme Broker must be defined!');

		} else {

			$this->broker = $broker;

		}

		return $this;

	}

	/**
	 * 
	 */
	public function setApplication ($application) {

		if (!$application) {

			throw new Exception('Syme Application must be defined!');

		} else {

			$this->application = $application;

		}

		return $this;

	}

	/**
	 * 
	 */
	public function toggleDebug () {

		$this->debug = !$this->debug; 

		return $this;

	}

	/**
	 * 
	 */
	public function send ($message, $routingkey, $contenttype, $publishedby, $level, $severity, $payload) {

		if ((!$message) || ($message === "")) {

			throw new Exception('To send a message with Syme, message must be defined!');

		}

		$input = new SymeMessage(null, $message, false, $this->broker, $publishedby, $this->application, $level, $severity, $payload, $routingkey, $contenttype);

		try {

			return $this->fetch($input);

		} catch (\Exception $fetchError) {

			throw $fetchError;

		}

	}

	/**
	 * 
	 */
	public function sendError ($message, $routingkey, $contenttype, $publishedby, $payload) {

		if ((!$message) || ($message === "")) {

			throw new Exception('To send a message error with Syme, message must be defined!');

		}

		$input = new SymeMessage(null, $message, false, $this->broker, $publishedby, $this->application, "error", "Minor", $payload, $routingkey, $contenttype);

		try {

			return $this->fetch($input);

		} catch (\Exception $fetchError) {

			throw $fetchError;

		}

	}

	/**
	 * 
	 */
	public function sendEmergency ($message, $routingkey, $contenttype, $publishedby, $payload) {

		if ((!$message) || ($message === "")) {

			throw new Exception('To send a message emergency with Syme, message must be defined!');

		}

		$input = new SymeMessage(null, $message, true, $this->broker, $publishedby, $this->application, "emerg", "Block", $payload, $routingkey, $contenttype);

		try {

			return $this->fetch($input);

		} catch (\Exception $fetchError) {

			throw $fetchError;

		}

	}

}

?>

<?php

/**
 * Syme - Lib PHP
 * Syme Message
 */

namespace Syme;

use Ramsey\Uuid\Uuid;

class SymeMessage {

	public $id;
	public $message;
	public $speak;
	public $broker;
	public $publishedat;
	public $publishedby;
	public $application;
	public $level;
	public $severity;
	public $routingkey;
	public $contenttype;
	public $payload;

	function __construct ($id, $message, $speak, $broker, $publishedby, $application, $level, $severity, $payload, $routingkey, $contenttype) {

		$this->id = $id ?? Uuid::uuid4();
		$this->message = $message ?? 'Undefined Syme Message';
		$this->speak = $speak ?? false;
		$this->broker = $broker ?? 'exchange-error';
		$this->publishedat = date('Y-m-d H:i:s');
		$this->publishedby = $publishedby ?? 'Undefined Syme Publisher';
		$this->application = $application ?? 'Syme JS Lib';
		$this->level = $level ?? 'info';
		$this->severity = $severity ?? 'Feature';
		$this->routingkey = $routingkey ?? '#';
		$this->contenttype = $contenttype ?? 'text/plain';

		try {

			$this->payload = json_encode($payload);

		} catch (\Exception $encodeError) {

			$this->payload = null;

		}

		return $this;

	}

}

?>

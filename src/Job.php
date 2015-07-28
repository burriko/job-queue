<?php

namespace JobQueue;

class Job
{
	private $class_name;

	private $arguments;

	private $id;

	public function __construct($class_name, $arguments)
	{
		$this->class_name = $class_name;
		$this->arguments = $arguments;
	}

	public static function createFromPayload($payload)
	{
		$data = json_decode($payload, true);

		return new self($data['job'], $data['data']);
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getClassName()
	{
		return $this->class_name;
	}

	public function getArguments()
	{
		return $this->arguments;
	}

	public function getPayload()
	{
		return json_encode([
			'job' => $this->class_name,
			'data' => $this->arguments
		]);
	}
}
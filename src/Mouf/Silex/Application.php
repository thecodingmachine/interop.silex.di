<?php
namespace Mouf\Silex;

use Mouf\MoufManager;

class Application extends \Silex\Application {
	/**
	 * 
	 * @var MoufManager
	 */
	protected $moufManager;
	
	public function registerMoufManager(MoufManager $moufManager) {
		$this->moufManager = $moufManager;
	}
	
	/**
	 * Checks if a parameter or an object is set.
	 *
	 * @param string $id The unique identifier for the parameter or object
	 *
	 * @return Boolean
	 */
	public function offsetExists($id)
	{
		if ($this->moufManager && $this->moufManager->has($id)) {
			return true;
		}
		return parent::offsetExists($id);
	}
	
	/**
	 * Gets a parameter or an object, first from Mouf, then if not found from Pimple.
	 *
	 * @param string $id The unique identifier for the parameter or object
	 *
	 * @return mixed The value of the parameter or an object
	 *
	 * @throws InvalidArgumentException if the identifier is not defined
	 */
	public function offsetGet($id)
	{
		if ($this->moufManager && $this->moufManager->has($id)) {
			return $this->moufManager->get($id);
		}
		return parent::offsetGet($id);
	}
}
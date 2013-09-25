<?php
namespace Mouf\Interop\Silex;

use Mouf\MoufManager;

/**
 * This class extends the Silex Application class that itself extends Pimple.
 * It adds the capability for Pimple to accept fallback DI containers (or preprend 
 * DI containers to Silex).
 * 
 * @author David Négrier <david@mouf-php.com>
 */
class Application extends \Silex\Application {
	
	/**
	 * @var ContainerInterface[]
	 */
	protected $prependContainers = array();

	/**
	 * @var ContainerInterface[]
	 */
	protected $fallbackContainers = array();
	
	/**
	 * Registers a container that will be queried if the Pimple container does not
	 * contain the requested instance.
	 *
	 * Note: we are not enforcing an interface yet because we lack a standard on the interface name.
	 *
	 * @param ContainerInterface $container
	 */
	public function registerFallbackContainer($container) {
		$this->fallbackContainers[] = $container;
	}
	
	/**
	 * Registers a container that will be queried before the Pimple container.
	 *
	 * Note: we are not enforcing an interface yet because we lack a standard on the interface name.
	 *
	 * @param ContainerInterface $container
	 */
	public function registerPrependContainer($container) {
		array_unshift($this->prependContainers, $container);
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
		foreach ($this->prependContainers as $container) {
			if ($container->has($id)) {
				return true;
			}
		}
		
		$has = parent::offsetExists($id);
		if ($has) {
			return true;
		}
		
		foreach ($this->fallbackContainers as $container) {
			if ($container->has($id)) {
				return true;
			}
		}
		
		return false;
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
		// Let's search in the prepended containers:
		foreach ($this->prependContainers as $container) {
			if ($container->has($id)) {
				return $container->get($id);
			}
		}
		
		if (parent::offsetExists($id)) {
			return parent::offsetGet($id);
		}
		
		// Let's search in the fallback mode:
		foreach ($this->fallbackContainers as $container) {
			if ($container->has($id)) {
				return $container->get($id);
			}
		}
		
		throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
	}
}
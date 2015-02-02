<?php
namespace Mouf\Interop\Silex;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\NotFoundException;
use Interop\Container\Pimple\PimpleInterop;

/**
 * This class extends the Silex Application class that itself extends Pimple.
 * It adds the capability for Pimple to accept fallback DI containers (or preprend 
 * DI containers to Silex).
 * 
 * @author David Négrier <david@mouf-php.com>
 */
class Application extends \Silex\Application {

	/**
	 * @var ContainerInterface
	 */
	protected $rootContainer;

	/**
	 * The pimple container for the Silex application (it is externalized).
	 * @var PimpleInterop
	 */
	protected $pimpleContainer;

	/**
	 * Instantiate the application.
	 *
	 * @param ContainerInterface $container The root container of the application (if any)
	 * @param array $values The parameters or objects.
	 */
	public function __construct(ContainerInterface $container = null, array $values = array())
	{
		$this->pimpleContainer = new PimpleInterop($container);

		parent::__construct($values);

		if ($container) {
			$this->rootContainer = $container;
		}
	}

	/**
	 * Returns the container for the Silex application.
	 * This is a container based on PimpleInterop.
	 *
	 * @return PimpleInterop
	 */
	public function getSilexContainer() {
		return $this->pimpleContainer;
	}

	/**
	 * Gets a parameter or an object, first from the root container, then from Pimple if nothing is found in root container.
	 * It is expected that the root container will be a composite container with Pimple being part of it, therefore,
	 * the fallback to Pimple is just here by security.
	 *
	 * @param string $id The unique identifier for the parameter or object
	 *
	 * @return mixed The value of the parameter or an object
	 *
	 * @throws PimpleNotFoundException if the identifier is not defined
	 */
	public function offsetGet($id)
	{
		if ($this->rootContainer) {
			try {
				return $this->rootContainer->get($id);
			} catch (NotFoundException $ex) {
				// Fallback to pimple if offsetGet fails.
				return $this->pimpleContainer[$id];
			}
		} else {
			return $this->pimpleContainer[$id];
		}
	}

	/**
	 * Forwards offsetSet to Pimple container
	 *
	 * @param string $id    The unique identifier for the parameter or object
	 * @param mixed  $value The value of the parameter or a closure to defined an object
	 */
	public function offsetSet($id, $value)
	{
		$this->pimpleContainer->offsetSet($id, $value);
	}

	/**
	 * Check existence of a parameter or an object, first in the root container, then in Pimple if nothing is found in root container.
	 * It is expected that the root container will be a composite container with Pimple being part of it, therefore,
	 * the fallback to Pimple is just here by security.
	 *
	 * @param string $id The unique identifier for the parameter or object
	 *
	 * @return Boolean
	 */
	public function offsetExists($id)
	{
		if ($this->rootContainer) {
			try {
				return $this->rootContainer->has($id);
			} catch (NotFoundException $ex) {
				// Fallback to pimple if offsetExists fails.
				return $this->pimpleContainer->offsetExists($id);
			}
		} else {
			return $this->pimpleContainer->offsetExists($id);
		}
	}

	/**
	 * Forwards offsetUnset to Pimple container
	 *
	 * @param string $id The unique identifier for the parameter or object
	 */
	public function offsetUnset($id)
	{
		$this->pimpleContainer->offsetUnset($id);
	}

	/**
	 * Forwards raw to Pimple container
	 *
	 * @param string $id The unique identifier for the parameter or object
	 *
	 * @return mixed The value of the parameter or the closure defining an object
	 *
	 * @throws InvalidArgumentException if the identifier is not defined
	 */
	public function raw($id)
	{
		return $this->pimpleContainer->raw($id);
	}

	/**
	 * Forwards extend to Pimple container
	 *
	 * @param string $id       The unique identifier for the object
	 * @param callable $callable A service definition to extend the original
	 *
	 * @return Closure The wrapped closure
	 *
	 * @throws InvalidArgumentException if the identifier is not defined or not a service definition
	 */
	public function extend($id, $callable)
	{
		return $this->pimpleContainer->extend($id, $callable);
	}

	/**
	 * Forwards keys to Pimple container
	 *
	 * @return array An array of value names
	 */
	public function keys()
	{
		return $this->pimpleContainer->keys();
	}
}

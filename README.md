Silex with Mouf Dependency Injection compatibility
==================================================

This project is a very simple extension to the [Silex microframework](http://silex.sensiolabs.org/).
It adds [Mouf dependency injection](http://mouf-php.com) capability to Silex.

Why?
----

Silex is a microframework. It is designed on top of Pimple, a very simple dependency injection container 
(DIC) written in about 80 lines of code.
Pimple is a nice DIC, but it can become quite verbose as your project grows. And natively, Silex
has no way to use another DIC (the `Application` class of Silex extends the `Pimple` class).

This project lets you use [Mouf](http://mouf-php.com), a graphical dependency injection framework
directly in your Silex project. Instead of injecting your dependencies by filling the `$app` variable,
you can use Mouf user interface to declare your instances.

How?
----

The extended `Application` class has only one additonnal method: `registerMoufManager()`. This is
used to register the Mouf DIC instance into Silex.

When this is done, you can access any instance declared in Mouf using the `$app` object, just like you would in
any Silex project.

Here is a sample about injecting a controller in Pimple.

- Install this package using Composer.
- This package depends on Mouf. Once Mouf is downloaded,
  you need to [install Mouf](http://mouf-php.com/packages/mouf/mouf/doc/installing_mouf.md).
- Then, declare a simple test controller:
  ```php
  <?php
  namespace Example\Controller;

  use Symfony\Component\HttpFoundation\JsonResponse;

  class TestController
  {
  	  private $text;
  	  public function __construct($text)
  	  {
  		  $this->text = $text;
  	  }

  	  public function testAction()
  	  {
  		  return new JsonResponse(array("hello"=>$this->text));
  	  }
  }
  ```
- [Create an instance](http://mouf-php.com/packages/mouf/mouf/version/2.0-dev/doc/mouf_di_ui.md) `mycontroller` for your controller in Mouf.
  When this is over, you should see this in Mouf UI:  
  ![Controller's instance](doc/images/mycontroller_instance.png)
- Init your application using the extended `Mouf\Silex\Application` class:
  ```php
  // Load Mouf (and Composer's autoloader)
  require_once __DIR__.'/mouf/Mouf.php';

  // Get Silex app with Mouf support
  $app = new Mouf\Silex\Application();

  // Register Silex's controllers support
  $app->register(new Silex\Provider\ServiceControllerServiceProvider());

  // Register the Mouf DI container
  $app->registerMoufManager(Mouf\MoufManager::getMoufManager());

  // 'mycontroller' instance is declared in Mouf!
  $app->get('/hello', "mycontroller:testAction");

  $app->run();  
  ```
  
See how great it is? You can use the simple routing mechanism of Pimple and get rid of all the
spaguetti code building your dependencies.


Known limits
------------

This project is a proof-of-concept. It prooves that is it possible to chain 2 DI containers easily (this
package contains... 10 lines of code!)
It also shows the limits of this technique. Indeed, it is not possible from Mouf to refer to an
object declared in Pimple (no possible round-trip). This might however become possible, should a 
standard about DIC interoperability become true.
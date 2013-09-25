Silex with Mouf Dependency Injection compatibility
==================================================

This project is a very simple extension to the [Silex microframework](http://silex.sensiolabs.org/).
It contains an `Mouf\Interop\Silex\Application` class that extends the `Silex\Application` class.
The extended class will let you add additional dependency injection containers (DIC) to Silex' container
(that is Pimple).

Why?
----

Silex is a microframework. It is designed on top of Pimple, a very simple dependency injection container 
(DIC) written in about 80 lines of code.
Pimple is a nice DIC, but it can become quite verbose as your project grows. And natively, Silex
has no way to use another DIC (the `Application` class of Silex extends the `Pimple` class).

This project lets you add any other dependency injection framework
directly in your Silex project. Instead of injecting your dependencies by filling the `$app` variable,
you can your own container. Instances declared in your container will be accessible using the
Pimple `$app['my.instance']` syntax.

How?
----

The extended `Application` class has only two additonal methods: 

- `registerPrependContainer()`: if you want your container to have the precedence over Pimple
- `registerFallbackContainer()`: if you want Pimple to have the precedence over your container

When this is done, you can access any instance declared of your container using the `$app` object, just like you would in
any Silex project.

Your DI container must respect the [`ContainerInterface` described in this document.](https://github.com/moufmouf/fig-standards/blob/master/proposed/dependency-injection/dependency-injection.md)

Note: your container does not have to explicitly implement the `ContainerInterface` interface (because it is not standard yet),
but it needs to provide the `get` and `has` methods.

What DI containers can I plug in Silex?
---------------------------------------

Out of the box, you can plug these DI containers, because they respect the `ContainerInterface` interface:

- Mouf (http://mouf-php.com)
- Aura DI (https://github.com/auraphp/Aura.Di)
- Symfony 2 DIC (http://symfony.com/doc/current/components/dependency_injection/introduction.html)

But wait! Thanks to Jeremy Lindblom and its awesome [Acclimate package](https://github.com/jeremeamia/acclimate), you can now take almost any dependency injection container out there, and get an adapter on that container that respects the `ContainerInterface` interface.

Prepending or appending containers
----------------------------------

When registering your container, you have 2 options:

- You can **preprend** your container. In this case, your container will be called before Symfony's container.
- You can use your container as a **fallback**. In this case, your container will be called only if Symfony's container does not contain the instance.

To preprend your container, use the `registerPrependContainer` method:
```php
$app = new Mouf\Interop\Silex\Application();
...
$app->registerPrependContainer($myContainer);
```

To use your container has a fallback, use the `registerFallbackContainer` method:
```php
$app = new Mouf\Interop\Silex\Application();
...
$app->registerFallbackContainer($myContainer);
```

<div class="alert alert-info"><strong>Note:</strong> you are not limited to one container, you can register as many as you want.</div>

Installation
------------

This class is distributed as a [Composer package](https://packagist.org/packages/mouf/interop.silex.di):

```
{
	require: {
		"mouf/interop.silex.di" : "~1.0"
	}
}
```

See a working sample
--------------------

Check out this use case: [creating a Silex controller with the Mouf framework](doc/declaring-a-controller-with-mouf.md)

You are a Symfony 2 user?
-------------------------

There is a very similar package for Symfony 2 application. It lets you add additional containers to 
the main Symfony 2 container: [check it out: interop.symfony.di](https://github.com/thecodingmachine/interop.symfony.di)

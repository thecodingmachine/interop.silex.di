Silex with chainable Dependency Injection compatibility
=======================================================

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

The extended `Application` class has a modified constructor:

- __construct(ContainerInterface $container = null, array $values = array())

The container passed in parameter is a [delegate lookup container](https://github.com/container-interop/container-interop/blob/master/docs/Delegate-lookup.md).

When this is done, you can access any instance declared of your container using the `$app` object, just like you would in
any Silex project.

Your DI container must respect the [`ContainerInterface` described in this the container-interop project.](https://github.com/container-interop/container-interop)

What DI containers can I plug in Silex?
---------------------------------------

Out of the box, you can plug any of the DI containers supported by [container-interop]((https://github.com/container-interop/container-interop)).
There are an awful lot of them!

Installation
------------

This class is distributed as a [Composer package](https://packagist.org/packages/mouf/interop.silex.di):

```
{
	require: {
		"mouf/interop.silex.di" : "~2.0"
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

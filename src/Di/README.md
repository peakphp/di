# Peak Dependency Injection Container
This component allows you to standardize and centralize the way objects are constructed in your application.

## Installation outside framework

```
$ composer require peakphp/di
```

## Usages

### With Autowiring

No configuration needed. Just type-hint your constructor parameters and the container can guess which dependencies to inject.

```PHP

class Foo
{
    public $bar;

    public function __construct(Bar $bar, $id = null, $alias = null)
    {
        $this->bar = $bar;
    }
}

$foo = $container->create(Foo::class);
```
In example above, a new ``Bar`` instance will be created automatically is each time when creating ``Foo``. This mechanism rely on ```Reflection``` to resolve objects dependencies.

#### Reuse a class instance by storing it in the container with ```add()```

```PHP
class Bar {}

$bar = new Bar();
$bar->name = "John Bar";

$container->add($bar);

$foo1 = $container->create(Foo::class);
$foo2 = $container->create(Foo::class);
```

In example above, ``$foo1`` and ``$foo2`` will have the same instance of ``Bar``.

```PHP
echo $foo1->bar->name; //output: John Bar
echo $foo2->bar->name; //output: John Bar
```

#### Passing other types of arguments:
```PHP
$foo = $container->create(Foo::class, [
    12,
    'FooBar'
]);
```

#### Get a stored object instance

```PHP
$container->add(new Monolog\Logger);
$logger = $container->get(Monolog\Logger::class);
```

#### Use alias for class name

```PHP
$container->add(new Monolog\Handler\StreamHandler(), 'LogStream');
$stream = $container->get('LogStream');
```

#### Call an object method
You can also resolve dependencies of a object method by simply using ```call()```

```PHP

class Events
{
    public function method(Bar $bar, $alias = null)
    {
        //...
        return $bar;
    }
}

$events = new Events;
$bar = $container->call([
    $events,
    'method
]);
```

### Without Autowiring
For small and medium projects, autowiring can do the job correctly, but as your project grow, you may want have more control over how your objects are instantiated and stored.
First you need to disable autowiring with disableAutowiring(). To resolve ```Foo```, you have to define how dependencies will be resolved. This can be be done with method ```setDefinitions()```

```PHP

$container->disableAutowiring();

// set definitions
$container->setDefinitions([
    Foo::class => function($container) {
        return [
            new Bar()
        ];
    }
]);

// create foo successfully
$foo = $container->create(Foo::class);
//throw an exception since there is no definiton for Bar
$bar = $container->create(Bar::class);
```
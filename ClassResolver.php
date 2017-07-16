<?php

namespace Peak\Di;

use \Exception;
use \InvalidArgumentException;

/**
 * Class Dependencies Resolver
 */
class ClassResolver
{
    /**
     * ClassInspector
     * @var \Peak\Di\ClassInspector
     */
    protected $inspector;

    /**
     * Interface resolver
     * @var \Peak\Di\InterfaceResolver
     */
    protected $iresolver;

    /**
     * Explicit resolver
     * @var \Peak\Di\ExplicitResolver
     */
    protected $explicit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inspector = new ClassInspector();
        $this->explicit  = new ExplicitResolver();
        $this->iresolver = new InterfaceResolver();
    }

    /**
     * Resolve class arguments dependencies
     *
     * @param mixed $class
     * @param Container $container
     * @param array $args
     * @param array $explicit
     * @return array
     * @throws InvalidArgumentException
     */
    public function resolve($class, Container $container, array $args = [], array $explicit = [])
    {
        $method = '__construct';

        if (is_array($class)) {
            // treat $class as a callback
            if (count($class) == 2) {
                $method = $class[1];
                $class  = $class[0];
            } else {
                throw new InvalidArgumentException('Expecting a valid callback definition');
            }
        }

        $dependencies = $this->inspector->inspect($class, $method);
        $class_args   = [];
        $class_count  = 0;
        $i            = 0;

        foreach ($dependencies as $d) {
            if (isset($d['error'])) {
                throw new Exception($d['error']);
            }

            // its a class or an interface
            if (isset($d['class'])) {
                $name = $d['class'];
                ++$class_count;

                // look for object in explicit dependency declaration
                $result = $this->explicit->resolve($name, $explicit);
                if ($result !== null) {
                    $class_args[] = $result;
                } elseif ($container->has($name)) {
                    // check if container has a stored instance
                    $class_args[] = $container->get($name);
                } else {
                    // otherwise check if we are
                    // dealing with an interface dependency
                    if (interface_exists($name)) {
                        $class_args[] = $this->iresolver->resolve($name, $container, $explicit);
                    } else {
                        // or resolve dependency by trying to instantiate object classname string
                        $child_args = [];
                        if (array_key_exists($name, $args)) {
                            $child_args = $args[$name];
                        }
                        $class_args[] = $container->instantiate($name, $child_args, $explicit);
                    }
                }
            } elseif (array_key_exists($i - ($class_count), $args)) {
                // everything else that is not a type of class or interface
                $class_args[] = $args[$i - $class_count];
            }

            ++$i;
        }

        return $class_args;
    }
}
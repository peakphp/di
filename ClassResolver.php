<?php

namespace Peak\Di;

use Peak\Di\Container;
use Peak\Di\ClassInspector;

/**
 * Dependency Class Resolver
 */
class ClassResolver
{
    /**
     * ClassInspector
     * @var object
     */
    protected $inspector;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inspector = new ClassInspector();
    }

    /**
     * Resolve class arguments dependencies
     * 
     * @param  string $class
     * @return object
     */
    public function resolve(string $class, Container $container, array $args = [])
    {
        $dependencies = $this->inspector->inspect($class);
        $class_args   = [];
        $class_count  = 0;
        $i            = 0;

        foreach($dependencies as $key => $d) {

            if(isset($d['class'])) {

                $name = $d['class'];
                ++$class_count;

                if($container->hasInstance($name)) {
                    $class_args[] = $container->getInstance($name);
                }
                else {
                    $class_args[] = new $name();
                }
            }
            else if(array_key_exists($i - ($class_count), $args)) {
                $class_args[] = $args[$i - $class_count];
            }

            ++$i;
        }

        return $class_args;
    } 
}
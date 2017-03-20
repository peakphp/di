<?php

namespace Peak\Di;

use Closure;
use Peak\Di\Container;

/**
 * Explicit Dependency declaration Resolver
 */
class ExplicitResolver
{
    /**
     * Resolve class arguments dependencies
     *
     * @param  string $class
     * @return object
     */
    public function resolve($needle, $explicit = [])
    {
        // Check for explicit dependency closure or object instance
        if (array_key_exists($needle, $explicit)) {
            if ($explicit[$needle] instanceof Closure) {
                return $explicit[$needle]();
            }
            elseif (is_object($explicit[$needle])) {
                return $explicit[$needle];
            }
        }
        return null;
    }
}

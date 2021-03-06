<?php

declare(strict_types=1);

namespace Peak\Di;

use Peak\Di\Binding\BindingInterface;

class BindingResolver
{
    /**
     * Resolve a binding request
     *
     * @param BindingInterface $binding
     * @param Container $container
     * @param array $args
     * @param array|null $explicit
     * @return mixed
     */
    public function resolve(BindingInterface $binding, Container $container, $args = [], $explicit = null)
    {
        return $binding->resolve($container, $args, $explicit);
    }
}

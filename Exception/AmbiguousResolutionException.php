<?php

declare(strict_types=1);

namespace Peak\Di\Exception;

use Psr\Container\ContainerExceptionInterface;
use \Exception;

use function count;

class AmbiguousResolutionException extends Exception implements ContainerExceptionInterface
{
    /**
     * AmbiguousResolutionException constructor.
     * @param string $interface
     * @param array $instances
     */
    public function __construct(string $interface, array $instances)
    {
        parent::__construct('Dependencies for interface ['.$interface.'] is ambiguous. There is '.count($instances).' different stored instances for this interface.');
    }
}


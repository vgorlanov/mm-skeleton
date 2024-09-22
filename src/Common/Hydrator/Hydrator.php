<?php

declare(strict_types=1);

namespace Common\Hydrator;

use ReflectionClass;
use ReflectionException;

class Hydrator
{
    private array $reflectionClassMap;

    /**
     * @param string $class
     * @param array<mixed> $data
     * @return mixed
     * @throws ReflectionException
     */
    public function hydrate(string $class, array $data): mixed
    {
        $reflection = $this->getReflectionClass($class);
        $target = $reflection->newInstanceWithoutConstructor();

        foreach ($data as $name => $value) {
            $property = $reflection->getProperty($name);
            $property->setValue($target, $value);
        }
        return $target;
    }

    /**
     * @param $object
     * @param array<mixed> $fields
     * @return mixed
     * @throws ReflectionException
     */
    public function extract($object, array $fields): mixed
    {
        $result = [];
        $reflection = $this->getReflectionClass($object::class);
        foreach ($fields as $name) {
            $property = $reflection->getProperty($name);
            $result[$property->getName()] = $property->getValue($object);
        }
        return $result;
    }

    private function getReflectionClass($className): object
    {
        if (!isset($this->reflectionClassMap[$className])) {
            $this->reflectionClassMap[$className] = new ReflectionClass($className);
        }
        return $this->reflectionClassMap[$className];
    }
}

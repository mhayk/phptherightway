<?php

namespace Mhayk\DI;

class Resolver
{
    private $dependencies;

    public function method($method, array $dependencies = [])
    {
        $this->dependencies = $dependencies;

        $info = new \ReflectionFunction($method);
        $parameters = $info->getParameters();
        $parameters = $this->resolveParameters($parameters);

        return call_user_func_array($info->getClosure(), $parameters);
    }

    public function class ($class, array $dependencies = [])
    {
        $this->dependencies = $dependencies;

        $class = new \ReflectionClass($class);

        if(!$class->isInstantiable()) {
            throw new \Exeption("{$class} is not instantiable");
        }

        $constructor = $class->getConstructor();

        if(!$constructor) {
            return new $class->name;
        }

        $parameters = $constrtuctor->getParameters();
        $parameters = $this->resolveParameters($parameters);

        return $class->newInstanceArgs($parameters);
    }

    
} 
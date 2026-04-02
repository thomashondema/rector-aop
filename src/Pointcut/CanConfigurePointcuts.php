<?php

namespace RectorAop\Pointcut;

trait CanConfigurePointcuts
{
    protected function pointcut(): Pointcut
    {
        if (! isset($this->configuration['paths'])) {
            return new TruePointcut;
        }
        if (count($this->configuration['paths']) == 1) {
            return $this->configuration['paths'][0];
        }

        return new AndPointcut(...$this->configuration['pointcuts']);
    }
}

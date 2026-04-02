<?php

namespace RectorAop;

trait hasArrayConfiguration
{
    protected array $configuration = [];
    public function configure(array $configuration): void
    {
        $this->configuration = $configuration;
    }
}

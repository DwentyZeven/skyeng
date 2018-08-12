<?php

namespace my\Provider;

/**
 * Interface DataProviderInterface
 * @package my\Provider
 */
interface DataProviderInterface
{
    /**
     * @param array $request
     * @return array
     */
    public function get(array $request): array;
}
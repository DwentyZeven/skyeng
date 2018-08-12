<?php

namespace my\Provider;

/**
 * Class FileDataProvider
 * @package my\Provider
 */
class FileDataProvider implements DataProviderInterface
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @inheritdoc
     */
    function get(array $request): array
    {
        // TODO: Implement get() method.
    }
}
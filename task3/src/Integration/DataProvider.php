<?php

namespace src\Integration;

/**
 * Лучше создать единый интерфейс для источников данных DataProviderInterface,
 * а этот класс будет одной из его реализаций.
 * Вместо общего названия DataProvider для этого класса можно определить более
 * конкретное, так как это реализация одного из источников получения данных.
 */
class DataProvider
{
    private $host;
    private $user;
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param array $request
     *
     * @return array
     */
    public function get(array $request)
    {
        // returns a response from external service
    }
}
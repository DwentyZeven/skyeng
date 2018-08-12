<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

/**
 * Лучше передавать экземпляр класса DataProvider через конструктор,
 * а не делать наследование от него. Данный класс не должен ничего знать о том,
 * как будут получены данные, он должен получать готовый источник данных.
 * Название класса выбрано не очень удачно: это скорее менеджер, чем декоратор,
 * и тем более не всё вместе.
 */
class DecoratorManager extends DataProvider
{
    /**
     * Свойства $cache и $logger не должны быть public.
     * Они должны устанавливаться только через конструктор
     * и использоваться только внутри класса.
     */
    public $cache;
    public $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct($host, $user, $password, CacheItemPoolInterface $cache)
    {
        /**
         * Этот класс не должен получать никаких настроек для соединения
         * с источником данных, все они должны быть внутри провайдера.
         */
        parent::__construct($host, $user, $password);
        $this->cache = $cache;
    }

    /**
     * Сеттер для логгера лишний, он должен устанавливаться только
     * один раз через конструктор.
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Метод не может использовать inheritdoc, так как не переопределяет
     * и не реализует другой метод из родительского класса.
     *
     * {@inheritdoc}
     */
    public function getResponse(array $input)
    {
        try {
            /**
             * Ключ для получения данных из кэша лучше определить в строковой константе,
             * а не использовать для этого отдельный метод.
             */
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            /**
             * Данные нужно получать через провайдер, который должен
             * передаваться через конструктор.
             */
            $result = parent::get($input);

            /**
             * Для установки кэша на определенный период, а не до конкретной даты,
             * удобнее использовать метод expiresAfter и передавать нужный интервал.
             * После установки значения необходимо сохранить кэш: $this->cache->save($cacheItem)
             */
            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (Exception $e) {
            $this->logger->critical('Error');
        }

        return [];
    }

    /**
     * Формат JSON содержит недопустимые для ключа символы,
     * его использование вызовет исключение.
     */
    public function getCacheKey(array $input)
    {
        return json_encode($input);
    }
}
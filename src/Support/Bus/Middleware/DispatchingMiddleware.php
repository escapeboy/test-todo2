<?php
declare(strict_types=1);

namespace Todo\Support\Bus\Middleware;

use Doctrine\ORM\EntityManager;
use Illuminate\Container\Container;

final class DispatchingMiddleware
{
    private Container $container;
    private EntityManager $entityManager;

    public function __construct(Container $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $command
     * @param \Closure $next
     *
     * @return mixed|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Throwable
     */
    public function handle($command, \Closure $next)
    {
        $commandClass = $command::class;
        if (!class_exists($commandClass)) {
            return $next($command);
        }
        if (!$this->entityManager->isOpen()) {
            $this->entityManager = EntityManager::create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }
        $class = $commandClass . 'Handler';
        if (!class_exists($class)) {
            return $next($command);
        }

        try {
            $handler = $this->container->make($class);
            $handler->handle($command);
            $this->entityManager->flush();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}

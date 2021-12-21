<?php
declare(strict_types=1);

namespace Todo\Support\Doctrine;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class BaseInMemoryEntityRepository
{
    /**
     * @var object[]
     */
    protected array $items = [];

    abstract protected function getEntityClass(): string;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return object[]
     * @throws \ReflectionException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $reflection = new \ReflectionClass($this->getEntityClass());

        $items = array_filter($this->items, static function ($item) use ($reflection, $criteria) {
            foreach ($criteria as $field => $value) {
                $property = $reflection->getProperty($field);
                $property->setAccessible(true);
                if ($property->getValue($item) !== $value) {
                    return false;
                }
            }

            return true;
        });

        return array_splice($items, $offset ?? 0, $limit ?? count($items));
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function findOneBy(array $criteria): mixed
    {
        return Arr::first($this->findBy($criteria));
    }

    /**
     * @param $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return mixed
     */
    public function find($id, $lockMode = null, $lockVersion = null): mixed
    {
        if (!array_key_exists($id, $this->items)) {
            return null;
        }

        return Arr::get($this->items, $id);
    }

    /**
     * @return object[]
     */
    public function findAll(): array
    {
        return $this->items;
    }

    public function remove($item): void
    {
        unset($this->items[$item->getId()]);
    }

    public function add($item): void
    {
        $this->items[$item->getId()] = $item;
    }

    public function persist($item): void
    {
        if (method_exists($item, 'getId') && $item->getId() === null) {
            $item->setId(Str::uuid()->toString());
        }
        $this->add($item);
    }
}

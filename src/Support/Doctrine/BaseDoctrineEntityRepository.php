<?php
declare(strict_types=1);

namespace Todo\Support\Doctrine;

use Doctrine\ORM\EntityRepository;

abstract class BaseDoctrineEntityRepository extends EntityRepository
{
    public function persist($entity): void
    {
        $this->_em->persist($entity);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove($entity): void
    {
        $this->_em->remove($entity);
    }
}

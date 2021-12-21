<?php
declare(strict_types=1);

namespace Todo\Tasks;

use Todo\Support\Doctrine\BaseDoctrineEntityRepository;

final class DoctrineTasksRepository extends BaseDoctrineEntityRepository implements TasksRepository
{

}

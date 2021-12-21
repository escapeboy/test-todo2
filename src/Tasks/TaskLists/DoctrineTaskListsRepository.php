<?php
declare(strict_types=1);

namespace Todo\Tasks\TaskLists;

use Todo\Support\Doctrine\BaseDoctrineEntityRepository;

final class DoctrineTaskListsRepository extends BaseDoctrineEntityRepository implements TaskListsRepository
{

}

<?php
declare(strict_types=1);

namespace Todo\Tasks;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Support\Arrayable;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Todo\Tasks\TaskLists\TaskList;

/**
 * @ORM\Entity
 */
class Task implements Arrayable
{
    use Timestamps;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $id;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private string $content;

    /**
     * @ORM\Column(type="smallint")
     * @var int
     */
    private int $priority = 1;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime|null
     */
    private ?\DateTime $completedAt = null;

    /**
     * @ORM\ManyToOne(targetEntity="Todo\Tasks\TaskLists\TaskList", inversedBy="tasks")
     * @var TaskList
     */
    private TaskList $list;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return \DateTime|null
     */
    public function getCompletedAt(): ?\DateTime
    {
        return $this->completedAt;
    }

    /**
     * @param \DateTime|null $completedAt
     */
    public function setCompletedAt(?\DateTime $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    /**
     * @return TaskList
     */
    public function getList(): TaskList
    {
        return $this->list;
    }

    /**
     * @param TaskList $list
     */
    public function setList(TaskList $list): void
    {
        $this->list = $list;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

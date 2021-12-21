<?php
declare(strict_types=1);

namespace Todo\Tasks\TaskLists;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Support\Arrayable;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Todo\Tasks\Task;
use Todo\Users\User;

/**
 * @ORM\Entity
 */
class TaskList implements Arrayable
{
    use Timestamps;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $id;

    /**
     * @ORM\ManyToOne(targetEntity="Todo\Users\User", inversedBy="lists")
     * @var User
     */
    private User $user;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity="Todo\Tasks\Task", cascade={"persist", "remove"}, mappedBy="list")
     * @ORM\OrderBy({"createdAt"="DESC"})
     * @var Collection|Task[]
     */
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection|array
    {
        return $this->tasks;
    }

    public function addTask(Task $task): void
    {
        if (!$this->tasks->contains($task)) {
            $task->setList($this);
            $this->tasks->add($task);
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

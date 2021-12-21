<?php
declare(strict_types=1);

namespace Todo\Users;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Todo\Tasks\Task;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User implements AuthenticatableContract, CanResetPasswordContract, Arrayable, UserEntityInterface
{
    use Timestamps,
        Authenticatable,
        CanResetPassword,
        HasApiTokens;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $id;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $name;
    /**
     * @ORM\Column(type="string")
     */
    private string $email;

    /**
     * @ORM\OneToMany(targetEntity="Todo\Tasks\TaskLists\TaskList", mappedBy="user", cascade={"persist", "remove"})
     * @var Collection
     */
    private Collection $lists;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
    }

    public function getAvatar(): ?string
    {
        return $this->avatar ?? 'https://www.gravatar.com/avatar/' . md5($this->email);
    }

    /**
     * @return array<string|string>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->getAvatar(),
        ];
    }

    public function getIdentifier(): string
    {
        return $this->id;
    }

    public function validateForPassportPasswordGrant($password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->getId();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Collection|Task[]
     */
    public function getLists(): Collection|array
    {
        return $this->lists;
    }
}

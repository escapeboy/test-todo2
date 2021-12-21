<?php
declare(strict_types=1);

namespace Todo\Auth\OAuth;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Todo\Users\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="personal_access_tokens")
 */
class PersonalAccessToken
{
    use Timestamps;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="bigint")
     */
    protected int $id;

    /**
     * @ORM\ManyToOne(targetEntity="PriceX\Users\User")
     * @var User
     */
    protected User $tokenable;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected string $tokenableType;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected string $name;
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @var string
     */
    protected string $token;
    /**
     * @ORM\Column(type="array", nullable=true)
     * @var array|null
     */
    protected ?array $abilities;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime|null
     */
    protected ?\DateTime $lastUsedAt;
}

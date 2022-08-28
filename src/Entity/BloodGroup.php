<?php

namespace App\Entity;

use App\Repository\BloodGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BloodGroupRepository::class)
 */
class BloodGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="bloodGroup")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Requester::class, mappedBy="bloodGroupId")
     */
    private $requester;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->requester = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setBloodGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getBloodGroup() === $this) {
                $user->setBloodGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Requester>
     */
    public function getRequester(): Collection
    {
        return $this->requester;
    }

    public function addRequester(Requester $requester): self
    {
        if (!$this->requester->contains($requester)) {
            $this->requester[] = $requester;
            $requester->setBloodGroupId($this);
        }

        return $this;
    }

    public function removeRequester(Requester $requester): self
    {
        if ($this->requester->removeElement($requester)) {
            // set the owning side to null (unless already changed)
            if ($requester->getBloodGroupId() === $this) {
                $requester->setBloodGroupId(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SortieArchiveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieArchiveRepository::class)
 */
class SortieArchive
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateStart;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $organisator;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private $Participators;

    public function __construct()
    {
        $this->Participators = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->DateStart;
    }

    public function setDateStart(\DateTimeInterface $DateStart): self
    {
        $this->DateStart = $DateStart;

        return $this;
    }

    public function getOrganisator(): ?User
    {
        return $this->organisator;
    }

    public function setOrganisator(?User $organisator): self
    {
        $this->organisator = $organisator;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipators(): Collection
    {
        return $this->Participators;
    }

    public function addParticipator(User $participator): self
    {
        if (!$this->Participators->contains($participator)) {
            $this->Participators[] = $participator;
        }

        return $this;
    }

    public function removeParticipator(User $participator): self
    {
        $this->Participators->removeElement($participator);

        return $this;
    }
}

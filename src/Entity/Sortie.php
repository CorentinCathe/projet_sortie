<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
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
     * @ORM\Column(type="datetime")
     */
    private $dateHourStart;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLimitSubscritption;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbSubMax;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $information;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sorties")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sorties")
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="sorties")
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="sorties")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sortieOrga")
     */
    private $organisator;

    public function __construct()
    {
        $this->user = new ArrayCollection();
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

    public function getDateHourStart(): ?\DateTimeInterface
    {
        return $this->dateHourStart;
    }

    public function setDateHourStart(\DateTimeInterface $dateHourStart): self
    {
        $this->dateHourStart = $dateHourStart;

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(\DateInterval $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateLimitSubscritption(): ?\DateTimeInterface
    {
        return $this->dateLimitSubscritption;
    }

    public function setDateLimitSubscritption(\DateTimeInterface $dateLimitSubscritption): self
    {
        $this->dateLimitSubscritption = $dateLimitSubscritption;

        return $this;
    }

    public function getNbSubMax(): ?int
    {
        return $this->nbSubMax;
    }

    public function setNbSubMax(int $nbSubMax): self
    {
        $this->nbSubMax = $nbSubMax;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): self
    {
        $this->information = $information;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

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
}

<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $meetingDatetime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $meetingPostalCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $meetingCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeOfMusic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instrument;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMeetingDatetime(): ?\DateTimeInterface
    {
        return $this->meetingDatetime;
    }

    public function setMeetingDatetime(\DateTimeInterface $meetingDatetime): self
    {
        $this->meetingDatetime = $meetingDatetime;

        return $this;
    }

    public function getMeetingPostalCode(): ?string
    {
        return $this->meetingPostalCode;
    }

    public function setMeetingPostalCode(string $meetingPostalCode): self
    {
        $this->meetingPostalCode = $meetingPostalCode;

        return $this;
    }

    public function getMeetingCity(): ?string
    {
        return $this->meetingCity;
    }

    public function setMeetingCity(string $meetingCity): self
    {
        $this->meetingCity = $meetingCity;

        return $this;
    }

    public function getTypeOfMusic(): ?string
    {
        return $this->typeOfMusic;
    }

    public function setTypeOfMusic(?string $typeOfMusic): self
    {
        $this->typeOfMusic = $typeOfMusic;

        return $this;
    }

    public function getInstrument(): ?string
    {
        return $this->instrument;
    }

    public function setInstrument(?string $instrument): self
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

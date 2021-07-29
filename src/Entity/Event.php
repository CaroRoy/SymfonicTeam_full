<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Positive(message="Le code postal doit être un nombre positif à 5 chiffres")
     * @Assert\Length(
     *  min = 5, minMessage="Le code postal doit contenir 5 chiffres",
     *  max = 5, maxMessage="Le code postal doit contenir 5 chiffres"
     * )
     */
    private $meetingPostalCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $meetingCity;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $typeOfMusic;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $instrument;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $meetingPlace;

    /**
     * @ORM\OneToMany(targetEntity=ReplyEventUser::class, mappedBy="event", orphanRemoval=true)
     */
    private $replyEventUsers;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="event", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->replyEventUsers = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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

    public function getTypeOfMusic()
    {
        return $this->typeOfMusic;
    }

    public function setTypeOfMusic($typeOfMusic): self
    {
        $this->typeOfMusic = $typeOfMusic;

        return $this;
    }

    public function getInstrument()
    {
        return $this->instrument;
    }

    public function setInstrument($instrument): self
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

    public function getMeetingPlace(): ?string
    {
        return $this->meetingPlace;
    }

    public function setMeetingPlace(?string $meetingPlace): self
    {
        $this->meetingPlace = $meetingPlace;

        return $this;
    }

    /**
     * @return Collection|ReplyEventUser[]
     */
    public function getReplyEventUsers(): Collection
    {
        return $this->replyEventUsers;
    }

    public function addReplyEventUser(ReplyEventUser $replyEventUser): self
    {
        if (!$this->replyEventUsers->contains($replyEventUser)) {
            $this->replyEventUsers[] = $replyEventUser;
            $replyEventUser->setEvent($this);
        }

        return $this;
    }

    public function removeReplyEventUser(ReplyEventUser $replyEventUser): self
    {
        if ($this->replyEventUsers->removeElement($replyEventUser)) {
            // set the owning side to null (unless already changed)
            if ($replyEventUser->getEvent() === $this) {
                $replyEventUser->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setEvent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getEvent() === $this) {
                $comment->setEvent(null);
            }
        }

        return $this;
    }
}

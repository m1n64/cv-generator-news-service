<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NewsRepository;
use App\Services\News\Enums\NewsStatusesEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ORM\Table(name: '`news`')]
/*#[ApiResource(
    normalizationContext: ['groups' => ['news:read']],
    denormalizationContext: ['groups' => ['news:write']],
    paginationEnabled: true,
    paginationItemsPerPage: 10
)]*/
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['news:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['news:read', 'news:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['news:read', 'news:write'])]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'news')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['news:read', 'news:write'])]
    private ?User $author = null;

    #[ORM\Column]
    #[Groups(['news:read', 'news:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['news:read', 'news:write'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'news_status_enum', nullable: true)]
    #[Groups(['news:read', 'news:write'])]
    private ?NewsStatusesEnum $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?NewsStatusesEnum
    {
        return $this->status;
    }

    public function setStatus(?NewsStatusesEnum $status): static
    {
        $this->status = $status;

        return $this;
    }
}

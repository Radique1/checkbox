<?php

declare(strict_types = 1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\CreatedUpdatedAtTrait;
use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Timestampable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiFilter(
    filterClass: SearchFilter::class,
    properties: [
        'authors.surname' => 'partial'
    ]
)]
#[ApiResource(
    operations: [
        new Get(
            requirements: ['id' => '\\d+']
        ),
        new Post(),
        new Patch(
            requirements: ['id' => '\\d+']
        ),
        new Delete(
            requirements: ['id' => '\\d+']
        ),
        new GetCollection()
    ],
    normalizationContext: ['groups' => [AbstractEntity::OUTPUT_GROUP]],
    denormalizationContext: ['groups' => [AbstractEntity::INPUT_GROUP]],
    order: ['createdAt' => 'DESC'],
)]
#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Index(columns: ['title'], name: 'book_title')]
class Book extends AbstractEntity implements Timestampable
{
    use CreatedUpdatedAtTrait;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private string $title;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private ?string $description = null;

    #[ORM\OneToOne(inversedBy: 'book', targetEntity: BookImage::class, cascade: ['persist', 'remove'])]
    #[MaxDepth(1)]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private BookImage $bookImage;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    #[ORM\JoinTable(name: 'books_authors')]
    #[MaxDepth(1)]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private Collection $authors;

    #[ORM\Column(type: 'datetimetz')]
    #[Assert\NotBlank]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private DateTimeInterface $publishDate;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getBookImage(): BookImage
    {
        return $this->bookImage;
    }

    public function setBookImage(BookImage $bookImage): self
    {
        $this->bookImage = $bookImage;

        return $this;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function setAuthors(Collection $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->add($author);
            $author->removeBook($this);
        }

        return $this;
    }

    public function getPublishDate(): DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }
}

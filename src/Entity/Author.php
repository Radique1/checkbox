<?php

declare(strict_types = 1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\CreatedUpdatedAtTrait;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Timestampable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

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
#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Index(columns: ['surname', 'name'], name: 'author_surname_name')]
#[Vich\Uploadable]
class Author extends AbstractEntity implements Timestampable
{
    use CreatedUpdatedAtTrait;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\Length(min: 3)]
    #[Assert\NotBlank]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private string $name;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private string $surname;
    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private ?string $middleName = null;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'authors')]
    #[MaxDepth(1)]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function setBooks(Collection $books): self
    {
        $this->books = $books;

        return $this;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->addAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->contains($book)) {
            $this->books->add($book);
            $book->removeAuthor($this);
        }

        return $this;
    }
}

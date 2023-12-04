<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\BookImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: BookImageRepository::class)]
#[Vich\Uploadable]
class BookImage extends AbstractEntity
{
    #[ORM\OneToOne(mappedBy: 'bookImage', targetEntity: Book::class)]
    private Book $book;

    #[Vich\UploadableField(mapping: 'books', fileNameProperty: 'imageName', size: 'imageSize', mimeType: 'mimeType')]
    #[Assert\NotBlank]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: [
            'image/jpeg',
            'image/png',
        ],
    )]
    #[Groups([AbstractEntity::INPUT_GROUP])]
    private File $image;

    #[Groups([AbstractEntity::OUTPUT_GROUP])]
    private ?string $outputImage;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Assert\Length(max: 255)]
    private string $imageName;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $imageSize;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $mimeType;

    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook(Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getImage(): File
    {
        return $this->image;
    }

    public function setImage(File $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getOutputImage(): ?string
    {
        return $this->outputImage;
    }

    public function setOutputImage(?string $outputImage): self
    {
        $this->outputImage = $outputImage;

        return $this;
    }

    public function getImageName(): string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageSize(): int
    {
        return $this->imageSize;
    }

    public function setImageSize(int $imageSize): self
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}

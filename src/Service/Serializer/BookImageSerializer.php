<?php

declare(strict_types = 1);

namespace App\Service\Serializer;

use App\Entity\BookImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;
use Vich\UploaderBundle\Storage\StorageInterface;

class BookImageSerializer implements NormalizerInterface, DenormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'BOOK_IMAGE_NORMALIZER_ALREADY_CALLED';

    public function __construct(private readonly StorageInterface $storage)
    {
    }

    /**
     * @param BookImage $object
     * @param string|null $format
     * @param array $context
     *
     * @return array
     *
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;

        $path = $this->storage->resolvePath($object, 'image');
        $data = file_get_contents($path);

        $object->setOutputImage(base64_encode($data));

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): BookImage
    {
        $bookImage = new BookImage();

        try {
            $filePath = tempnam(sys_get_temp_dir(), 'UploadedFile');
            $data = base64_decode($data['file']);
            file_put_contents($filePath, $data);
        } catch (Throwable) {
            throw new BadRequestHttpException();
        }

        $path = explode('/', $filePath);
        $originalName = end($path);

        $image = new UploadedFile(path: $filePath, originalName: $originalName, test: true);

        $bookImage
            ->setImage($image)
            ->setImageName($originalName)
            ->setImageSize($image->getSize())
            ->setMimeType($image->getMimeType());

        return $bookImage;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED]) && $context[self::ALREADY_CALLED]) {
            return false;
        }

        return $data instanceof BookImage;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return $type === BookImage::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            BookImage::class => false,
        ];
    }
}
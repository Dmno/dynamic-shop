<?php

namespace App\Service;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageService
{
    public function __construct(
        private ImageRepository $imageRepository,
        private EntityManagerInterface $em,
        private ParameterBagInterface $parameterBag
    )
    {
    }

    public function generateImageName(string $fileName): string
    {
        $split = explode('.', $fileName,2);
        $generatedFileName = $split[0] . "_" . substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 5)), 0, 5);
        return $generatedFileName . "." . $split[1];
    }

    public function checkAndProcessFile(object $imageObject, bool $isProduct): Image
    {
        $destination = $this->parameterBag->get('kernel.project_dir') . '/public';
        $destination .= $isProduct ? '/products' : '/front';
        $fileName = $imageObject->getClientOriginalName();

        if ($this->imageRepository->findOneBy(['title' => $fileName])) {
            $fileName = $this->generateImageName($fileName);
        }

        $imageObject->move($destination, $fileName);

        $image = new Image();
        $image->setTitle($fileName);
        $this->em->persist($image);
        $this->em->flush();
        return $image;
    }
}
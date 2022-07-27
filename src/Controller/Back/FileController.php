<?php

namespace App\Controller\Back;

use App\Entity\RegistrationInfo;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FileController extends AbstractController
{
    private AsciiSlugger $slugger;

    public function __construct(protected string $uploadPath)
    {
        $this->slugger = new AsciiSlugger('fr_FR');
    }

    #[Route('/telecharger-attestation/{registrationInfo}', name: 'bo_download_attestation', methods: ['GET'])]
    public function download(RegistrationInfo $registrationInfo): Response
    {
        $filePath = $registrationInfo->getMedicalCertificateUrl();
        if (null === $filePath) {
            throw new LogicException('invalid file');
        }

        /** @var string $firstName */
        $firstName = $registrationInfo->getAdherent()?->getFirstName();
        /** @var string $lastName */
        $lastName = $registrationInfo->getAdherent()?->getLastName();

        $fileName = strtolower(sprintf(
            'attestation_%s_%s',
            $this->slugger->slug($firstName),
            $this->slugger->slug($lastName),
        ));

        $pathInfo = pathinfo($filePath);

        if (isset($pathInfo['extension'])) {
            $fileName = sprintf('%s.%s', $fileName, $pathInfo['extension']);
        }

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $fileName);

        return $response;
    }
}

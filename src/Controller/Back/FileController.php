<?php

namespace App\Controller\Back;

use App\Entity\Adherent;
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
    public function medicalCertificate(RegistrationInfo $registrationInfo): Response
    {
        $filePath = $registrationInfo->getMedicalCertificateUrl();
        if (null === $filePath) {
            throw new LogicException('invalid file');
        }

        /** @var Adherent $adherent */
        $adherent = $registrationInfo->getAdherent();
        $fileName = $this->buildFileName($adherent, $filePath, 'attestation');

        return $this->getFileContent($filePath, $fileName);
    }

    #[Route('/telecharger-photo/{adherent}', name: 'bo_download_picture', methods: ['GET'])]
    public function picture(Adherent $adherent): Response
    {
        $filePath = $adherent->getPictureUrl();
        if (null === $filePath) {
            throw new LogicException('invalid file');
        }

        $fileName = $this->buildFileName($adherent, $filePath, 'photo');

        return $this->getFileContent($filePath, $fileName);
    }

    private function getFileContent(string $filePath, string $fileName): Response
    {
        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $fileName);

        return $response;
    }

    private function buildFileName(Adherent $adherent, string $filePath, string $prefix): string
    {
        /** @var string $firstName */
        $firstName = $adherent->getFirstName();
        /** @var string $lastName */
        $lastName = $adherent->getLastName();

        $fileName = strtolower(sprintf(
            '%s_%s_%s',
            $prefix,
            $this->slugger->slug($firstName),
            $this->slugger->slug($lastName),
        ));

        $pathInfo = pathinfo($filePath);

        if (isset($pathInfo['extension'])) {
            $fileName = sprintf('%s.%s', $fileName, $pathInfo['extension']);
        }

        return $fileName;
    }
}

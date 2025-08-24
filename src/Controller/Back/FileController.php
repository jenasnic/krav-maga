<?php

namespace App\Controller\Back;

use App\Entity\Adherent;
use App\Entity\Registration;
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

    #[Route('/telecharger-attestation/{registration}', name: 'bo_download_attestation', methods: ['GET'])]
    public function medicalCertificate(Registration $registration): Response
    {
        $filePath = $registration->getMedicalCertificateUrl();
        if (null === $filePath) {
            throw new \LogicException('invalid file');
        }

        $fileName = $this->buildFileName($registration->getAdherent(), $filePath, 'certificat');

        return $this->getFileContent($filePath, $fileName);
    }

    #[Route('/telecharger-formulaire-licence/{registration}', name: 'bo_download_licence_form', methods: ['GET'])]
    public function licenceForm(Registration $registration): Response
    {
        $filePath = $registration->getLicenceFormUrl();
        if (null === $filePath) {
            throw new \LogicException('invalid file');
        }

        $fileName = $this->buildFileName($registration->getAdherent(), $filePath, 'licence');

        return $this->getFileContent($filePath, $fileName);
    }

    #[Route('/telecharger-pass-citizen/{registration}', name: 'bo_download_pass_citizen', methods: ['GET'])]
    public function passCitizen(Registration $registration): Response
    {
        $filePath = $registration->getPassCitizenUrl();
        if (null === $filePath) {
            throw new \LogicException('invalid file');
        }

        $fileName = $this->buildFileName($registration->getAdherent(), $filePath, 'pass_citizen');

        return $this->getFileContent($filePath, $fileName);
    }

    #[Route('/telecharger-pass-sport/{registration}', name: 'bo_download_pass_sport', methods: ['GET'])]
    public function passSport(Registration $registration): Response
    {
        $filePath = $registration->getPassSportUrl();
        if (null === $filePath) {
            throw new \LogicException('invalid file');
        }

        $fileName = $this->buildFileName($registration->getAdherent(), $filePath, 'pass_sport');

        return $this->getFileContent($filePath, $fileName);
    }

    #[Route('/telecharger-photo/{adherent}', name: 'bo_download_picture', methods: ['GET'])]
    public function picture(Adherent $adherent): Response
    {
        $filePath = $adherent->getPictureUrl();
        if (null === $filePath) {
            throw new \LogicException('invalid file');
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

    private function buildFileName(Adherent $adherent, string $filePath, string $suffix): string
    {
        /** @var string $firstName */
        $firstName = $adherent->getFirstName();
        /** @var string $lastName */
        $lastName = $adherent->getLastName();

        $fileName = strtolower(sprintf(
            '%s_%s_%s',
            $this->slugger->slug($firstName),
            $this->slugger->slug($lastName),
            $suffix,
        ));

        $pathInfo = pathinfo($filePath);

        if (isset($pathInfo['extension'])) {
            $fileName = sprintf('%s.%s', $fileName, $pathInfo['extension']);
        }

        return $fileName;
    }
}

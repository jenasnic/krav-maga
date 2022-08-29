<?php

namespace App\Service\Export;

use App\Entity\Adherent;
use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use LogicException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdherentCsvExport extends AbstractCsvExport
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected RegistrationRepository $registrationRepository,
    ) {
    }

    public function export(): StreamedResponse
    {
        $result = $this->registrationRepository->findForExport();

        return $this->getStreamedResponse($result);
    }

    /**
     * @return array<string>
     */
    protected function getHeaders(): array
    {
        return [
            'Nom',
            'Prénom',
            'Représentant légal',
            'Nom contact',
            'Prénom contact',
            'Téléphone contact',
            'Pseudo Facebook',
            'Date de naissance',
            'Sexe',
            'E-mail',
            'Téléphone',
            'Adresse',
            'Commentaire',
            'Objectif',
            'Droit à l\'image',
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function buildLine(mixed $data): array
    {
        if (!$data instanceof Registration) {
            throw new LogicException('invalid data');
        }

        $legalRepresentative = '';
        if ($data->isWithLegalRepresentative()) {
            $legalRepresentative = sprintf('%s %s', $data->getLegalRepresentative()->getLastName(), $data->getLegalRepresentative()->getFirstName());
        }

        /** @var array<int, string> $line */
        $line = [
            $data->getAdherent()->getLastName(),
            $data->getAdherent()->getFirstName(),
            $legalRepresentative,
            $data->getEmergency()->getLastName(),
            $data->getEmergency()->getFirstName(),
            $data->getEmergency()->getPhone(),
            $data->getAdherent()->getPseudonym(),
            $data->getAdherent()->getBirthDate()->format('d/m/Y'),
            $this->translator->trans('enum.gender.'.$data->getAdherent()->getGender()),
            $data->getAdherent()->getEmail(),
            $data->getAdherent()->getPhone(),
            $data->getAdherent()->getAddress(),
            $data->getComment(),
            $data->getPurpose()->getLabel(),
            $data->getCopyrightAuthorization() ? $this->translator->trans('global.yes') : $this->translator->trans('global.no'),
        ];

        return $line;
    }

    protected function getFilename(): string
    {
        return 'liste_adherents_kmis.csv';
    }
}

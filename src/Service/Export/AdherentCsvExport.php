<?php

namespace App\Service\Export;

use App\Entity\Registration;
use App\Repository\RegistrationRepository;
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
            'Contact urgence',
            'Pseudo Facebook',
            'Date de naissance',
            'Sexe',
            'E-mail',
            'Téléphone',
            'Adresse',
            'Commentaire',
            'Objectif',
            'Droit à l\'image',
            'N° de licence',
            'Date licence',
            'Saison',
            'Formule',
            'Pass 15',
            'Pass 50',
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function buildLine(mixed $data): array
    {
        if (!$data instanceof Registration) {
            throw new \LogicException('invalid data');
        }

        $legalRepresentative = '';
        if ($data->isWithLegalRepresentative()) {
            $legalRepresentative = sprintf(
                '%s %s',
                $data->getLegalRepresentative()?->getLastName() ?? '',
                $data->getLegalRepresentative()?->getFirstName() ?? '',
            );
        }

        $emergencyContact = sprintf(
            '%s %s [%s]',
            $data->getEmergency()?->getLastName() ?? '',
            $data->getEmergency()?->getFirstName() ?? '',
            $data->getEmergency()?->getPhone() ?? '',
        );

        /** @var array<int, string> $line */
        $line = [
            $data->getAdherent()->getLastName(),
            $data->getAdherent()->getFirstName(),
            $legalRepresentative,
            $emergencyContact,
            $data->getAdherent()->getPseudonym(),
            $data->getAdherent()->getBirthDate()?->format('d/m/Y') ?? '',
            $this->translator->trans('enum.gender.'.$data->getAdherent()->getGender()),
            $data->getAdherent()->getEmail(),
            $data->getAdherent()->getPhone(),
            $data->getAdherent()->getAddress(),
            $data->getComment(),
            $data->getPurpose()?->getLabel(),
            $data->getCopyrightAuthorization() ? $this->translator->trans('global.yes') : $this->translator->trans('global.no'),
            $data->getLicenceNumber(),
            $data->getLicenceDate()?->format('d/m/Y'),
            $data->getSeason()->getDisplayLabel(),
            $data->getPriceOption()?->getLabel(),
            $data->isUsePassCitizen() ? $this->translator->trans('global.yes') : $this->translator->trans('global.no'),
            $data->isUsePassSport() ? $this->translator->trans('global.yes') : $this->translator->trans('global.no'),
        ];

        return $line;
    }

    protected function getFilename(): string
    {
        return 'liste_adherents_kmis.csv';
    }
}

<?php

namespace App\Service\Export;

use App\Repository\AdherentRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdherentCsvExport extends AbstractCsvExport
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected AdherentRepository $adherentRepository,
    ) {
    }

    public function export(): StreamedResponse
    {
        $result = $this->adherentRepository->findForExport();

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
            'Sexe',
            'Date de naissance',
            'Téléphone',
            'E-mail',
            'Objectif',
            'Droit à l\'image',
        ];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string>
     */
    protected function buildLine(array $data): array
    {
        return [
            $data['firstName'],
            $data['lastName'],
            $this->translator->trans('enum.gender.'.$data['gender']),
            $data['birthDate'],
            $data['phone'],
            $data['email'],
            $data['purposeLabel'],
            $data['copyrightAuthorization'] ? 'Oui' : 'Non',
        ];
    }

    protected function getFilename(): string
    {
        return 'liste_adherents_kmis.csv';
    }
}

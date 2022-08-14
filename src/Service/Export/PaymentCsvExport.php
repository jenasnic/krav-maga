<?php

namespace App\Service\Export;

use App\Entity\Payment\AbstractPayment;
use App\Entity\Payment\AncvPayment;
use App\Entity\Payment\CheckPayment;
use App\Entity\Payment\PassPayment;
use App\Entity\Payment\TransferPayment;
use App\Entity\Season;
use App\Repository\Payment\PaymentRepository;
use LogicException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaymentCsvExport extends AbstractCsvExport
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected PaymentRepository $paymentRepository,
    ) {
    }

    public function export(Season $season): StreamedResponse
    {
        /** @var int $seasonId */
        $seasonId = $season->getId();
        $result = $this->paymentRepository->findForExport($seasonId);

        return $this->getStreamedResponse($result);
    }

    /**
     * @return array<string>
     */
    protected function getHeaders(): array
    {
        return [
            'Date',
            'Type',
            'Montant',
            'Adhérent',
            'N° ANCV',
            'N° Chèque',
            'N° Pass',
            'Libellé virement',
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function buildLine(mixed $data): array
    {
        if (!$data instanceof AbstractPayment) {
            throw new LogicException('invalid data');
        }

        /** @var float $amount */
        $amount = $data->getAmount();

        /** @var array<int, string> $line */
        $line = [
            $data->getDate()->format('d/m/Y'),
            $this->translator->trans('enum.paymentType.'.$data->getPaymentType()),
            number_format($amount, 2, ',', ' '),
            $data->getAdherent()->getFullName(),
            ($data instanceof AncvPayment) ? $data->getNumber() : '',
            ($data instanceof CheckPayment) ? $data->getNumber() : '',
            ($data instanceof PassPayment) ? $data->getNumber() : '',
            ($data instanceof TransferPayment) ? $data->getLabel() : '',
        ];

        return $line;
    }

    protected function getFilename(): string
    {
        return 'liste_paiements_kmis.csv';
    }
}

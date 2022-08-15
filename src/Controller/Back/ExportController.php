<?php

namespace App\Controller\Back;

use App\Entity\Season;
use App\Service\Export\AdherentCsvExport;
use App\Service\Export\PaymentCsvExport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends AbstractController
{
    #[Route('/exporter-les-inscrits', name: 'bo_export_adherent', methods: ['GET'])]
    public function exportAdherent(AdherentCsvExport $adherentCsvExport): Response
    {
        return $adherentCsvExport->export();
    }

    #[Route('/exporter-les-paiements/{season}', name: 'bo_export_payment', methods: ['GET'])]
    public function exportPayment(PaymentCsvExport $paymentCsvExport, Season $season): Response
    {
        return $paymentCsvExport->export($season);
    }
}

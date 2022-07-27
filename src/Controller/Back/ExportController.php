<?php

namespace App\Controller\Back;

use App\Service\Export\AdherentCsvExport;
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
}

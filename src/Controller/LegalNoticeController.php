<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegalNoticeController extends AbstractController {
    /**
     * @Route("mentions-légales", name="legal_notice")
     */
    public function show(): Response {
        return $this->render("legal_notice.html.twig");
    }
}
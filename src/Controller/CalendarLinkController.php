<?php

namespace App\Controller;

use App\Entity\Appointement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Spatie\CalendarLinks\Link;

class CalendarLinkController extends AbstractController
{
    #[IsGranted('ROLE_CONSULTANT')]
    public function getLinks(Appointement $appointment): Response
    {
        $link = new Link($appointment->getTitle(), $appointment->getDate(), $appointment->getDate());
        if ($appointment->getDescription()) {
            $link->description($appointment->getDescription());
        }
        if ($appointment->getAdress()) {
            $link->address($appointment->getAdress());
        }
        return $this->render('calendar_link/_calendarLink.html.twig', [
            'googleLink' => $link->google(),
            'icsLink' => $link->ics(),
            'outlookLink' => $link->webOutlook()
        ]);
    }
}

<?php

namespace App\Controller\BackOffice;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashBoardController extends Controller
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('dash_board/index.html.twig', [
            'page_title' => 'DashBoard',
            'page_subtitle' => '',
        ]);
    }

}

<?php

namespace MissionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response|null
     * @Route("/")
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($user instanceof User){
            return $this->redirectToRoute("mission_mission_page_list");
        }else{
            return $this->redirectToRoute("fos_user_security_login");
        }
//        return $this->render('MissionBundle:Default:index.html.twig');
    }
}

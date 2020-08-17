<?php


namespace MissionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Security("has_role('ROLE_USER')")
 * @Route("/mission")
 */
class MissionController extends Controller
{
    /**
     * @param Request $request
     * @param int $page
     * @return Response|null
     * @Route("", name="mission_mission_list_all_mission")
     */
    public function listAllMission(Request $request, $page =1){
        $limit = (int)abs($request->get('limit', 10));
        $skip = (int)abs($request->get('skip', 0));
        $user = $this->get('security.token_storage')
            ->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $missions = $em->getRepository('MissionBundle:Mission')
            ->findBy([], ["serviceDate" => "DESC"], $limit, $skip);
        return $this->render('MissionBundle:Mission:list_all.html.twig', [
            "missions" => $missions
        ]);
    }

}
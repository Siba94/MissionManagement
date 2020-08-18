<?php


namespace MissionBundle\Controller;


use MissionBundle\Entity\Mission;
use MissionBundle\Form\MissionPageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Security("has_role('ROLE_USER')")
 * @Route("/mission/page")
 */
class MissionController extends Controller
{
    /**
     * @param Request $request
     * @param int $page
     * @return Response|null
     * @Route("/list-all", methods={"GET"}, name="mission_mission_page_list")
     */
    public function pageListAction(Request $request, $page =1){
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

    /**
     * @param Request $request
     * @param $missionId
     * @return Response|null
     * @Route("/read", methods={"GET"}, name="mission_mission_page_read")
     */
    public function pageReadAction(Request $request, $missionId){
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('MissionBundle:Mission')
            ->find($missionId);
        if(!empty($mission)){
            if(!($this->get('security.token_storage')->getToken()->getUser()->isSuperAdmin()
                || ($mission->getClient() == $this->get('security.token_storage')->getToken()->getUser()))
            ){
                throw new AccessDeniedException("Sorry, you are not allowed to access this page");
            }
        }else{
            throw new NotFoundHttpException("Sorry, the requested mission details not found.");
        }
        return $this->render('MissionBundle:Mission:read.html.twig', [
            'mission' => $mission
        ]);
    }

    /**
     * @param Request $request
     * @return Response|null
     * @Route("/create", methods={"POST"}, name="mission_mission_page_create")
     */
    public function pageCreateAction(Request $request){
        $mission = new Mission();
        $form = $this->createForm(MissionPageType::class, $mission);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            $productName = $form['productName'];
            $vendorName = $form['vendorName'];
            $vendorEmail = $form['vendorEmail'];
            $quantity = $form['quantity'];
            $serviceDate = $request->get('serviceDate');
            if(!empty($productName) && !empty($vendorName) && !empty($vendorEmail) && !empty($quantity) && !empty($serviceDate)){
                if (filter_var($vendorEmail, FILTER_VALIDATE_EMAIL)) {
                    $this->get('mission.mission_management')
                        ->create($productName, $vendorName, $vendorEmail, $quantity, $serviceDate);
                }else{
                    $this->addFlash("danger", "Sorry, the provided email is not in a valid format.");
                }
                $this->addFlash('success', "Successfully Created");
            }else{
                $this->addFlash('danger', 'Kindly fill up all the fields.');
            }
            $this->redirectToRoute("mission_mission_page_list");
        }
        return $this->render('MissionBundle:Mission:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $missionId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response|null
     * @Route("/update/{missionId}", methods={"GET"}, name="mission_mission_page_update")
     */
    public function pageUpdateAction(Request $request, $missionId){
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('MissionBundle:Mission')
            ->find($missionId);
        if(empty($mission)){
            $this->addFlash("danger", "Sorry, the requested mission details not found.");
            return $this->redirectToRoute("mission_mission_page_read");
        }
        $form = $this->createForm(MissionPageType::class, $mission);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $productName = $form['productName'];
            $vendorName = $form['vendorName'];
            $vendorEmail = $form['vendorEmail'];
            $quantity = $form['quantity'];
            $serviceDate = $request->get('serviceDate');
            if(!empty($productName) && !empty($vendorName) && !empty($vendorEmail) && !empty($quantity)){
                if (filter_var($vendorEmail, FILTER_VALIDATE_EMAIL)) {
                    $this->get('mission.mission_management')
                        ->update($mission, $productName, $vendorName, $vendorEmail, $quantity, $serviceDate);
                }else{
                    $this->addFlash("danger", "Sorry, the provided email is not in a valid format.");
                }
                $this->addFlash('success', "Successfully Updated");
            }else{
                $this->addFlash('danger', 'Kindly fill up all the fields.');
            }
            $this->redirectToRoute("mission_mission_page_list");
        }
        return $this->render('MissionBundle:Mission:update.html.twig', [
            'form' => $form->createView(),
            'mission'=> $mission
        ]);
    }
}
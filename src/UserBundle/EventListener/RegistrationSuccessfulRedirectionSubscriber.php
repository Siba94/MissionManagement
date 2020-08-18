<?php


namespace UserBundle\EventListener;


use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RegistrationSuccessfulRedirectionSubscriber implements EventSubscriberInterface
{
    private $router;
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onRegistrationSuccess(FormEvent $event){
        $url = $this->router->generate('mission_mission_page_list');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
    public static function getSubscribedEvents(){
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        ];
    }
}
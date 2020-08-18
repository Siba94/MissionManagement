<?php


namespace MissionBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use MissionBundle\Entity\Mission;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MissionManagement
{
    private $container;
    private $em;
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }

    public function create($productName, $vendorName, $vendorEmail, $quantity, $serviceDate){
        $mission = new Mission();
        $mission
            ->setProductName($productName)
            ->setVendorName($vendorName)
            ->setVendorEmail($vendorEmail)
            ->setQuantity($quantity)
            ->setServiceDate(new \DateTime($serviceDate))
            ->setClient($this->container->get('security.token_storage')->getToken()->getUser())
            ;
        $this->em->persist($mission);
        $this->em->flush();
        return $mission;
    }

    public function update(Mission $mission, $productName, $vendorName, $vendorEmail, $quantity, $serviceDate){
        $mission->setProductName($productName)
            ->setVendorName($vendorName)
            ->setVendorEmail($vendorEmail)
            ->setQuantity($quantity)
            ->setServiceDate(new \DateTime($serviceDate))
            ->setClient($this->container->get('security.token_storage')->getToken()->getUser())
        ;
        $this->em->persist($mission);
        $this->em->flush();
        return $mission;
    }

}
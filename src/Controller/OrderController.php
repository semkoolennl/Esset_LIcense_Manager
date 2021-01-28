<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Company;

class OrderController extends AbstractController
{   
    private $session;
    private EntityManagerInterface $em;
    
    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {   
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function showCheckout(): Response
    {   
        $orderid = $this->session->get('orderid');
        $order = $this->getOrder();
        $response = new Response(201);
        $response = new Response('<html><body>'.$order->getId().'</body></html>');
        return $response;
    }

    /**
     * @Route("/addOrderLine", name="addOrderLine")
     */
    public function addOrderLine(Request $request): Response
    {   
        $orderid = $this->session->get('orderid');
        $order = $this->getOrder();
        $response = new Response($request);
        // $response = new Response('<html><body>'.$order->getId().'</body></html>');
        return $response;
    }

    public function getOrder() 
    {
        $orderid = $this->session->get('orderid');
        $company = $this->em->getRepository(Company::class)->find('testcompanyid123');
        $order;
        if (isset($orderid)){
            try {
                $order = $this->em->getRepository(Order::class)->find($orderid);
            } catch (ORMException $e){
                $order = $this->createOrder($company);
            }
        } else{
            $order = $this->em->getRepository(Order::class)->findOneBy(['company' => $company, 'status' => 'creating']);
            if (!isset($order)){
                $order = $this->createOrder($company);
            }
            $this->session->set('orderid', $order->getId());            
        }
        return $order;
    }

    public function createOrder($company) : Object
    {
        $order = New Order();
        $order->setCompany($company);
        $order->setStatus('creating');
        $this->em->persist($order);
        $this->em->flush();
        return $order;
    }
}

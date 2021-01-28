<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\EsetAPI\EsetClient;
use App\Service\Handlers\EmailHandler;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Payment;
use App\Entity\Company;
use Mollie\Api\MollieApiClient;
use Psr\Log\LoggerInterface;

class VisitorController extends AbstractController
{   
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @Route("", name="home")
     */
    public function index()
    {   
        return $this->render('visitor/index.html.twig', [
            'controller_name' => 'VisitorController',
        ]);
    }

    /**
     * @Route("eset", name="eset")
     */
    public function eset(EsetClient $esetAPI, EmailHandler $emailHandler)
    {   
        $license = $esetAPI->license->get('test_license_id', 'test_license_key');
        $esetAPI->setCredentials('test2_GUID', 'test2_KEY');
        $license2 = $esetAPI->license->get('test2_license_id', 'test2_license_key');
        $response = $emailHandler->validateEmail('sem.koolen@gmail.com');
        return $this->render('visitor/eset.html.twig', [
            'dumps' => [$license, $license2, $response[0], $response[1]]
        ]);
    }

    /**
     * @Route("orders/{id}", name="showOrder")
     */
    public function showOrder(Request $request, $id)
    {   
        $order = $this->em->getRepository(Order::class)->find($id);
        $payment = $order->getPayment();
        return $this->render('visitor/order.html.twig', [
            'order' => $order,
            'payment' => $payment,
        ]);
    }

    /**
     * @Route("payment/confirm/{orderid}", name="webhook")
     */
    public function webhook($orderid, Request $request, MollieApiClient $mollie, LoggerInterface $logger)
    {   
        $order = $this->em->getRepository(Order::class)->find($orderid);
        $payment = $order->getPayment();
        $paymentid = $request->get('id');
        $mollie->setApiKey("test_494abCt9vNNK5qbhq7s4P589euvyrp");
        $molliepayment = $mollie->payments->get($paymentid);
        $payment->setStatus($molliepayment->status);
        $logger->info(json_encode($molliepayment));
        if ($molliepayment) {
            $payment->setResponse(json_decode(json_encode($molliepayment), true));
        }
        $this->em->persist($payment);
        $this->em->flush();
        return new Response(200);
    }

    /**
     * @Route("products/{id}/order", name="pay")
     */
    public function makeOrder($id, Request $request, MollieApiClient $mollie)
    {   
        $orderLine = New OrderLine();
        $orderLine->setStatus('awaiting_payment');
        $orderLine->setRequest(['key' => 'value']);
        $this->em->persist($orderLine);
        $this->em->flush();
        $order = New Order();
        $company = $this->em->getRepository(Company::class)->find('testcompanyid123');
        $order->setCompany($company);
        $this->em->persist($order);
        $this->em->flush();
        $orderLine->setOrderId($order);
        $this->em->persist($orderLine);
        $this->em->flush();

        
        
        $redirect = $this->generateUrl('showOrder', [
            'id' => $order->getId(),
        ]);
        $ngrok = $_ENV['NGROK_URL'];
        $redirectUrl = $ngrok . "orders/" . $order->getId();
        $webhookUrl = $ngrok . "payment/confirm/" . $order->getId();
        $requestQuery = [
            "amount" => [
                "currency" => "EUR",
                "value" => "10.00"
            ],
            "description" => "My first API payment",
            "redirectUrl" => $redirectUrl,
            "webhookUrl"  => $webhookUrl
        ];
        
        $payment = new Payment();
        $payment->setCompany($company);
        $payment->setOrder($order);
        $payment->setAmount(10.00);
        $payment->setStatus('pending');
        $this->em->persist($payment);
        $this->em->flush();
        $mollie->setApiKey("test_494abCt9vNNK5qbhq7s4P589euvyrp");
        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => "10.00"
            ],
            "description" => "My first API payment",
            "redirectUrl" => $redirectUrl,
            "webhookUrl"  => $webhookUrl
        ]);
        $attributes = $request->attributes->all();
        // $links = ['payment' => '#'];
        $links = [
            'payment' => $payment->getCheckoutUrl()
        ];

        $product = $this->em->getRepository(Product::class)->find($id);
        $data = [$orderLine->getId(), $payment];
        return $this->render('visitor/pay.html.twig', [
            'data' => $data,
            'product' => $product,
            'links' => $links,
        ]);
    }

    /**
     * @Route("products", name="products")
     */
    public function showProducts()
    {   
        $products = $this->em->getRepository(Product::class)->findBy(['showAsProduct' => true]);
        return $this->render('visitor/eset.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("products/{id}", name="product")
     */
    public function showProduct($id)
    {       
        $product = $this->em->getRepository(Product::class)->find($id);
        return $this->render('visitor/showProduct.html.twig', [
            'product' => $product,
        ]);
    }


}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class EmailController extends AbstractController
{
    /**
     * @Route("/email", name="email")
     */
    public function index(MailerInterface $mailer): Response
    {   
        $email = new Email(); 
        $email->from(new Address('sem.koolen@gmail.com', 'Sem Koolen'));
        $email->subject("Sending with SendGrid is Fun");
        $email->to(new Address('329321cxcjnusckjnchnwyunij289d892jcdasca@gmail.com', 'Sem Koolen NL'));
        $email->text("and easy to do anywhere, even with PHP");
        $email->html("<strong>and easy to do anywhere, even with PHP</strong>");
        $response = '';
        try {
            $response = $mailer->send($email);
            
        } catch (Exception $e) {
            $response = $e->getMessage();
        }
        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
            'response' => $response,
        ]);
        
    }
}

<?php

namespace App\Controller;

use App\Dto\ContactDto;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new ContactDto();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $template = 'emails/contact.html.twig';
            $emailTo = 'contact@demo.fr';

            switch ($data->service) {
                case 'accounting':
                    $template = 'emails/contact_accounting.html.twig';
                    $emailTo = 'accounting@demo.fr';
                    break;
                case 'support':
                    $template = 'emails/contact_support.html.twig';
                    $emailTo = 'support@demo.fr';
                    break;
                case 'marketing':
                    $template = 'emails/contact_marketing.html.twig';
                    $emailTo = 'marketing@demo.fr';
                    break;
            }

            try {
                $email = new TemplatedEmail();
                $email
                ->from($data->email)
                ->to($emailTo)
                ->subject('Demande de contact')
                ->htmlTemplate($template)
                ->context([
                    'data' => $data,
                ]);

                $mailer->send($email);
                $this->addFlash('success', 'Votre message est envoyé');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Un problème est survenu');
            }


            return $this->redirectToRoute('contact.index');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}

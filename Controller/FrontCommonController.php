<?php

namespace GaylordP\FrontCommonBundle\Controller;

use GaylordP\FrontCommonBundle\Form\ContactType;
use GaylordP\FrontCommonBundle\Form\Model\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class FrontCommonController extends AbstractController
{
    /**
     * @Route(
     *     {
     *         "fr": "/contact",
     *     },
     *     name="contact",
     *     methods=
     *     {
     *         "GET",
     *         "POST",
     *     }
     * )
     */
    public function contact(
        Request $request,
        MailerInterface $mailer
    ): Response {
        $parameters = $this->getParameter('front_common')['contact'];

        if (false === $parameters['enabled']) {
            throw $this->createNotFoundException();
        }

        $contact = new Contact();

        if (null !== $this->getUser()) {
            $contact->setUser($this->getUser());
            $contact->setName($this->getUser()->getUsername());
            $contact->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailTitle = 'Demande de contact | Bubble.lgbt';

            $email = (new TemplatedEmail())
                ->to($this->getParameter('front_common')['contact']['email'])
                ->subject($emailTitle)
                ->htmlTemplate('@FrontCommon/email/contact.html.twig')
                ->context([
                    'title' => $emailTitle,
                    'contact' => $contact,
                ])
            ;

            $mailer->send($email);

            $this->get('session')->getFlashBag()->add(
                'success',
                [
                    'contact.created_successfully',
                    [
                        '%email%' => $contact->getEmail(),
                    ],
                    'front_common'
                ]
            );

            return $this->redirectToRoute('contact');
        }

        return $this->render('@FrontCommon/contact.html.twig', [
            'form'  => $form->createView(),
            'whatsapp' => $this->getParameter('front_common')['contact']['whatsapp'],
        ]);
    }

    /**
     * @Route(
     *     {
     *         "fr": "/mentions-legales",
     *     },
     *     name="legal",
     *     methods="GET"
     * )
     */
    public function legal(): Response
    {
        $parameters = $this->getParameter('front_common')['legal'];

        if (false === $parameters['enabled']) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FrontCommon/legal.html.twig', [
            'parameters'  => $parameters,
        ]);
    }

    /**
     * @Route(
     *     {
     *         "fr": "/conditions-generales-d-utilisation",
     *     },
     *     name="term",
     *     methods="GET"
     * )
     */
    public function term(): Response
    {
        $parameters = $this->getParameter('front_common')['term'];

        if (false === $parameters['enabled']) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FrontCommon/term.html.twig');
    }
}

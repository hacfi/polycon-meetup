<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Service\CounterService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        $people = $this->getDoctrine()
            ->getRepository(Person::class)
            ->findAll();

        $person = new Person();

        $form = $this->getPersonForm($person);

        $counter = $this->get(CounterService::class)->getCounter();

        return $this->render(
            'index.html.twig',
            [
                'people' => $people,
                'form' => $form->createView(),
                'counter' => $counter,
            ]
        );
    }

    public function add(Request $request)
    {
        $person = new Person();

        $form = $this->getPersonForm($person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
        }

        return $this->redirectToRoute('index');
    }

    private function getPersonForm($person)
    {
        return $this->createForm(PersonType::class, $person);
    }
}

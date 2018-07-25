<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Feed;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FeedController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    public function insertTestAction()
    {
        $feed = new Feed();
        $feed->setTitle('TITLE - Primera noticia manual');
        $feed->setBody('BODY - Primera noticia manual');
        $feed->setImage('link a la imagen');
        $feed->setSource('SOURCE - Primera noticia manual');
        $feed->setPublisher('PUBLISHER - Primera noticia manual');
        $feed->setCreated(new \DateTime());
        $feed->setUpdated(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($feed);
        $flushed = $em->flush();

        if (!is_null($flushed)) {
            echo "error al crear noticia";
        } else {
            echo "noticia creada";
        }

        die();
    }
}

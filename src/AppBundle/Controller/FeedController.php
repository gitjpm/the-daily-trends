<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Feed;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use AppBundle\Form\FeedType;

class FeedController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $feedRepository = $em->getRepository("AppBundle:Feed");
        $feeds = $feedRepository->findAll();
        return $this->render('@App/Feed/index.html.twig', array(
            'title' => 'Listado de feeds',
            'feeds' => $feeds
        ));
    }

    public function editAction(Request $request, $id){
        $feed = $this->getDoctrine()->getRepository('AppBundle:Feed')->find($id);
        $form = $this->createForm(FeedType::class, $feed);
        return $this->render('@App/Feed/form.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Formulario del Feed'
        ));
    }

    public function createAction(Request $request){
        $feed = new Feed();
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $feed = new Feed();
            $feed->setTitle($form->get("title")->getData());
            $feed->setBody($form->get("body")->getData());
            $feed->setImage($form->get("image")->getData());
            $feed->setSource($form->get("source")->getData());
            $feed->setPublisher($form->get("publisher")->getData());
            $feed->setCreated(new \DateTime());
            $feed->setUpdated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($feed);
            $flushed = $em->flush();
            if(is_null($flushed)){
                $state = "Se ha insertado la noticia con éxito";
            }else{
                $state = "Error al añadir la noticia";
            }
        }
        return $this->render('@App/Feed/form.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Creación de noticia',
            'state' => $state,
        ));
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

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Feed;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
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

    public function deleteAction(Request $request, $id){
        $feed = $this->getDoctrine()->getRepository('AppBundle:Feed')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($feed);
        $em->flush();

        return $this->redirectToRoute('feeds_list');
    }

    public function createAction(Request $request){
        $feed = new Feed();
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);
        $state = '';
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
                return $this->redirectToRoute('feeds_list');
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

    public function importAction(){

        $arrayRss = array(
            'EL PAIS' => 'http://ep00.epimg.net/rss/elpais/portada.xml',
            'EL MUNDO' =>  'http://estaticos.elmundo.es/elmundo/rss/portada.xml'
        );
        foreach($arrayRss as $publisher => $rss) {
            echo $publisher. " - ".$rss;
            echo "<br>";

            $html = file_get_contents($rss);
            $crawler = new Crawler($html);
            $item = $crawler->filter('rss > channel > item')->eq(0);
            $title = $item->filterXPath('//title')->text();

            switch($publisher){
                case 'EL PAIS':
                    $body = $item->filterXPath('//content:encoded')->text();
                    $image = $item->filterXPath('//enclosure')->attr('url');
                    break;
                case 'EL MUNDO':
                    $body = $item->filterXPath('//media:description')->text();
                    if(($item->filterXPath('//media:content')->count() == 1)){
                        $image = $item->filterXPath('//media:content')->attr('url');
                    }else{
                        $image = 'N / A';
                    }
                    break;
                default:
                    $body = '';
                    break;
            }

            $source = $item->filterXPath('//dc:creator')->text();
            $feed = new Feed();

            $feed->setTitle($title);
            $feed->setBody($body);
            $feed->setImage($image);
            $feed->setSource($source);
            $feed->setPublisher($publisher);
            $feed->setCreated(new \DateTime());
            $feed->setUpdated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($feed);
            $em->flush();
        }

        return $this->redirectToRoute('feeds_list');

    }
}

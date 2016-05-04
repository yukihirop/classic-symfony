<?php
namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
//use Nelmio\ApiDocBundle\Annotation\ApiDoc;
//use FOS\RestBundle\Controller\Annotations as Rest;
//使わない
//use FOS\RestBundle\View\View;

class ConcertController extends FOSRestController
{
    
//    /**
//     * @Rest\Get("/api/concerts.{_format}")
//     */
    public function getConcertsAction()
    {
        
        $em = $this->get('doctrine')->getManager();
        $repository = $em->getRepository('AppBundle:Concert');
        $concertList = $repository->findAll();
        
//        //公演情報エンティティの配列を元にViewオブジェクトを生成
//        $view = new View($concertList);
//        //Viewオブジェクトをもとにレスポンスオブジェクトを生成
//        return $this->handleView($view);

        return $concertList;
        
    }
    
}
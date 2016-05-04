<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MemberController extends Controller
{

    /**
     * @Route("/member")
     */
    public function indexAction()
    {
        //コレクションオブジェクトを取得(サービスコンテナから)
        $memberCollection = $this->get('app.member_collection');

        return $this->render('Member/index.html.twig',
            ['memberCollection' => $memberCollection]
        );
    }
}

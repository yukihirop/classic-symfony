<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
#use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BlogController extends Controller
{
	/**
	 * @Route("/blog/")
	 */
	public function latestListAction()
	{
		// $blogList = [
		// 	[
		// 		'targetDate' => '2015年3月15日',
		// 		'title' => '東京公演レポート',
		// 	],
		// 	[
		// 		'targetDate' => '2015年2月8日',
		// 		'title' => '最近の練習風景',
		// 	],
		// 	[
		// 		'targetDate' => '2015年1月3日',
		// 		'title' => '本年もよろしくお願い致します',
		// 	],
		// ];

		// エンティティマネージャーを取得
		$em = $this->getDoctrine()->getManager();
		// エンティティマネージャから、エンティティリポジトリを取得
		$blogArticleRepository = $em->getRepository('AppBundle:BlogArticle');
		// エンティティリポジトリのファインダメソッドを実行して、情報を取得
		$blogList = $blogArticleRepository->findBy([],['targetDate' => 'DESC']);

		return $this->render(
			'Blog/latestList.html.twig',
			['blogList' => $blogList]);
	}
}

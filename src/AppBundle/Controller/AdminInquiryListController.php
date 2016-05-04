<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
//第７章
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AppBundle\Entity\Inquiry;
use League\Csv\Writer;


/**
 * Class AdminInquiryController
 * @Route("/admin/inquiry")
 */
class AdminInquiryListController extends Controller
{

    /**
     * Class AdminInquiryListController
     * @Route("/search.{_format}",
     *     defaults={"_format": "html"},
     *     requirements={
     *          "_format": "html|csv",
     *     }
     * )
     */
    public function indexAction(Request $request, $_format)
    {

        //検索キーワード入力フォーム処理
        $form = $this->createSearchForm();
        $form->handleRequest($request);
        $keyword = null;
        if($form->isValid()){
            $keyword = $form->get('search')->getData();
        }

        //Doctrineを介してエンティティマネージャーを生
        $em = $this->getDoctrine()->getManager();
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry');

        //キーワードに一致するお問い合わせ一覧を取得
        //findAllByKeywordメソッドは自分で作る必要がある
        $inquiryList = $inquiryRepository->findAllByKeyword($keyword);

        //formatによって分岐
        if ($_format == 'csv') {
            //csv形式のレスポンスを生成
            $response = new Response($this->createCSV($inquiryList));
            $d = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'inquiry_list.csv'
            );

            $response->headers->set('Content-Disposition', $d);

            return $response;
        }

        return $this->render('Admin/Inquiry/index.html.twig',
            [
                'form' => $form->createView(),
                'inquiryList' => $inquiryList
            ]
        );
    }

    //検索キーワード入力フォーム作成
    private function createSearchForm()
    {

        return $this->createFormBuilder()
            ->add('search', 'search')
            ->add('submit', 'button', [
                'label' => '検索',
            ])
            ->getForm();

    }

    //CSVデータを用意するメソッド
    private function createCsv($inquiryList)
    {
        /**
         * @var Inquiry $inquiry
         */
        $writer = Writer::createFromString('','');
        $writer->setNewline("\r\n");

        //CSV処理用オズジェクトへ1行づつ登録
        foreach ($inquiryList as $inquiry) {
            /**
             * @var Inquiry $inquiry
             */
            $writer->insertOne([
                    $inquiry->getId(),
                    $inquiry->getName(),
                    $inquiry->getEmail()
            ]);
        }

        //CS k英式の文字列を取り出して、戻り値として返す
        return (string)$writer;
    }


}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Inquiry;


/**
 * @Route("/inquiry")
 */
class InquiryController extends Controller
{
	/**
 	 * @Route("/")
 	 * @Method("get")
 	 */
	public function indexAction()
	{

		// $form = $this->createFormBuilder()
		// -> add('name', 'text',['label' => 'お名前'])
		// -> add('email', 'text')
		// -> add('tel', 'text',[
		// 	'required' => false,
		// ])
		// -> add('type','choice',[
		// 	'choices' => [
		// 		'公演について',
		// 		'その他',
		// 		],
		// 		'expanded' => true,//ラジオボタンになる
		// ])
		// -> add('content','textarea')
		// -> add('submit','submit',[
		// 	'label' => '送信',
		// ])
		// ->getForm();//要素を追加し終えたらFormオブジェクトにして返す

		// return $this->render('Inquiry/index.html.twig',
		// 	['form' => $form->createView()]
		// );//FormオブジェクトのcreateView()メソッドを呼び出して、FormViewオブジェクトに変換してから
		// //渡す必要があることに注意してください。

		return $this->render('Inquiry/index.html.twig',
							['form' => $this->createInquiryForm()->createView()]);

	}


	/**
	 * @Route("/")
	 * @Method("post")
	 */
	public function indexPostAction(Request $request)
	{
		//フォーム定義を取得
		$form = $this->createInquiryForm();
		//送信された情報をフォームオブジェクトに取り込む
		$form->handleRequest($request);
		//フォーム入力値のバリデーション
		if ($form->isValid())
		{

			// $data = $form->getData();

			// // エンティティインスタンスの準備
			// $inquiry = new Inquiry();
			// $inquiry->setName($data['name']);
			// $inquiry->setEmail($data['email']);
			// $inquiry->setTel($data['tel']);
			// $inquiry->setType($data['type']);
			// $inquiry->setContent($data['content']);

			// フォームからエンティティが取得できる
			$inquiry = $form->getData();

			// エンティティマネージャーを主奥
			$em = $this->getDoctrine()->getManager();
			// エンティティをDoctrine管理下へ
			$em->persist($inquiry);
			// 変更分をデータベースへ適用
			$em->flush();


			#メールメッセージ作成
			$message = \Swift_Message::newInstance()
			->setSubject('webサイトからのお問い合わせ')
			->setFrom('webmaster@example.com')
			->setTo('admin@example.com')
			->setBody(
                    $this->renderView(
                        'mail/inquiry.txt.twig',
                        ['data' => $inquiry]
                    )
                );

			$this->get('mailer')->send($message);



			//何らかの処理を行った後、完了ページへリダイレクト
			return $this->redirect(
				$this->generateUrl('app_inquiry_complete'));
		}

		//入力エラーの場合は同じフォームを出力
		return $this->render('Inquiry/index.html.twig',
							['form' => $form->createView()]);

	}




    /**
     * @Route("/complete") 
     */
	public function completeAction()
	{
		return $this->render('Inquiry/complete.html.twig');

	}

	private function createInquiryForm()
	{
		// エンティティを渡す
		return $this->createFormBuilder(new Inquiry())
			->add('name', 'text')
			->add('email', 'text')
			->add('tel', 'text', ['required' => false,])
			->add(
				'type', 
				'choice', ['choices' => ['公演について', 'その他',],
							'expanded' => true,]
				)
			->add('content', 'textarea')
			->add('submit', 'submit', ['label' => '送信',])
			->getForm();
	}
}

<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class NotifyUnprocessedInquiryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cs:inquiry:notify-unprocessed')
            ->setDescription('未処理お問い合わせ一覧を通知')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $em = $container->get('doctrine')->getManager();
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry');
        
        //未処理のお問い合わせ一覧をリポジトリから取得
        $inquiryList = $inquiryRepository->findUnprocessed();
        
        if (count($inquiryList) > 0) {

            $output->writeln(count($inquiryList) . '件の未処理お問い合わせがあります');

            if ($this->confirmSend($input, $output)) {
                $this->sendMail($inquiryList, $output);
            }

            
        } else {
            $output->writeln("未処理なし");
        }
    }

    private function confirmSend($input, $output)
    {
        //Questionヘルパを取得
        $qHelper = $this->getHelper('question');
        //質問文を用意
        $question = new Question('通知メールを送信しますか？ [y/N]: ', null);
        //回答のバリデーションの準備
        $question->setValidator(function ($answer) {
            $answer = strtolower(substr($answer, 0, 1));
            if (!in_array($answer, ['y', 'n'])) {
                throw new \RuntimeException(
                    'yまたはnを入力してください'
                );
            }

            return $answer;
        });

        //許容する試行回数の設定
        $question->setMaxAttempts(3);

        return $qHelper->ask($input, $output, $question) == 'y';

    }

    private function sendMail($inquiryList, $output)
    {
        $container = $this->getContainer();
        $templating = $container->get('templating');

        //メールの文章を作成
        $message = \Swift_Message::newInstance()
                ->setSubject('[CS]未処理お問い合わせ通知')
                ->setFrom('webmaster@example.com')
                ->setTo('dev108186@gmail.com')
                ->setBody(
                    $templating->render(
                        'mail/admin_unprocessedInquiryList.txt.twig',
                        ['inquiryList' => $inquiryList]
                    )
                );

        $container->get('mailer')->send($message);

        $output->writeln(count($inquiryList) . "件数の未処理を通知");

    }
}
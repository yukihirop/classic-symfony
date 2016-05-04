<?php
namespace AppBundle\Service\Csv;

use AppBundle\Entity\Inquiry;
use League\Csv\Writer;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class InquiryCsvBuilder
 * @DI\Service("app.inquiry_csv_builder")
 */
class InquiryCsvBuilder
{
    private $encoding;//文字エンコーディング
    private $inquiryRepository;//お問い合わせ情報リポジトリ

    /**
     * InquiryCsvBuilder constructor.
     * @DI\InjectParams({
     *     "encoding"=@DI\Inject("%csv_encoding"),
     *     "inquiryRepository"=@DI\Inject("app.inquiry_repository")
     * })
     */
    public function __construct($encoding, $inquiryRepository)
    {
        $this->encoding = $encoding;
        $this->inquiryRepository = $inquiryRepository;

    }

    /**
     * CSV形式の文字列を作成
     */
    public function build($keyword)
    {
        $inquiryList = $this->inquiryRepository->findAllByKeyword($keyword);

        /**
         * @var Writer $writer
         */
        $writer = Writer::createFromString('','');
        $writer->setNewline("\r\n");

        foreach ($inquiryList as $inquiry)
        {
            /**
             * @var Inquiry $inquiry
             */
            $writer->insertOne([
                $inquiry->getId(),
                $inquiry->getName(),
                $inquiry->getEmail()
            ]);
        }

        return mb_convert_encoding((string)$writer, $this->encoding, 'UTF-8');
    }

}
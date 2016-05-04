<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\BlogArticleLoader;
#use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function ブログ記事一覧が表示されること()
    {
        //フィクスチャの読み込みを追加
        $this->loadFixtures([
            BlogArticleLoader::class
        ]);


        $client = static::createClient();
        $crawler = $client->request('GET', '/blog/');

        $this->assertThat(
            $crawler->filter('li.blog-article')->count(),
            $this->equalTo(20)
        );
    }

}

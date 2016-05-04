<?php
namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtures\Loader;
use Nelmio\Alice\Fixtures;

class BlogArticleLoader extends Loader
{

    protected function getFixtures()
    {
        return array(
            __DIR__ . '/../../Resources/fixtures/BlogArticle.yml',
        );
    }

}

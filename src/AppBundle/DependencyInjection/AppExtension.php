<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use AppBundle\Entity\MemberCollection;

class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        //YamlFileLoaderを準備する
        $loader = new YamlFileLoader($container,
            new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');//services.ymlを読み込む

        $config = $this->processConfiguration(new Configuration(), $configs);
        $this->buildMemberCollectionDefinition($container, $config['members']);
    }

    private function buildMemberCollectionDefinition(ContainerBuilder $container, $memberList)
    {
       //MemberCollectionオブジェクトのサービス定義を動的に追加
        $collectionDefinition = $container->register('app.member_collection', MemberCollection::class);
        
        foreach ($memberList as $name => $memberInfo) {
            //メンバーごとにaddMember()メソッドを呼ぶコードを定義に追加
            $collectionDefinition->addMethodCall('addMember', [
                $name, $memberInfo['part'], $memberInfo['joinedDate']
            ]);
        }
    }



    public function getAlias()
    {
        return 'app';//エクステンションのエイリアス
    }
}
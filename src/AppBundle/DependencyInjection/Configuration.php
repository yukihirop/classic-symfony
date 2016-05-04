<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app'); //エントリポイント

        $rootNode
            ->children() //ルートの子ノード定義の始まり
                ->arrayNode('members') //このキーの配下は配列の値
                    ->useAttributeAsKey('name') //キー名を配列の要素へマッピング
                    ->prototype('array') //配列宣言の始まり
                        ->children() //arrayの子ノードの始まり
                            //配列の要素の定義
                            ->scalarNode('part')->isRequired()->end()
                            ->scalarNode('joinedDate')->isRequired()->end()
                        ->end() //arrayの子ノード定義の終わり
                    ->end() //配列宣言の終わり
                ->end() //arrayNode定義の終わり
            ->end() //ルートの子ノード定義の終わり
        ;

        return $treeBuilder;
    }
}

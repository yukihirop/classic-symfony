<?php
//テスト対象と同じ名前空間
namespace AppBundle\Entity;

class MemberCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function 指定した属性のメンバーが追加されていること()
    {
        $memberCollection = new MemberCollection();

        //事前状態のアサーション
        $this->assertThat($memberCollection->count(), $this->equalTo(0),'初期状態のコレクションの要素が0');

        //対象メソッドの呼び出し
        $memberCollection->addMember('testname', 'testpart', '2015-01-15');

        //事後状態のアサーション
        $this->assertThat($memberCollection->count(), $this->equalTo(1), '追加後のコレクションの要素が1');

        $member = $memberCollection->last();
        $this->assertThat($member, $this->isInstanceOf(Member::class),'コレクションに追加された要素がMemberか');
        $this->assertThat($member->getName(), $this->equalTo('testname'),'nameプロパティが設定されているか');
        $this->assertThat($member->getPart(), $this->equalTo('testpart'),'partプロパティが設定されているか');
        $this->assertthat($member->getJoinedDate(), $this->equalTO('2015-01-15'), 'joinedDateプロパティが設定されているか');

    }

}
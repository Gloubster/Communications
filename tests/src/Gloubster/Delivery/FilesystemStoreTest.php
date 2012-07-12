<?php

namespace Gloubster\Delivery;

require_once __DIR__ . '/AbstractDelivery.php';

class FilesystemStoreTest extends AbstractDelivery
{
    /**
     * @var FilesystemStore
     */
    protected $object;

    /**
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;
    protected $dir;

    protected function setUp()
    {
        $this->dir = tempnam(sys_get_temp_dir(), 'test_fsdelivery');
        $this->filesystem = new \Symfony\Component\Filesystem\Filesystem();
        $this->filesystem->remove($this->dir);
        $this->filesystem->mkdir($this->dir);
    }

    protected function tearDown()
    {
        $this->filesystem->remove($this->dir);
        unset($this->object, $this->filesystem);
    }

    public function testBasic()
    {
        $signature = 'signature';
        $this->object = new FilesystemStore(__DIR__, $signature);
        $this->assertEquals('FilesystemStore', $this->object->getName());
        $this->assertEquals($signature, $this->object->getSignature());
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::deliver
     */
    public function testDeliver()
    {
        $result = $this->getResultMock();

        $result->expects($this->once())
            ->method('serialize')
            ->will(
                $this->returnValue(json_encode(array()))
        );

        $this->object = new FilesystemStore($this->dir, 'signature');
        $this->object->deliver('test', $result, 'binary datas');
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::deliver
     */
    public function testDeliverWithIntAsKey()
    {
        $result = $this->getResultMock();

        $result->expects($this->once())
            ->method('serialize')
            ->will(
                $this->returnValue(json_encode(array()))
        );

        $this->object = new FilesystemStore($this->dir, 'signature');
        $this->object->deliver(1234567, $result, 'binary datas');
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::deliver
     * @covers Gloubster\Delivery\FilesystemStore::getFile
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testDeliverFail()
    {
        $result = $this->getResultMock();

        $this->object = new FilesystemStore('/rhino/doro', 'signature');
        $this->object->deliver('test', $result, 'binary datas');
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::deliver
     * @covers Gloubster\Delivery\FilesystemStore::getFile
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testDeliverShouldFailIfDuplicateUuid()
    {
        $result = $this->getResultMock();

        $this->object = new FilesystemStore($this->dir, 'signature');
        $this->object->deliver('test', $result, 'binary datas');
        $this->object->deliver('test', $result, 'binary datas');
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::getFile
     * @covers Gloubster\Delivery\FilesystemStore::retrieve
     */
    public function testRetrieve()
    {
        $result = $this->getResultObject();

        $this->object = new FilesystemStore($this->dir, 'signature');
        $this->object->deliver('test', $result, 'binary datas');

        $this->assertEquals($result, $this->object->retrieve('test'));
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::getFile
     * @covers Gloubster\Delivery\FilesystemStore::retrieveData
     */
    public function testRetrieveData()
    {
        $this->object = new FilesystemStore($this->dir, 'signature');
        $this->object->deliver('test', $this->getResultObject(), 'binary datas');

        $this->assertEquals('binary datas', $this->object->retrieveData('test'));
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::retrieve
     * @expectedException Gloubster\Delivery\Exception\ItemDoesNotExistsException
     */
    public function testRetrieveShouldFail()
    {
        $this->object = new FilesystemStore($this->dir, 'signature');
        $this->object->retrieve('bidule');
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::retrieveData
     * @expectedException Gloubster\Delivery\Exception\ItemDoesNotExistsException
     */
    public function testRetrieveDataShouldFail()
    {
        $this->object = new FilesystemStore($this->dir, 'signature');
        $this->object->retrieveData('bidule');
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::retrieve
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testRetrieveCorruptedDatas()
    {
        $result = $this->getResultMock();

        $result->expects($this->once())
            ->method('serialize')
            ->will(
                $this->returnValue(serialize('gangBang !'))
        );

        $this->object = new FilesystemStore($this->dir, 'signature');
        $this->object->deliver('bidule', $result, 'binary datas');
        $this->object->retrieve('bidule');
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::build
     * @expectedException Gloubster\Exception\InvalidArgumentException
     */
    public function testBuildWrongPath()
    {
        FilesystemStore::build(array('path' => '/danny/devito'));
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::build
     * @expectedException Gloubster\Exception\InvalidArgumentException
     */
    public function testBuildNoPath()
    {
        FilesystemStore::build(array('pates' => $this->dir));
    }

    /**
     * @covers Gloubster\Delivery\FilesystemStore::build
     */
    public function testBuild()
    {
        $this->assertInstanceOf('\\Gloubster\\Delivery\\FilesystemStore', FilesystemStore::build(array('path' => $this->dir)));
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::build
     */
    public function testBuildsEquals()
    {
        $build1 = FilesystemStore::build(array('path'  => $this->dir));
        $build2 = FilesystemStore::build(array('path' => $this->dir . DIRECTORY_SEPARATOR));

        $this->assertEquals($build2->getSignature(), $build1->getSignature());
    }
}

<?php

namespace Gloubster;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getGoodConfigurations
     */
    public function testGoodConfiguration($configuration)
    {
        $conf = new Configuration($configuration);

        $this->assertTrue(isset($conf['server']));

        $conf['key'] = 'value';
        $this->assertEquals('value', $conf['key']);
        unset($conf['key']);
        $this->assertFalse(isset($conf['key']));
    }

    /**
     * @expectedException \Gloubster\Exception\RuntimeException
     */
    public function testWrongConfigurationSchema()
    {
        new Configuration(json_encode(array('hello'=>'world')), array('gloubs'));
    }

    /**
     * @dataProvider getWrongConfigurations
     * @expectedException \Gloubster\Exception\RuntimeException
     */
    public function testWrongConfiguration($configuration)
    {
        new Configuration($configuration);
    }

    public function getGoodConfigurations()
    {
        return $this->loadConfigurationsFolder(__DIR__ . '/../../resources/good-configurations');
    }

    public function getWrongConfigurations()
    {
        return $this->loadConfigurationsFolder(__DIR__ . '/../../resources/wrong-configurations');
    }

    protected function loadConfigurationsFolder($folder)
    {
        $confs = array();

        $finder = new \Symfony\Component\Finder\Finder();

        foreach ($finder->in($folder) as $configuration) {
            $confs[] = array(file_get_contents($configuration->getPathname()));
        }

        return $confs;
    }
}

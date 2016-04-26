<?php
namespace Hostnet\Component\AccessorGenerator;

use Composer\Composer;
use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Installer\InstallationManager;
use Composer\IO\BufferIO;
use Composer\IO\NullIO;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Repository\RepositoryManager;
use Composer\Repository\WritableArrayRepository;
use Composer\Script\ScriptEvents;
use Hostnet\Component\AccessorGenerator\Generator\CodeGeneratorInterface;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * @covers Hostnet\Component\AccessorGenerator\Plugin
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class PluginTest extends \PHPUnit_Framework_TestCase
{
    public function testActivate()
    {
        // Only a smoke test.
        $plugin = new Plugin();
        $plugin->activate($this->getMockComposer(), new NullIO());
    }

    public function testGetSubscribedEvents()
    {
        self::assertEquals(
            [ScriptEvents::PRE_AUTOLOAD_DUMP => ['onPreAutoloadDump', 20 ]],
            Plugin::getSubscribedEvents()
        );
    }

    public function testOnPreAutoloadDump()
    {
        // Efectivly change installation dir of root package.
        chdir(__DIR__ . '/fixtures');

        // Get fake generator
        $generator = $this->getMock(CodeGeneratorInterface::class);

        // Hit every fixture two times (ignore annotations).
        $generator->expects($this->exactly(4))->method('writeTraitForClass')->willReturn(true);

        $plugin = new Plugin($generator);
        $plugin->activate(self::getMockComposer(), new BufferIO('', StreamOutput::VERBOSITY_VERY_VERBOSE));
        $plugin->onPreAutoloadDump();

    }

    /**
     * Build up a composer environment with:
     * - a RepositoryManager with
     *   - a Repository with
     *     - a package that depends on Plugin::NAME
     *     - a package that not depends on Plugin::NAME
     * - a Mocked InstallationManager that will return
     *   __DIR__/fixtures when asked for an installation path
     * - a default event dispatcher
     * - a root package that depends on Plugin::NAME
     *
     * all with NullIO and a default empty config
     *
     * @return \Composer\Composer
     */
    private function getMockComposer()
    {
        $config = new Config();
        $io     = new NullIO();

        $root_package = new RootPackage(Plugin::NAME, 0, 0);
        $root_package->setRequires([Plugin::NAME => 0]);

        $silly_package = new Package('TestEntity', 0, 0);
        $package       = new Package('TestEntity', 0, 0);
        $package->setRequires([Plugin::NAME => 0]);

        $repository = new WritableArrayRepository();
        $repository->addPackage($silly_package);
        $repository->addPackage($package);

        $repository_manager = new RepositoryManager($io, $config);
        $repository_manager->setLocalRepository($repository);

        $installation_manager = $this->getMock(InstallationManager::class);
        $installation_manager->expects($this->any())->method('getInstallPath')->willReturn(__DIR__ . '/fixtures');

        $composer = new Composer();
        $composer->setPackage($root_package);
        $composer->setConfig($config);
        $composer->setRepositoryManager($repository_manager);
        $composer->setInstallationManager($installation_manager);
        $composer->setEventDispatcher(new EventDispatcher($composer, $io));

        return $composer;
    }
}

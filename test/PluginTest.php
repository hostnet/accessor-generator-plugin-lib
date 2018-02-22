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
        self::assertSame(
            [
                ScriptEvents::PRE_AUTOLOAD_DUMP  => ['onPreAutoloadDump', 20 ],
                ScriptEvents::POST_AUTOLOAD_DUMP => ['onPostAutoloadDump', 20 ]
            ],
            Plugin::getSubscribedEvents()
        );
    }

    public function testOnPreAutoloadDump()
    {
        // Effectively change installation dir of root package.
        chdir(__DIR__ . '/fixtures/root');

        // Get fake generator
        $generator = $this->createMock(CodeGeneratorInterface::class);

        // Hit every fixture one time.
        $generator->expects(self::exactly(2))->method('writeTraitForClass')->willReturn(true);
        $generator->expects(self::exactly(0))->method('writeEnumeratorAccessorsForClass')->willReturn(true);
        $generator->expects(self::once())->method('setEncryptionAliases');

        $plugin = new Plugin($generator);
        $plugin->activate($this->getMockComposer(), new BufferIO('', StreamOutput::VERBOSITY_VERY_VERBOSE));
        $plugin->onPreAutoloadDump();
    }

    public function testOnPostAutoloadDump()
    {
        // Effectively change installation dir of root package.
        chdir(__DIR__ . '/fixtures/root');

        // Get fake generator
        $generator = $this->createMock(CodeGeneratorInterface::class);

        // Hit every fixture one time.
        $generator->expects(self::exactly(0))->method('writeTraitForClass')->willReturn(true);
        $generator->expects(self::exactly(2))->method('writeEnumeratorAccessorsForClass')->willReturn([]);

        $plugin = new Plugin($generator);
        $plugin->activate($this->getMockComposer(), new BufferIO('', StreamOutput::VERBOSITY_VERY_VERBOSE));
        $plugin->onPostAutoloadDump();
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
     * @throws \PHPUnit_Framework_Exception
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

        $installation_manager = $this->createMock(InstallationManager::class);
        $installation_manager
            ->expects(self::any())
            ->method('getInstallPath')
            ->willReturn(__DIR__ . '/fixtures/package');

        $composer = new Composer();
        $composer->setPackage($root_package);
        $composer->setConfig($config);
        $composer->setRepositoryManager($repository_manager);
        $composer->setInstallationManager($installation_manager);
        $composer->setEventDispatcher(new EventDispatcher($composer, $io));

        $composer->getPackage()->setExtra([
            'accessor-generator' => [
                'database.table.column' => [
                    'public-key' => 'public',
                    'private-key' => 'secret'
                ]
            ]
        ]);

        return $composer;
    }
}

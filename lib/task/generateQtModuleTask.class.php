<?php
require_once('/usr/local/symfony/symfony-1.4.8/lib/task/generator/sfGeneratorBaseTask.class.php');

//class generateQt_moduleTask extends sfGenerateModuleTask
class generateQtModuleTask extends sfGenerateModuleTask
{
  protected function configure()
  {
    parent::configure();

    $this->namespace        = 'generate';
    $this->name             = 'qt_module';
    $this->briefDescription = 'Generates a new quutan project module';
    $this->detailedDescription = <<<EOF
The [generate:qt_module|INFO] task does things.
Call it with:

  [php symfony generate:qt_module|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $sfPath = '/usr/local/symfony/symfony-1.4.8/';

    $app    = $arguments['application'];
    $module = $arguments['module'];

    // Validate the module name
    if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $module))
    {
      throw new sfCommandException(sprintf('The module name "%s" is invalid.', $module));
    }

    $moduleDir = sfConfig::get('sf_app_module_dir').'/'.$module;

    if (is_dir($moduleDir))
    {
      throw new sfCommandException(sprintf('The module "%s" already exists in the "%s" application.', $moduleDir, $app));
    }

    $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);

    $constants = array(
      'PROJECT_NAME' => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
      'APP_NAME'     => 'Qt'.str_replace(' ', '', ucwords(str_replace('_', ' ', $app))),
      'MODULE_NAME'  => $module,
      'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Your name here',
    );

    // Œp³‚·‚é Action class ‚Ì‘¶ÝŠm”F
    $className = $constants['APP_NAME'].'Actions';
    if (!class_exists($className))
    {
      throw new sfCommandException(sprintf('The class "%s" not exists in the "%s" application.', $className, $app));
    }

//    if (is_readable(sfConfig::get('sf_data_dir').'/skeleton/module'))
//    {
//      $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/module';
//    }
//    else
//    {
//      $skeletonDir = dirname(__FILE__).'/skeleton/module';
//    }
    if (is_readable(dirname(__FILE__).'/skeleton/module'))
    {
      $skeletonDir = dirname(__FILE__).'/skeleton/module';
    }
    else
    {
      $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/module';
    }

    // create basic application structure
    $finder = sfFinder::type('any')->discard('.sf');
    $this->getFilesystem()->mirror($skeletonDir.'/module', $moduleDir, $finder);

    // create basic test
    $this->getFilesystem()->copy($sfPath.'lib/task/generator/skeleton/module'.'/test/actionsTest.php', sfConfig::get('sf_test_dir').'/functional/'.$app.'/'.$module.'ActionsTest.php');

    // customize test file
    $this->getFilesystem()->replaceTokens(sfConfig::get('sf_test_dir').'/functional/'.$app.DIRECTORY_SEPARATOR.$module.'ActionsTest.php', '##', '##', $constants);

    // customize php and yml files
    $finder = sfFinder::type('file')->name('*.php', '*.yml');
    $this->getFilesystem()->replaceTokens($finder->in($moduleDir), '##', '##', $constants);
  }
}

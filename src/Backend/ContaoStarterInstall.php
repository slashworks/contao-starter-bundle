<?php

namespace Slashworks\ContaoStarterBundle\Backend;

use Contao\BackendModule;
use Contao\Config;
use Contao\DataContainer;
use Contao\Dbafs;
use Contao\Email;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Folder;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\LayoutModel;
use Contao\Message;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Contao\ThemeModel;
use Contao\UserModel;
use Slashworks\ContaoSimpleSvgIconsBundle\SimpleSvgIcons;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ContaoStarterInstall extends BackendModule
{

    /** @var string */
    protected $strTemplate = 'be_contao_starter_install';

    /** @var bool */
    protected $installationDone = false;

    /** @var bool */
    protected $debug = false;

    /** @var Folder */
    protected $themeFolder;

    /** @var string */
    protected $themeName;

    /** @var string */
    protected $themeNameAlias;

    /** @var ThemeModel */
    protected $theme;

    /** @var LayoutModel */
    protected $layout;

    /** @var PageModel */
    protected $rootPage;

    /** @var PageModel */
    protected $homePage;

    /** @var array */
    protected $userData = array();

    /** @var array */
    protected $installedModules = array();

    /**
     * ContaoStarterInstall constructor.
     *
     * @param DataContainer|null $dc
     */
    public function __construct(DataContainer $dc = null)
    {
        $this->import('BackendUser', 'User');

        parent::__construct($dc);
    }

    /**
     * @return string
     */
    public function generate()
    {
        $this->formSubmit = 'contao_starter_install';

        return parent::generate();
    }

    /**
     * Compile output string.
     */
    protected function compile()
    {
        System::loadLanguageFile('default');

        $this->formSubmit();
        $this->buildTemplate();
    }

    /**
     *
     */
    protected function buildTemplate()
    {
        $this->Template->href = $this->getReferer(true);
        $this->Template->title = \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
        $this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];

        $this->Template->themeNameHelp = 'Geben Sie den Namen des Themes ein, z. B. "Beispielkunde" oder "Kunden AG".';
        $this->Template->formSubmit = $this->formSubmit;

        $this->Template->message = Message::generateUnwrapped(__CLASS__);
        $this->Template->installedModules = Message::generate('installed_modules');
        $this->Template->userData = Message::generate('user_data');

        $this->Template->showForm = !Config::has('contaoStarterInstallation');
        $this->Template->showDone = Config::has('contaoStarterInstallation');
    }

    /**
     * Handle form submission.
     */
    protected function formSubmit()
    {
        if (Input::post('FORM_SUBMIT') !== $this->formSubmit) {
            return;
        }

        if (!Input::post('theme_name')) {
            Message::addError('Bitte geben Sie einen Theme-Namen ein.', __CLASS__);
            return;
        }

        $this->themeName = Input::post('theme_name');
        $this->themeNameAlias = StringUtil::generateAlias($this->themeName);

        if ($this->debug) {
            $this->cleanUp();
        }

        $this->createFiles();
        $this->createTheme();
        $this->createLayout();
        $this->createPages();
        $this->createUsers();
        $this->modifyLocalconfig();

        $this->createMessages();

        if (!$this->debug) {
            $this->done();
        }

        $this->reload();
    }

    /**
     * Create messages for successful installation and installed modules.
     *
     * @throws \Exception
     */
    protected function createMessages()
    {
        Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['contaoStarterBundle']['installed'], __CLASS__);

        $installedModulesMessage = new FrontendTemplate('partial_installed_modules');
        $installedModulesMessage->modules = $this->installedModules;
        Message::add($installedModulesMessage->parse(), 'TL_RAW', 'installed_modules');

        $userDataMessage = new FrontendTemplate('partial_user_data');
        $userDataMessage->users = $this->userData;
        Message::add($userDataMessage->parse(), 'TL_RAW', 'user_data');
    }

    /**
     * Remove all data from tables the installer is using.
     * This will permanently delete already existing data in the database tables.
     */
    protected function cleanUp()
    {
        // TODO: Remove cleanup in production code.
        $this->Database->prepare('TRUNCATE table tl_article')->execute();
        $this->Database->prepare('TRUNCATE table tl_page')->execute();
        $this->Database->prepare('TRUNCATE table tl_theme')->execute();
        $this->Database->prepare('TRUNCATE table tl_layout')->execute();

        $smelz = UserModel::findByUsername('smelz');
        if ($smelz !== null) {
            $smelz->delete();
        }

        $sschwarz = UserModel::findByUsername('sschwarz');
        if ($sschwarz !== null) {
            $sschwarz->delete();
        }
    }

    /**
     * Copy all theme-related files (css, img) to the files-folder of the contao installation.
     *
     * @throws \Exception
     */
    protected function createFiles()
    {
        // Create 'themes' folder
        $themeRootFolder = new Folder('files/themes');
        $themeRootFolder->unprotect();

        // Create project theme folder
        $this->themeFolder = new Folder($themeRootFolder->path . '/' . $this->themeNameAlias);

        $this->generateCss();
        $this->generateImg();
        $this->generateTemplate();

        $this->syncDatabase();

        $this->installedModules[] = array
        (
            'title' => 'Theme-Files',
            'description' => 'CSS-Dateien, Icon-Sprite, Template-Ordner',
            'link' => 'https://www.google.com',
        );
    }

    /**
     * Copy the css directory shipped with the bundle to the file-folder of the contao installation
     */
    protected function generateCss()
    {
        $filesystem = new Filesystem();
        $filesystem->mirror(__DIR__ . '/../../data/theme/css', TL_ROOT . '/' . $this->themeFolder->path . '/css');
    }

    /**
     * Copy the img directory shipped with the bundle to the file-folder of the contao instalation.
     */
    protected function generateImg()
    {
        $filesystem = new Filesystem();
        $filesystem->mirror(__DIR__ . '/../../data/theme/img', TL_ROOT . '/' . $this->themeFolder->path . '/img');
    }

    /**
     * Generate a template folder for the theme.
     *
     * @throws \Exception
     */
    protected function generateTemplate()
    {
        $templateFolder = new Folder('templates/' . $this->themeNameAlias);
    }

    /**
     * Sync all files and folders inside the newly created theme folder with database.
     *
     * @throws \Exception
     */
    protected function syncDatabase()
    {
        $finder = new Finder();
        /** @var SplFileInfo $file */
        foreach ($finder->in(TL_ROOT . '/' . $this->themeFolder->path) as $file) {
            if (!is_file($file)) {
                continue;
            }

            $filePath = $this->themeFolder->path . '/' . $file->getRelativePathname();
            Dbafs::addResource($filePath);
        }
    }

    /**
     * Create a new theme.
     */
    protected function createTheme()
    {
        $themeFolderModel = FilesModel::findByPath($this->themeFolder->path);

        $this->theme = new ThemeModel();
        $this->theme->tstamp = time();
        $this->theme->name = $this->themeName;
        $this->theme->author = 'slashworks';
        $this->theme->folders = array($themeFolderModel->uuid);
        $this->theme->templates = 'templates/' . $this->themeNameAlias;

        if (class_exists(SimpleSvgIcons::class)) {
            $iconSpriteModel = FilesModel::findByPath($this->themeFolder->path . '/img/icon-sprite.svg');

            if ($iconSpriteModel !== null) {
                $this->theme->iconFiles = array($iconSpriteModel->uuid);
            }
        }

        $this->theme->save();

        $this->installedModules[] = array
        (
            'title' => 'Theme',
            'description' => 'Ein Theme "' . $this->themeName . '" wurde mit den Grundeinstellungen angelegt.',
            'link' => 'https://www.google.com',
        );
    }

    /**
     * Create a new layout.
     */
    protected function createLayout()
    {
        $this->layout = new LayoutModel();
        $this->layout->pid = $this->theme->id;
        $this->layout->tstamp = time();
        $this->layout->name = 'Standard';
        $this->layout->rows = '3rw';
        $this->layout->cols = '1cl';

        $cssFile = FilesModel::findByPath($this->themeFolder->path . '/css/app.scss');
        if ($cssFile !== null) {
            $this->layout->external = array($cssFile->uuid);
        }

        $this->layout->picturefill = true;
        $this->layout->addJQuery = true;
        $this->layout->jSource = 'j_local';
        $this->layout->viewport = 'width=device-width,initial-scale=1.0';
        $this->layout->template = 'fe_page';

        $modules = array();
        $modules[] = array
        (
            'mod' => '0',
            'col' => 'main',
            'enable' => true
        );
        $this->layout->modules = $modules;

        $this->layout->save();

        $this->installedModules[] = array
        (
            'title' => 'Layout',
            'description' => 'Ein Layout "Standard" wurde mit den Grundeinstellungen angelegt.',
            'link' => 'https://www.google.com',
        );
    }

    /**
     * Create pages.
     */
    protected function createPages()
    {
        $this->createRootPage();
        $this->createHomePage();

        $this->installedModules[] = array
        (
            'title' => 'Seitenstruktur',
            'description' => 'Es wurden eine Wurzelseite und eine Startseite angelegt.',
            'link' => 'https://www.google.com',
        );
    }

    /**
     * Create the root page.
     */
    protected function createRootPage()
    {
        $this->rootPage = new PageModel();
        $this->rootPage->pid = 0;
        $this->rootPage->sorting = 128;
        $this->rootPage->tstamp = time();
        $this->rootPage->title = $this->themeName;
        $this->rootPage->alias = $this->themeNameAlias;
        $this->rootPage->type = 'root';
        $this->rootPage->language = 'de';
        $this->rootPage->fallback = true;
        $this->rootPage->adminEmail = 'hallo@slash-works.de';
        $this->rootPage->includeLayout = true;
        $this->rootPage->layout = $this->layout->id;
        $this->rootPage->published = true;

        $this->rootPage->save();
    }

    /**
     * Create the home page.
     */
    protected function createHomePage()
    {
        $this->homePage = new PageModel();
        $this->homePage->pid = $this->rootPage->id;
        $this->homePage->sorting = 128;
        $this->homePage->tstamp = time();
        $this->homePage->title = 'Start';
        $this->homePage->alias = 'index';
        $this->homePage->type = 'regular';
        $this->homePage->robots = 'index,follow';
        $this->homePage->published = true;

        $this->homePage->save();


        // Create homepage article
        $arrSet['pid'] = $this->homePage->id;
        $arrSet['sorting'] = 128;
        $arrSet['tstamp'] = time();
        $arrSet['author'] = $this->User->id;
        $arrSet['inColumn'] = 'main';
        $arrSet['title'] = $this->homePage->title;
        $arrSet['alias'] = str_replace('/', '-', $this->homePage->alias); // see #5168
        $arrSet['published'] = true;

        $this->Database->prepare("INSERT INTO tl_article %s")->set($arrSet)->execute();
    }

    /**
     * Create backend users.
     */
    protected function createUsers()
    {
        $this->createUser('smelz', 'Stefan Melz', 'stefan@slash-works.de');
        $this->createUser('sschwarz', 'Simon Schwarz', 'simon@slash-works.de');
        if (!$this->debug) {
            $this->createUser('sgruna', 'Stefan Gruna', 'stefang@slash-works.de');
        }

        if (!$this->debug) {
            $this->sendEmailWithUserData();
        }

        $this->installedModules[] = array
        (
            'title' => 'Backend-User',
            'description' => 'Es wurden Backenduser angelegt. Die Zugangsdaten wurden per E-Mail an "hallo@slash-works.de" gesendet.',
            'link' => 'https://www.google.com',
        );
    }

    /**
     * Create a single backend users.
     *
     * @param $username
     * @param $name
     * @param $email
     *
     * @throws \Exception
     */
    protected function createUser($username, $name, $email)
    {
        $password = md5(random_bytes(10));

        $user = new UserModel();
        $user->tstamp = time();
        $user->username = $username;
        $user->name = $name;
        $user->email = $email;
        $user->language = 'de';
        $user->backendTheme = 'flexible';
        $user->showHelp = true;
        $user->thumbnails = true;
        $user->useRTE = true;
        $user->useCE = true;
        $user->admin = true;
        $user->dateAdded = time();
        $user->password = password_hash($password, PASSWORD_DEFAULT);

        $user->save();

        $this->userData[] = array
        (
            'username' => $username,
            'password' => $password
        );
    }

    /**
     * Send an email containing user data of the generated backend users.
     */
    protected function sendEmailWithUserData()
    {
        $mail = new Email();
        $mail->subject = 'Benutzerdaten für ' . $this->themeNameAlias;
        $mail->from = 'hallo@slash-works.de';

        $bodyTemplate = new FrontendTemplate('partial_user_data_mail');
        $bodyTemplate->website = Environment::get('host');
        $bodyTemplate->users = $this->userData;

        $template = new FrontendTemplate('mail_default');
        $template->title = 'Benutzerdaten für ' . $this->themeNameAlias;
        $template->css = '';
        $template->body = $bodyTemplate->parse();

        $mail->html = $template->parse();

        $mail->sendTo('hallo@slash-works.de');
    }

    /**
     * Setup the localconfig file with some default settings.
     */
    protected function modifyLocalconfig()
    {
        Config::persist('adminEmail', 'hallo@slash-works.de');
        Config::persist('websiteTitle', $this->themeName);
        Config::persist('dateFormat', 'd.m.Y');
        Config::persist('datimFormat', 'd.m.Y H:i');
        Config::persist('gdMaxImgWidth', 5000);
        Config::persist('gdMaxImgHeight', 5000);
        Config::persist('maxFileSize', 20480000);
        Config::persist('imageWidth', 5000);
        Config::persist('imageHeight', 5000);

        $this->installedModules[] = array
        (
            'title' => 'System-Einstellungen',
            'description' => 'Es wurden Grundeinstellungen für das System vorgenommen.',
            'link' => 'https://www.google.com',
        );
    }

    /**
     * Set a flag that the installation has been run.
     */
    protected function done()
    {
        Config::persist('contaoStarterInstallation', time());
    }

}

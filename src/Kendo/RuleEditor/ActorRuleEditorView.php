<?php
namespace Kendo\RuleEditor;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_LoaderInterface;
use Twig_Loader_Array;
use Twig_Loader_Filesystem;

class ActorRuleEditorView
{

    /**
     * @var Kendo\RuleEditor\ActorRuleEditor
     */
    protected $editor;


    /**
     * @var Twig_Environment
     */
    protected $environment;

    public function __construct(ActorRuleEditor $editor, Twig_Environment $environment = null, Twig_LoaderInterface $loader = null)
    {
        $this->editor = $editor;

        if ($environment) {
            $this->environment = $environment;

            // Add Kendo template directory to existing loader
            $loader = $environment->getLoader();
            if ($loader instanceof Twig_Loader_Filesystem) {
                if (!in_array('Kendo',$loader->getNamespaces())) {
                    $loader->addPath($this->getTemplateDirectory(), 'Kendo');
                }
            }

        } else {
            if (!$loader) {
                $loader = $this->createDefaultTemplateLoader();
            }
            $this->environment = new Twig_Environment($loader, [ 'debug' => true, ]);
            $this->environment->addExtension(new Twig_Extension_Debug());
        }
    }

    public function getTemplateDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Templates';
    }

    public function createDefaultTemplateLoader()
    {
        $loader = new Twig_Loader_Filesystem;
        $loader->addPath($this->getTemplateDirectory(), 'Kendo');
        // $templateContent = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'rule_editor.html.twig');
        /*
        $loader = new Twig_Loader_Array(array(
            'rule_editor.html.twig' => $templateContent,
        ));
         */
        return $loader;
    }

    public function render()
    {
        return $this->environment->render('@Kendo/rule_editor.html.twig', array(
            'view'   => $this,
            'editor' => $this->editor,
            'policy' => $this->editor->getPolicy(),
            'rule_loader' => $this->editor->getLoader(),
        ));
    }

    public function __toString()
    {
        return $this->render();
    }

}





<?php
namespace Kendo\RuleEditor;
use Twig_Loader_Array;
use Twig_Environment;
use Twig_LoaderInterface;
use Twig_Extension_Debug;

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
        } else {
            if (!$loader) {
                $loader = $this->createDefaultTemplateLoader();
            }
            $this->environment = new Twig_Environment($loader, [ 'debug' => true, ]);
            $this->environment->addExtension(new Twig_Extension_Debug());
        }
    }

    public function createDefaultTemplateLoader()
    {
        $templateContent = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'rule_editor.html.twig');
        $loader = new Twig_Loader_Array(array(
            'rule_editor.html.twig' => $templateContent,
        ));
        return $loader;
    }

    public function render()
    {
        return $this->environment->render('rule_editor.html.twig', array(
            'view'   => $this,
            'editor' => $this->editor,
            'rule_loader' => $this->editor->getLoader(),
        ));
    }

    public function __toString()
    {
        return $this->render();
    }

}





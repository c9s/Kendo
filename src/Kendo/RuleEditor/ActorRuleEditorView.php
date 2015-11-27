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


    protected $defaultTwigOptions = [ 'debug' => true ];


    protected $formTemplate = '@Kendo/rule_editor.html.twig';

    /**
     * @var Twig_Environment
     */
    protected $environment;

    protected $readonly = false;

    protected $options = array(
        'rules_field_name' => 'rules',
    );

    public function __construct(ActorRuleEditor $editor, Twig_Environment $environment = null, array $options = array())
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

            $loader = $this->createDefaultTemplateLoader();
            $this->environment = new Twig_Environment($loader, $this->defaultTwigOptions);
            $this->environment->addExtension(new Twig_Extension_Debug());

        }

        $this->options = array_merge($this->options, $options);
    }

    public function getTemplateDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Templates';
    }

    public function createDefaultTemplateLoader()
    {
        $loader = new Twig_Loader_Filesystem;
        $loader->addPath($this->getTemplateDirectory(), 'Kendo');
        return $loader;
    }

    public function setReadOnly($readonly = true)
    {
        $this->readonly = $readonly;
    }

    public function render()
    {
        return $this->environment->render(
            ($this->readonly ? $this->viewTemplate : $this->formTemplate),
            [
                'view'   => $this,
                'editor' => $this->editor,
                'policy' => $this->editor->getPolicy(),
                'readonly' => $this->readonly,
                'rule_loader' => $this->editor->getLoader(),
                'rules_field_name' => $this->options['rules_field_name'],
            ]
        );
    }

    public function __toString()
    {
        return $this->render();
    }

}





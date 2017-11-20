<?php

/* themes/botpromo/templates/page.html.twig */
class __TwigTemplate_48ee0e84a1edbdf03f512d96e726ae703d3e05fbfc67185d4ca002f2f9be635c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $tags = array("if" => 1);
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('Twig_Extension_Sandbox')->checkSecurity(
                array('if'),
                array(),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 1
        if ($this->getAttribute(($context["page"] ?? null), "top", array())) {
            // line 2
            echo "<div class=\"above-header\">
    ";
            // line 3
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "top", array()), "html", null, true));
            echo "
</div>
";
        }
        // line 6
        echo "
";
        // line 7
        if ($this->getAttribute(($context["page"] ?? null), "header", array())) {
            // line 8
            echo "<header>";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "header", array()), "html", null, true));
            echo "</header>
";
        }
        // line 10
        echo "
";
        // line 11
        if ($this->getAttribute(($context["page"] ?? null), "content", array())) {
            // line 12
            echo "<main>";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "content", array()), "html", null, true));
            echo "</main>
";
        }
        // line 14
        echo "
";
        // line 15
        if ($this->getAttribute(($context["page"] ?? null), "sidebar", array())) {
            // line 16
            echo "<aside>";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "sidebar", array()), "html", null, true));
            echo "</aside>
";
        }
        // line 18
        echo "
";
        // line 19
        if ($this->getAttribute(($context["page"] ?? null), "footer", array())) {
            // line 20
            echo "<footer>";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "footer", array()), "html", null, true));
            echo "</footer>
";
        }
    }

    public function getTemplateName()
    {
        return "themes/botpromo/templates/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  92 => 20,  90 => 19,  87 => 18,  81 => 16,  79 => 15,  76 => 14,  70 => 12,  68 => 11,  65 => 10,  59 => 8,  57 => 7,  54 => 6,  48 => 3,  45 => 2,  43 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "themes/botpromo/templates/page.html.twig", "C:\\MAMP\\htdocs\\cmsdev-bot4hire\\drupal\\themes\\botpromo\\templates\\page.html.twig");
    }
}

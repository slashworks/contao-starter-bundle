<?php

namespace Slashworks\ContaoStarterBundle\DataContainer;

use Contao\Backend;
use Contao\DataContainer;
use Contao\FormTextField;
use Contao\System;
use Contao\Widget;

class General extends Backend
{

    /**
     * Show a custom template name in the backend instead of the template's file name.
     *
     * @param $table
     * @param $templateGroup
     * @param $defaultTemplateKey
     *
     * @return array
     */
    protected function getCustomTemplateNames($table, $templateGroup, $defaultTemplateKey)
    {
        $namedTemplates = array();
        $templates = $this->getTemplateGroup($templateGroup);

        foreach ($templates as $key => $value) {
            if ($key === $defaultTemplateKey) {
                $namedTemplates[$key] = $GLOBALS['TL_LANG'][$table]['customTemplateNames']['default'];
                continue;
            }

            if (isset($GLOBALS['TL_LANG'][$table]['customTemplateNames'][$key])) {
                $namedTemplates[$key] = $GLOBALS['TL_LANG'][$table]['customTemplateNames'][$key] . ' (' . $value . ')';
            } else {
                $namedTemplates[$key] = $value;
            }
        }

        return $namedTemplates;
    }

    /**
     * Show custom template names for content elements.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomElementTemplates(DataContainer $dc)
    {
        $type = $dc->activeRecord->type;
        return $this->getCustomTemplateNames($dc->table, 'ce_' . $type, 'ce_' . $type);
    }

    /**
     * Show custom template names for articles.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomArticleTemplates(DataContainer $dc)
    {
        return $this->getCustomTemplateNames($dc->table, 'mod_article' . $type, 'mod_article');
    }

    /**
     * Show custom template names for forms.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomFormTemplates(DataContainer $dc)
    {
        return $this->getCustomTemplateNames($dc->table, 'form_wrapper_', 'form_wrapper');
    }

    /**
     * Show custom template names for form fields.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomFormFieldTemplates(DataContainer $dc)
    {
        // Get the default template from the widget class itself, as the naming is not tightly coupled to the type.
        $type = $dc->activeRecord->type;
        $widgetClass = $GLOBALS['TL_FFL'][$type];
        /** @var Widget $widget */
        $widget = new $widgetClass();

        $templateGroup = $widget->template;
        $defaultTemplateKey = $widget->template;

        return $this->getCustomTemplateNames($dc->table, $templateGroup, $defaultTemplateKey);
    }

    /**
     * Show custom template names for front end modules.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomModuleTemplates(DataContainer $dc)
    {
        $type = $dc->activeRecord->type;
        return $this->getCustomTemplateNames($dc->table, 'mod_' . $type, 'mod_' . $type);
    }

    /**
     * Show custom template names for navigation.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomNavigationTemplates(DataContainer $dc)
    {
        return $this->getCustomTemplateNames($dc->table, 'nav_', 'nav_default');
    }

    /**
     * Show custom template names for member data.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomMemberTemplates(DataContainer $dc)
    {
        return $this->getCustomTemplateNames($dc->table, 'member_', 'member_default');
    }

    /**
     * Show custom template names for search engines.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomSearchTemplates(DataContainer $dc)
    {
        return $this->getCustomTemplateNames($dc->table, 'search_', 'search_default');
    }

    /**
     * Show custom template names for RSS readers.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomRssTemplates(DataContainer $dc)
    {
        return $this->getCustomTemplateNames($dc->table, 'rss_', 'rss_default');
    }

}

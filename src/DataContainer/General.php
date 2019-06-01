<?php

namespace Slashworks\ContaoStarterBundle\DataContainer;

use Contao\Backend;
use Contao\DataContainer;

class General extends Backend
{

    /**
     * Instead of showing the file name in the backend, show a custom translation.
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getCustomTemplateNames(DataContainer $dc)
    {
        $namedTemplates = array();
        $templates = array();
        $table = $dc->table;
        $type = $dc->activeRecord->type;

        switch ($table) {
            case 'tl_article':
                $templates = $this->getTemplateGroup('mod_article');
                $defaultTemplateKey = 'mod_article';
                break;
            case 'tl_content':
                $templates = $this->getTemplateGroup('ce_' . $type);
                $defaultTemplateKey = 'ce_' . $type;
                break;
            case 'tl_form':
                $templates = $this->getTemplateGroup('form_wrapper_');
                $defaultTemplateKey = 'form_wrapper';
                break;
            case 'tl_form_field':
                $templates = $this->getTemplateGroup('form_' . $type);
                $defaultTemplateKey = 'form_' . $type;
                break;
            case 'tl_module':
                $templates = $this->getTemplateGroup('mod_' . $type);
                $defaultTemplateKey = 'mod_' . $type;
                break;
            default:
                break;
        }

        foreach ($templates as $key => $value) {
            if ($key === $defaultTemplateKey) {
                $namedTemplates[$key] = $GLOBALS['TL_LANG'][$table]['customTemplateNames']['default'];
                continue;
            }

            if (isset($GLOBALS['TL_LANG'][$table]['customTemplateNames'][$key])) {
                $namedTemplates[$key] = $GLOBALS['TL_LANG'][$table]['customTemplateNames'][$key];
            } else {
                $namedTemplates[$key] = $value;
            }
        }
        
        return $namedTemplates;
    }
    
}

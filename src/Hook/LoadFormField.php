<?php

namespace Slashworks\ContaoStarterBundle\Hook;

use Contao\Form;
use Contao\FormCaptcha;
use Contao\Widget;

/**
 * Class LoadFormField
 *
 * Provide methods for the loadFormField Hook of Contao.
 *
 * @package Slashworks\ContaoStarterBundle\Hook
 */
class LoadFormField
{

    /**
     * Add the widget's name to the CSS prefix for easier frontend manipulation.
     *
     * @param Widget $widget
     * @param        $formId
     * @param        $data
     * @param Form   $form
     *
     * @return Widget
     */
    public function addWidgetNames(Widget $widget, $formId, $data, Form $form)
    {
        // Exclude special widgets.
        if ($widget instanceof FormCaptcha) {
            return $widget;
        }

        if ($widget->name) {
            $widget->prefix .= ' widget-' . $widget->name;
        }

        return $widget;
    }

}

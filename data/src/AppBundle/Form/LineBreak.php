<?php

namespace AppBundle\Form;

use Contao\Widget;

class LineBreak extends Widget
{

    /**
     * Submit user input
     *
     * @var boolean
     */
    protected $blnSubmitInput = false;

    /**
     * Add a for attribute
     *
     * @var boolean
     */
    protected $blnForAttribute = false;

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'form_linebreak';

    /**
     * The CSS class prefix
     *
     * @var string
     */
    protected $strPrefix = 'widget widget-linebreak';

    public function generate()
    {
        return '<hr>';
    }

}

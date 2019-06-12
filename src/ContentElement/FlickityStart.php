<?php

namespace Slashworks\ContaoStarterBundle\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentElement;

class FlickityStart extends ContentElement
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_flickitystart';

    protected function compile()
    {
        if (TL_MODE == 'BE')
        {
            $this->strTemplate = 'be_wildcard';

            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new BackendTemplate($this->strTemplate);

            $this->Template = $objTemplate;
            $this->Template->title = $this->headline;
        }

    }

}

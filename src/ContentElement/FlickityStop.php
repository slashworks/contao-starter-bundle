<?php

namespace Slashworks\ContaoStarterBundle\ContentElement;

use Contao\BackendTemplate;

/**
 * Front end content element "slider" (wrapper stop).
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FlickityStop extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_flickitystop';

	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		if (TL_MODE == 'BE')
		{
			$this->strTemplate = 'be_wildcard';

			/** @var BackendTemplate|object $objTemplate */
			$objTemplate = new BackendTemplate($this->strTemplate);

			$this->Template = $objTemplate;
		}

        $GLOBALS['TL_CSS'][] = 'bundles/contaostarter/vendor/flickity/flickity.min.css';
        $GLOBALS['TL_BODY'][] = '<script src="bundles/contaostarter/vendor/flickity/flickity.pkgd.min.js"></script>';
	}
}

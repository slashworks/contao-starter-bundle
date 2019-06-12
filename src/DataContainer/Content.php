<?php

namespace Slashworks\ContaoStarterBundle\DataContainer;

use Contao\DataContainer;

/**
 * Class Content
 *
 * @package Slashworks\ContaoStarterBundle\DataContainer
 */
class Content
{

    /**
     * Fill the example flickity configuration field with some example values.
     *
     * @param mixed         $value
     * @param DataContainer $dc
     *
     * @return string
     */
    public function generateExampleFlickityConfiguration($value, DataContainer $dc)
    {
        $value = <<<EOF
{
    "draggable": ">1", // Draggable if more than 1 element. Can also be 'false' or 'true' for always draggable.
    "freeScroll": "true", // Content can be freely scrolled, without snapping to cells.
    "wrapAround": true, // At the end of cells, wrap-around to the other end for infinite scrolling.
    "autoPlay": 5000, // Advance cells every 5000 ms
    "pauseAutoPlayOnHover": false, // Autoplay continues when user hovers over slider.
    "selectedAttraction": 0.025, // Higher values make the slider move faster, lower makes it move slower. Use in conjunction with the 'friction' option.,
    "imagesLoaded": true, // Reposition cells once the images have been loaded and prevents wrong cell positions.
    "initialIndex": 2, // Zero based index of the initial selected cell.
    "prevNextButtons": false, // Disable previous & next buttons.
    "pageDots": false, // Disable page dots
    "arrowShape": "M58.33 75l5.9-5.9L45.12 50l19.1-19.1-5.9-5.9-25 25 25.01 25z" // The path of the left arrow, drawn in a 100 x 100 viewbox.
}
EOF;

        return $value;
    }

}

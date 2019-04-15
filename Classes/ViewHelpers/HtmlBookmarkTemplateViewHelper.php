<?php

namespace Bithost\Pdfviewhelpers\ViewHelpers;

/* * *
 *
 * This file is part of the "PDF ViewHelpers" Extension for TYPO3 CMS.
 *
 *  (c) 2016 Markus Mächler <markus.maechler@bithost.ch>, Bithost GmbH
 *           Esteban Marin <esteban.marin@bithost.ch>, Bithost GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * * */

/**
 * HtmlBookmarkTemplateViewHelper
 *
 * @author Markus Mächler <markus.maechler@bithost.ch>, Esteban Marin <esteban.marin@bithost.ch>
 */
class HtmlBookmarkTemplateViewHelper extends AbstractPDFViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('level', 'integer', '', false, $this->settings['htmlBookmarkTemplate']['level']);
        $this->registerArgument('sanitizeWhitespace', 'boolean', '', false, $this->settings['htmlBookmarkTemplate']['sanitizeWhitespace']);
    }

    /**
     * @return void
     */
    public function render()
    {
        $bookmarkTemplates = $this->viewHelperVariableContainer->get('TableOfContentViewHelper', 'bookmarkTemplates');
        $template = $this->renderChildren();

        if ($this->arguments['sanitizeWhitespace']) {
            $template = trim($template);
            $template = preg_replace('/(\>)\s*(\<)/m', '$1$2', $template);
        }

        $bookmarkTemplates[$this->arguments['level']] = $template;

        $this->viewHelperVariableContainer->addOrUpdate('TableOfContentViewHelper', 'bookmarkTemplates', $bookmarkTemplates);
    }
}

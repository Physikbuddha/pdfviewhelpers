<?php

declare(strict_types=1);

namespace Bithost\Pdfviewhelpers\Tests\Functional;

/* * *
 *
 * This file is part of the "PDF ViewHelpers" Extension for TYPO3 CMS.
 *
 *  (c) 2016 Markus Mächler <markus.maechler@bithost.ch>, Bithost GmbH
 *           Esteban Gehring <esteban.gehring@bithost.ch>, Bithost GmbH
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

use Nimut\TestingFramework\Exception\Exception;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use Smalot\PdfParser\Document;
use Smalot\PdfParser\Parser;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * BaseFunctionalTest
 *
 * @author Markus Mächler <markus.maechler@bithost.ch>, Esteban Gehring <esteban.gehring@bithost.ch>
 */
abstract class AbstractFunctionalTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/pdfviewhelpers'];

    /**
     * 150 words 890 characters
     */
    protected string $loremIpsumText = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';

    /**
     * Setup TYPO3 environment
     *
     * @return void
     *
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->importDataSet($this->getFixturePath('pages.xml'));
        $this->setUpPage();
        $this->setUpBackendUserFromFixture(1);
    }

    /**
     * Load TypoScript files
     */
    public function setUpPage(array $typoScriptFiles = []): void
    {
        $baseTypoScripts = [
            __DIR__ . '/../../Configuration/TypoScript/setup.txt',
        ];

        $this->setUpFrontendRootPage(
            1,
            array_merge($baseTypoScripts, $typoScriptFiles)
        );
    }

    /**
     * @return mixed
     */
    protected function renderFluidTemplate(string $templatePath, array $variables = [])
    {
        /** @var StandaloneView $standaloneView */
        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);

        $standaloneView->setFormat('html');
        $standaloneView->setTemplatePathAndFilename($templatePath);
        $standaloneView->assignMultiple($variables);

        return $standaloneView->render();
    }

    protected function getFixturePath(string $path): string
    {
        return __DIR__ . '/Fixtures/' . $path;
    }

    public function parseFile(string $filename): Document
    {
        $parser = new Parser();

        return $parser->parseFile($filename);
    }

    public function parseContent(string $content): Document
    {
        $parser = new Parser();

        return $parser->parseContent($content);
    }

    protected function getLongLoremIpsumText(int $duplicates): string
    {
        $longLoremIpsumText = '';
        $duplicates = max($duplicates, 0);

        for ($i=0; $i < $duplicates; $i++) {
            $longLoremIpsumText .= $this->loremIpsumText;
        }

        return $longLoremIpsumText;
    }
}

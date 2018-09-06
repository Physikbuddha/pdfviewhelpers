<?php

namespace Bithost\Pdfviewhelpers\Tests\Functional;

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
 * SpeakingSettingsTest
 *
 * @author Markus Mächler <markus.maechler@bithost.ch>, Esteban Marin <esteban.marin@bithost.ch>
 */
class SpeakingSettingsTest extends AbstractFunctionalTest
{
    /**
     * @test
     */
    public function testSetupSettings()
    {
        $this->setUpPage([$this->getFixturePath('SpeakingSettings/setup.txt')]);

        $output = $this->renderFluidTemplate($this->getFixturePath('SpeakingSettings/TypoScript.html'));
        $pdf = $this->parseContent($output);
        $text = $pdf->getText();

        $this->assertContains('Test', $text);
    }

    /**
     * @test
     */
    public function testSpeakingSetupSettings()
    {
        $this->setUpPage([$this->getFixturePath('SpeakingSettings/setup_speaking.txt')]);

        $output = $this->renderFluidTemplate($this->getFixturePath('SpeakingSettings/TypoScript.html'));
        $pdf = $this->parseContent($output);
        $text = $pdf->getText();

        $this->assertContains('Test', $text);
    }

    /**
     * @test
     */
    public function testFluid()
    {
        $outputDestinations = ['string', 'S'];
        $orientations = ['portrait', 'P', 'landscape', 'L'];
        $fontStyles = ['bold', 'B', 'italic', 'I', 'regular', 'R', 'underline', 'U'];
        $alignments = ['left', 'L', 'center', 'C', 'right', 'R', 'justify', 'J'];

        $outputDestinationsCount = count($outputDestinations);
        $orientationsCount = count($orientations);
        $fontStylesCount = count($fontStyles);
        $alignmentsCount = count($alignments);

        $maxElements = max($outputDestinationsCount, $orientationsCount, $fontStylesCount, $alignmentsCount);

        for ($i=0; $i<$maxElements; $i++) {
            $output = $this->renderFluidTemplate(
                $this->getFixturePath('SpeakingSettings/FluidAttributes.html'),
                [
                    'outputDestination' => $outputDestinations[$i % $outputDestinationsCount ],
                    'orientation' => $orientations[$i % $orientationsCount],
                    'fontStyle' => $fontStyles[$i % $fontStylesCount],
                    'alignment' => $alignments[$i % $alignmentsCount],
                ]
            );
            $pdf = $this->parseContent($output);
            $text = $pdf->getText();

            $this->assertContains('Test', $text);
        }
    }

    /**
     * @test
     *
     * @expectedException \Bithost\Pdfviewhelpers\Exception\ValidationException
     */
    public function testInvalidOutputDestination()
    {
        $this->renderFluidTemplate(
            $this->getFixturePath('SpeakingSettings/FluidAttributes.html'),
            [
                'outputDestination' => 'invalid',
                'orientation' => 'L',
                'fontStyle' => 'B',
                'alignment' => 'L',
            ]
        );
    }

    /**
     * @test
     *
     * @expectedException \Bithost\Pdfviewhelpers\Exception\ValidationException
     */
    public function testInvalidOrientation()
    {
        $this->renderFluidTemplate(
            $this->getFixturePath('SpeakingSettings/FluidAttributes.html'),
            [
                'outputDestination' => 'S',
                'orientation' => 'XYZ',
                'fontStyle' => 'B',
                'alignment' => 'L',
            ]
        );
    }

    /**
     * @test
     *
     * @expectedException \Bithost\Pdfviewhelpers\Exception\ValidationException
     */
    public function testInvalidFontStyle()
    {
        $this->renderFluidTemplate(
            $this->getFixturePath('SpeakingSettings/FluidAttributes.html'),
            [
                'outputDestination' => 'S',
                'orientation' => 'P',
                'fontStyle' => 'nothing',
                'alignment' => 'L',
            ]
        );
    }

    /**
     * @test
     *
     * @expectedException \Bithost\Pdfviewhelpers\Exception\ValidationException
     */
    public function testInvalidAlignment()
    {
        $this->renderFluidTemplate(
            $this->getFixturePath('SpeakingSettings/FluidAttributes.html'),
            [
                'outputDestination' => 'S',
                'orientation' => 'P',
                'fontStyle' => 'B',
                'alignment' => 'rectify',
            ]
        );
    }
}
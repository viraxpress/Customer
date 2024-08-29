<?php
/**
 * ViraXpress - https://www.viraxpress.com
 *
 * LICENSE AGREEMENT
 *
 * This file is part of the ViraXpress package and is licensed under the ViraXpress license agreement.
 * You can view the full license at:
 * https://www.viraxpress.com/license
 *
 * By utilizing this file, you agree to comply with the terms outlined in the ViraXpress license.
 *
 * DISCLAIMER
 *
 * Modifications to this file are discouraged to ensure seamless upgrades and compatibility with future releases.
 *
 * @category    ViraXpress
 * @package     ViraXpress_Customer
 * @author      ViraXpress
 * @copyright   © 2024 ViraXpress (https://www.viraxpress.com/)
 * @license     https://www.viraxpress.com/license
 */

namespace ViraXpress\Customer\View\Element\Html;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use ViraXpress\Configuration\Helper\Data;
use Magento\Framework\View\Element\Html\Date as HtmlDate;
use Magento\Framework\Phrase;
use IntlDateFormatter;

/**
 * Date element block
 */
class Date extends HtmlDate
{

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $helperData
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Data $helperData
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->helperData = $helperData;
    }

    /**
     * Render block HTML
     *
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _toHtml()
    {
        $html = '<input type="text" name="' . $this->getName() . '" id="' . $this->getId() . '" ';
        $html .= 'value="' . $this->escapeHtml($this->getValue()) . '" ';
        $html .= 'class="' . $this->getClass() . '" ' . $this->getExtraParams() . '/> ';
        $enableViraXpress = $this->scopeConfig->getValue('viraxpress_config/general/enable_viraxpress', ScopeInterface::SCOPE_STORE);
        $isViraXpressTheme = $this->helperData->isViraXpressEnable();
        if (!$enableViraXpress && $isViraXpressTheme) {
            $html .= '<script type="text/javascript">
                require(["jquery", "mage/calendar"], function($){
                        $("#' .
                $this->getId() .
                '").calendar({
                            showsTime: ' .
                ($this->getTimeFormat() ? 'true' : 'false') .
                ',
                            ' .
                ($this->getTimeFormat() ? 'timeFormat: "' .
                $this->getTimeFormat() .
                '",' : '') .
                '
                            dateFormat: "' .
                $this->getDateFormat() .
                '",
                            buttonImage: "' .
                $this->getImage() .
                '",
                            ' .
                ($this->getYearsRange() ? 'yearRange: "' .
                $this->getYearsRange() .
                '",' : '') .
                '
                            buttonText: "' .
                (string)new \Magento\Framework\Phrase(
                    'Select Date'
                ) .
                '"' . ($this->getMaxDate() ? ', maxDate: "' . $this->getMaxDate() . '"' : '') .
                ($this->getChangeMonth() === null ? '' : ', changeMonth: ' . $this->getChangeMonth()) .
                ($this->getChangeYear() === null ? '' : ', changeYear: ' . $this->getChangeYear()) .
                ($this->getShowOn() ? ', showOn: "' . $this->getShowOn() . '"' : '') .
                ($this->getFirstDay() ? ', firstDay: ' . $this->getFirstDay() : '') .
                '})
                });
                </script>';
        }
        return $html;
    }

    /**
     * Convert special characters to HTML entities
     *
     * @return string
     */
    public function getEscapedValue()
    {
        if ($this->getFormat() && $this->getValue()) {
            $dateFormatter = new \IntlDateFormatter(
                $this->getLocale(),
                \IntlDateFormatter::SHORT,
                \IntlDateFormatter::NONE
            );
            $timestamp = strtotime($this->getValue());
            $formattedDate = $dateFormatter->format($timestamp);
            return $formattedDate;
        }
        return $this->escapeHtml($this->getValue());
    }

    /**
     * Produce and return block's html output
     *
     * {@inheritdoc}
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->toHtml();
    }
}

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

namespace ViraXpress\Customer\Block\Account;

use Magento\Framework\View\LayoutFactory;
use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use ViraXpress\Configuration\Helper\Data;

/**
 * Class for sortable links.
 */
class SortLink extends \Magento\Customer\Block\Account\SortLink
{

    /**
     * @var DefaultPathInterface
     */
    protected $defaultPath;

    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Constructor
     *
     * @param Context $context
     * @param DefaultPathInterface $defaultPath
     * @param LayoutFactory $layoutFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        LayoutFactory $layoutFactory,
        ScopeConfigInterface $scopeConfig,
        Data $helperData,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
        $this->scopeConfig = $scopeConfig;
        $this->layoutFactory = $layoutFactory;
        $this->helperData = $helperData;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }
        $enableViraXpress = $this->scopeConfig->getValue('viraxpress_config/general/enable_viraxpress', ScopeInterface::SCOPE_STORE);
        $isViraXpressTheme = $this->helperData->isViraXpressEnable();
        if ($enableViraXpress && $isViraXpressTheme) {
            $menus = $this->layoutFactory->create()->createBlock(\Magento\Framework\View\Element\Template::class)
                ->setTemplate('Magento_Customer::account/components/account-menu.phtml')
                ->setIsHighlighted($this->getIsHighlighted())
                ->setIsCurrent($this->isCurrent())
                ->setLabel($this->getLabel())
                ->setTitle($this->getTitle())
                ->setAttributesHtml($this->getAttributesHtml())
                ->setHref($this->getHref());
            $html = $menus->toHtml();
            return $html;
        } else {
            return parent::_toHtml();
        }
    }

    /**
     * Get sort order.
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Generate attributes' HTML code
     *
     * @return string
     */
    private function getAttributesHtml()
    {
        $attributesHtml = '';
        $attributes = $this->getAttributes();
        if ($attributes) {
            foreach ($attributes as $attribute => $value) {
                $attributesHtml .= ' ' . $attribute . '="' . $this->escapeHtml($value) . '"';
            }
        }

        return $attributesHtml;
    }
}

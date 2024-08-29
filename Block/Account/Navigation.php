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

declare(strict_types=1);

namespace ViraXpress\Customer\Block\Account;

use Magento\Framework\View\Element\Html\Links;
use Magento\Customer\Block\Account\SortLinkInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use ViraXpress\Configuration\Helper\Data;
use Magento\Framework\View\Element\Template\Context;

class Navigation extends Links
{

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Navigation constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param LayoutFactory $layoutFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerSession $customerSession
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        LayoutFactory $layoutFactory,
        ScopeConfigInterface $scopeConfig,
        CustomerSession $customerSession,
        Data $helperData,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
        $this->layout = $context->getLayout();
        $this->layoutFactory = $layoutFactory;
        $this->customerSession = $customerSession;
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
            $menus = $this
                ->setTemplate('Magento_Customer::account/components/top-menu.phtml')
                ->setLinks($this->getDefaultLinks())
                ->setCssClass($this->getCssClass())
                ->setHasCssClass($this->hasCssClass());
            $html = $menus->toHtml();
        } else {
            $html = '';
            if ($this->getLinks()) {
                $html = '<ul' . ($this->hasCssClass() ? ' class="' . $this->escapeHtml(
                    $this->getCssClass()
                ) . '"' : '') . '>';
                foreach ($this->getLinks() as $link) {
                    $html .= $this->renderLink($link);
                }
                $html .= '</ul>';
            }
        }
        return $html;
    }

    /**
     * @inheritdoc
     */
    public function getLinks()
    {
        $links = $this->_layout->getChildBlocks($this->getNameInLayout());
        $sortableLink = [];
        foreach ($links as $key => $link) {
            if ($link instanceof SortLinkInterface) {
                $sortableLink[] = $link;
                unset($links[$key]);
            }
        }

        usort($sortableLink, [$this, "compare"]);
        return array_merge($sortableLink, $links);
    }

    /**
     * Compare sortOrder in links.
     *
     * @param SortLinkInterface $firstLink
     * @param SortLinkInterface $secondLink
     * @return int
     */
    private function compare(SortLinkInterface $firstLink, SortLinkInterface $secondLink): int
    {
        return $secondLink->getSortOrder() <=> $firstLink->getSortOrder();
    }

    /**
     * Retrieves the default links.
     *
     * @return array
     */
    public function getDefaultLinks()
    {
        $links = parent::getLinks();
        return $links;
    }

    /**
     * Render Block
     *
     * @param \Magento\Framework\View\Element\AbstractBlock $link
     * @return string
     */
    public function renderChildLink(\Magento\Framework\View\Element\AbstractBlock $link)
    {
        return $this->layout->renderElement($link->getNameInLayout());
    }
}

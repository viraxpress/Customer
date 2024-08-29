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

use Magento\Customer\Block\Account\SortLinkInterface;
use Magento\Customer\Model\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Customer\Model\Url as CustomerUrl;

class Link extends \Magento\Customer\Block\Account\Link
{
    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $customerUrl;

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @param TemplateContext $context
     * @param CustomerUrl $customerUrl
     * @param HttpContext $httpContext
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        CustomerUrl $customerUrl,
        HttpContext $httpContext,
        array $data = []
    ) {
        $this->httpContext = $httpContext;
        parent::__construct($context, $customerUrl, $data);
    }

    /**
     * Get account url.
     *
     * @return string
     */
    public function getHref()
    {
        return $this->_customerUrl->getAccountUrl();
    }

    /**
     * Retrieve the sort order.
     *
     * @return int|null The sort order value or null if not set.
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }
}

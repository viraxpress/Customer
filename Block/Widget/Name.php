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

namespace ViraXpress\Customer\Block\Widget;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Customer\Helper\Address;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Model\Options;
use Magento\Customer\Api\AddressMetadataInterface;

class Name extends \Magento\Customer\Block\Widget\Name
{
    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * @param Context $context
     * @param Address $addressHelper
     * @param CustomerMetadataInterface $customerMetadata
     * @param Options $options
     * @param AddressMetadataInterface $addressMetadata
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Address $addressHelper,
        CustomerMetadataInterface $customerMetadata,
        Options $options,
        AddressMetadataInterface $addressMetadata,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        array $data = []
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        parent::__construct($context, $addressHelper, $customerMetadata, $options, $addressMetadata, $data);
    }

    /**
     * Get the last ordered guest info.
     */
    public function getCustomerData()
    {
        $jsonData = $this->cookieManager->getCookie('guest_customer_data');
        if ($jsonData) {
            $customerData = json_decode($jsonData, true);
            return $customerData;
        }
        return [];
    }
}

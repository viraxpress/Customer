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

namespace ViraXpress\Customer\ViewModel;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\Store;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Request\Http;
use Magento\Directory\Model\Country\Postcode\Config as PostCodeConfig;

class Directory implements ArgumentInterface
{
    /**
     * @var DirectoryHelper
     */
    private $directoryHelper;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var PostCodeConfig
     */
    protected $postCodeConfig;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor
     * @param DirectoryHelper $directoryHelper
     * @param CustomerSession $customerSession
     * @param PostCodeConfig $postCodeConfig
     * @param SerializerInterface $serializer
     * @param Http $request
     */
    public function __construct(
        DirectoryHelper $directoryHelper,
        CustomerSession $customerSession,
        PostCodeConfig $postCodeConfig,
        SerializerInterface $serializer,
        Http $request
    ) {
        $this->directoryHelper = $directoryHelper;
        $this->customerSession = $customerSession;
        $this->postCodeConfig = $postCodeConfig;
        $this->serializer = $serializer;
        $this->request = $request;
    }

    /**
     * Get serialized post codes
     *
     * @return string
     */
    public function getSerializedPostCodes(): string
    {
        return $this->serializer->serialize($this->postCodeConfig->getPostCodes());
    }

    /**
     * Return default country code from system configuration at general/country/default
     *
     * @param Store|string|int $store
     * @return string
     */
    public function getDefaultCountry($store = null): string
    {
        return $this->directoryHelper->getDefaultCountry($store);
    }

    /**
     * Return array of ISO2 country codes set in system configuration at general/country/destinations
     *
     * @return string[]
     */
    public function getTopCountryCodes(): array
    {
        return $this->directoryHelper->getTopCountryCodes();
    }

    /**
     * Get section data.
     */
    public function getSectionData()
    {
        $output = [];
        $regionsData = $this->directoryHelper->getRegionData();

        foreach ($this->directoryHelper->getCountryCollection() as $code => $data) {
            $output[$code]['name'] = $data->getName();
            if (array_key_exists($code, $regionsData)) {
                foreach ($regionsData[$code] as $key => $region) {
                    $output[$code]['regions'][$key]['code'] = $region['code'];
                    $output[$code]['regions'][$key]['name'] = $region['name'];
                }
            }
        }
        return $output;
    }

    /**
     * Get current customer
     *
     * @return mixed
     */
    public function getCurrentCustomer()
    {
        return $this->customerSession->getCustomer();
    }

    /**
     * Get form params
     *
     * @return mixed
     */
    public function getFormData()
    {
        return $this->request->getParam('id');
    }
}

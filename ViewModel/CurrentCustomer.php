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

use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Customer\Model\Session as CustomerSession;

class CurrentCustomer implements ArgumentInterface
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var DateTimeFormatterInterface
     */
    private $dateTimeFormatter;

    /**
     * Constructor
     *
     * @param CustomerSession $customerSession
     * @param DateTimeFormatterInterface $dateTimeFormatter
     */
    public function __construct(
        CustomerSession $customerSession,
        DateTimeFormatterInterface $dateTimeFormatter
    ) {
        $this->customerSession = $customerSession;
        $this->dateTimeFormatter = $dateTimeFormatter;
    }

    /**
     * Get current customer
     *
     * @return mixed
     */
    public function getCurrentCustomer()
    {
        $customer = $this->customerSession->getCustomer();
        return $customer;
    }

    /**
     * Get Format date
     *
     * @param string $dobDate The date of birth.
     * @return string
     */
    public function getDobFormatDate($dobDate): string
    {
        $dateTime = new \DateTime($dobDate);
        $formattedDate = $dateTime->format('Y-m-d');
        return $formattedDate;
    }
}

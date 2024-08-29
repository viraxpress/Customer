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

namespace ViraXpress\Customer\Plugin;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;

class CustomerSessionContext
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var Context
     */
    protected $httpContext;

    /**
     * @param Session $customerSession
     * @param Context $httpContext
     */
    public function __construct(
        Session $customerSession,
        Context $httpContext
    ) {
        $this->customerSession = $customerSession;
        $this->httpContext = $httpContext;
    }

    /**
     * Around plugin to set customer context values before dispatching the request.
     *
     * @param ActionInterface $subject
     * @param callable $proceed
     * @param RequestInterface $request
     * @return mixed
     */
    public function aroundDispatch(
        ActionInterface $subject,
        \Closure $proceed,
        RequestInterface $request
    ) {
        $this->httpContext->setValue(
            'customer_id',
            $this->customerSession->getCustomerId(),
            false
        );

        $this->httpContext->setValue(
            'customer_name',
            $this->customerSession->getCustomer()->getName(),
            false
        );

        $this->httpContext->setValue(
            'customer_email',
            $this->customerSession->getCustomer()->getEmail(),
            false
        );

        return $proceed($request);
    }
}

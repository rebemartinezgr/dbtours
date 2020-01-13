<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_GoogleRecaptcha
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Dbtours\Base\Observer\GoogleRecaptcha;

use Magento\Framework\Event\Observer;

/**
 * Class Login
 * @package Mageplaza\GoogleRecaptcha\Observer\Adminhtml
 */
class Captcha extends \Mageplaza\GoogleRecaptcha\Observer\Captcha
{
    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->_helperData->isEnabled() && $this->_helperData->isCaptchaFrontend()) {
            $checkResponse = 1;
            if ($this->_request->getFullActionName() === 'wishlist_index_add') {
                return;
            }
            foreach ($this->_helperData->getFormPostPaths() as $item) {
                if ($item !== '' && strpos($this->_request->getRequestUri(), trim($item, ' ')) !== false) {
                    $checkResponse = 0;
                    if ($this->_request->getParam('g-recaptcha-response')) {
                        $type = $this->_helperData->getRecaptchaType();
                        $response = $this->_helperData->verifyResponse($type);
                        if (!isset($response['success']) || (isset($response['success']) && $response['success'] !== true)) {
                            $this->redirectUrlError($response['message']);
                        }
                    } else {
                        $this->redirectUrlError(__('Missing required parameters recaptcha!'));
                    }
                }
            }
            if ($checkResponse === 1 && $this->_request->getParam('g-recaptcha-response') !== null) {
                $this->redirectUrlError(__('Missing Url in "Form Post Paths" configuration field!'));
            }
        }
    }
}

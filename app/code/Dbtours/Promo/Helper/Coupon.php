<?php

namespace Dbtours\Promo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Coupon
 */
class Coupon extends AbstractHelper
{
    /**
     * @var State
     */
    private $appState;

    /**
     * Mailer constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param State $appState
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        State $appState
    )
    {
        $this->storeManager      = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder  = $transportBuilder;
        $this->appState          = $appState;
    }


    /**
     * @param $variable
     * @param $receiverInfo
     * @param $templateId
     * @param $storeId
     * @return $this
     */
    public function generateTemplate($variable, $receiverInfo, $templateId, $storeId)
    {
        $this->transportBuilder->setTemplateIdentifier($templateId)->setTemplateOptions(
            [
                'area'  => \Magento\Framework\App\Area::AREA_ADMINHTML,
                'store' => $storeId,
            ]
        )->setTemplateVars($variable)->setFrom($this->emailSender())->addTo(
            $receiverInfo['email'],
            $receiverInfo['name']
        );

        return $this;
    }

}


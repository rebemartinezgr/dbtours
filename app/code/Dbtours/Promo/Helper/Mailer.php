<?php

namespace Dbtours\Promo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\SalesRule\Model\ResourceModel\Coupon\Collection as CouponCollection;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Mailer
 */
class Mailer extends AbstractHelper
{
    const XML_PATH_EMAIL_TEMPLATE_FIELD = 'dbtours_promo/general/template_notification';

    /**
     * Sender email config path - from default CONTACT extension
     */
    const XML_PATH_EMAIL_SENDER = 'contact/email/sender_email_identity';

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var State
     */
    private $appState;

    private $couponCollection;

    /**
     * Mailer constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param State $appState
     * @param CouponCollection $couponCollection
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        State $appState,
        CouponCollection $couponCollection
    ){
        parent::__construct($context);
        $this->storeManager      = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder  = $transportBuilder;
        $this->appState          = $appState;
        $this->couponCollection  = $couponCollection;
    }

    /**
     * Return store configuration value of your template field that which id you set for template
     *
     * @param string $path
     * @param int $storeId
     * @return mixed
     */
    private function getConfigValue($path, $storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param $id
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore($id)
    {
        return $this->storeManager->getStore($id);
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

    /**
     * Return email for sender header
     * @return mixed
     */
    public function emailSender()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $name
     * @param $email
     * @param $storeId
     * @return $this
     * @throws LocalizedException
     * @throws MailException
     */
    public function notify($name, $email, $storeId)
    {
        $this->appState->setAreaCode('frontend');

        /* Receiver Detail */
        $receiverInfo = [
            'name'  => $name,
            'email' => $email
        ];

        /* Assign values for your template variables  */
        $variable                 = [];
        $variable['coupon']       = 'test';
        $variable['coupon_valid'] = '31/01/2020';

        $templateId = $this->getConfigValue(self::XML_PATH_EMAIL_TEMPLATE_FIELD, $storeId);
        $this->inlineTranslation->suspend();
        $this->generateTemplate($variable, $receiverInfo, $templateId, $storeId);
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();

        return $this;
    }
}


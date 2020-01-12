<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Booking\Controller\Adminhtml;

use Magento\Backend\Model\View\Result\Page;

/**
 * Class Booking
 */
abstract class Booking extends AbstractAction
{
    const PARAM_CRUD_ID          = 'id';
    const REGISTRY_NAME          = 'dbtours_booking';

    /**
     * @var string
     */
    protected $resource = 'Dbtours_Booking::manage_booking';
    /**
     * Init page.
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage) : Page
    {
        $resultPage->setActiveMenu('Dbtours_Base::base')
            ->addBreadcrumb(__('Booking'), __('Booking'))
            ->addBreadcrumb(__('Manage Bookings'), __('Manage Bookings'));

        return $resultPage;
    }
}

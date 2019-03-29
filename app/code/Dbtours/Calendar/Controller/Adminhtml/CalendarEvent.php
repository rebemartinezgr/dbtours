<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Calendar\Controller\Adminhtml;

use Magento\Backend\Model\View\Result\Page;

/**
 * Class CalendarEvent
 */
abstract class CalendarEvent extends AbstractAction
{
    const PARAM_CRUD_ID          = 'id';
    const REGISTRY_NAME          = 'dbtours_calendarEvent';

    /**
     * @var string
     */
    protected $resource = 'Dbtours_Calendar::manage_calendarevents';
    /**
     * Init page.
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage) : Page
    {
        $resultPage->setActiveMenu('Dbtours_Base::base')
            ->addBreadcrumb(__('CalendarEvent'), __('CalendarEvent'))
            ->addBreadcrumb(__('Manage CalendarEvents'), __('Manage CalendarEvents'));

        return $resultPage;
    }
}

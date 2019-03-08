<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Guide\Controller\Adminhtml;

use Magento\Backend\Model\View\Result\Page;

/**
 * Class Guide
 */
abstract class Guide extends AbstractAction
{
    const PARAM_CRUD_ID          = 'id';
    const REGISTRY_NAME          = 'dbtours_guide';

    /**
     * @var string
     */
    protected $resource = 'Dbtours_Guide::manage_guide';
    /**
     * Init page.
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage) : Page
    {
        $resultPage->setActiveMenu('Dbtours_Base::base')
            ->addBreadcrumb(__('Guide'), __('Guide'))
            ->addBreadcrumb(__('Manage Guides'), __('Manage Guides'));

        return $resultPage;
    }
}

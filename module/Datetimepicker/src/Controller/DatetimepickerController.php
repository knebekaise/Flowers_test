<?php

namespace Datetimepicker\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class DatetimepickerController extends AbstractActionController
{
    /**
     * Load js action
     */
    public function jsAction()
    {
        header('Content-type:application/javascript;charset=utf-8');

        echo file_get_contents(__dir__."/../../assets/js/moment-with-locales.min.js");
        echo file_get_contents(__dir__."/../../assets/js/bootstrap-datetimepicker.min.js");

        exit;
    }

    /**
     * Load css action
     */
    public function cssAction()
    {
        header('Content-type:text/css;charset=utf-8');
        echo file_get_contents(__dir__."/../../assets/css/bootstrap-datetimepicker.min.css");

        exit;
    }
}

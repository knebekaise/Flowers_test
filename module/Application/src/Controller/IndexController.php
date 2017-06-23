<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Form\LoginForm;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * Auth manager.
     * @var Application\Service\AuthManager
     */
    private $authManager;

    /**
     * Auth service.
     * @var \Zend\Authentication\AuthenticationService
     */
    private $authService;


    public function __construct($authManager, $authService)
    {
        $this->authManager = $authManager;
        $this->authService = $authService;
    }

    /**
     * The "index" action performs show default page operation.
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * The "login" action performs login operation.
     */
    public function loginAction()
    {
        if ($this->authService->hasIdentity()) {
            $this->redirect()->toRoute('home');
        }

        // if ($this->authManager->)
        $form = new LoginForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                $data = $form->getData();

                $result = $this->authManager->login($data['login'],
                        $data['password']);
                if ($result->getCode() == Result::SUCCESS) {
                    return $this->redirect()->toRoute('home');
                }
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * The "logout" action performs logout operation.
     */
    public function logoutAction()
    {
        if (!$this->authService->hasIdentity()) {
            $this->redirect()->toRoute('home');
        }
        $this->authManager->logout();

        return $this->redirect()->toRoute('login');
    }
}

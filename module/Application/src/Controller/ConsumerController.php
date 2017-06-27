<?php

namespace Application\Controller;

use Application\Entity\Consumer;
use Application\Entity\Group;
use Application\Form\ConsumerForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

// use Application\Repository\ConsumerRepository;

class ConsumerController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Consumer manager.
     * @var Application\Service\ConsumerManager
     */
    private $consumerManager;

    public function __construct($entityManager, $consumerManager)
    {
        $this->entityManager = $entityManager;
        $this->consumerManager = $consumerManager;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * list of consumers.
     */
    public function indexAction()
    {
        $consumers = $this->entityManager
            ->getRepository(Consumer::class)
            ->findBy([], ['consumerId'=>'ASC']);

        return new ViewModel([
            'consumers' => $consumers,
        ]);
    }

    /**
     * The "view" action displays a page allowing to view consumers's details.
     */
    public function viewAction()
    {
        $consumer = $this->findModel();
        if (empty($consumer)) {
            return;
        }

        return new ViewModel([
            'consumer' => $consumer,
        ]);
    }

    /**
     * The "create" action display a page alllowing to create new consumer
     * @return [type] [description]
     */
    public function createAction()
    {
        $groupRepository = $this->entityManager
            ->getRepository(Group::class);
        $form = new ConsumerForm($groupRepository, 'create', $this->entityManager);

        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                // Add consumer.
                $consumer = $this->consumerManager->addConsumer($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute(
                    'consumer',
                    ['action'=>'index']
                );
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * The "edit" action displays a page allowing to edit consumer.
     */
    public function editAction()
    {
        $consumer = $this->findModel();
        if (empty($consumer)) {
            return;
        }

        $groupRepository = $this->entityManager
            ->getRepository(Group::class);
        // Create consumer form
        $form = new ConsumerForm($groupRepository, 'update', $this->entityManager, $consumer);

        // Check if consumer has submitted the form
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                // Update the consumer.
                $this->consumerManager->updateConsumer($consumer, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute(
                    'consumer',
                    ['action'=>'view', 'id'=>$consumer->getId()]
                );
            }
        } else {
            $form->setData(array(
                    'login'=>$consumer->getLogin(),
                    'password'=>$consumer->getPassword(),
                    'email'=>$consumer->getEmail(),
                    'expirationDateTime'=>$consumer->getExpirationDateTime(),
                    'groupId' => $consumer->getGroupId(),
                ));
        }

        return new ViewModel(array(
            'consumer' => $consumer,
            'form' => $form
        ));
    }

    /**
     * This "delete" action deletes the given consumer.
     */
    public function deleteAction()
    {
        $consumer = $this->findModel();
        if (empty($consumer)) {
            return;
        }

        $this->consumerManager->removeConsumer($consumer);

        // Redirect the user to "index" page.
        return $this->redirect()->toRoute('consumer', ['action'=>'index']);
    }

    protected function findModel()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $consumer = $this->entityManager
            ->getRepository(Consumer::class)
            ->find($id);

        if ($consumer == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return $consumer;
    }
}

<?php

namespace Application\Controller;

use Application\Entity\Consumer;
use Application\Entity\Group;
use Application\Form\ConsumerForm;
use Application\Form\ConsumerSearchForm;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
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

    /**
     * Image manager
     * @var Application\Service\ImageManager
     */
    private $imageManager;

    public function __construct($entityManager, $consumerManager, $imageManager)
    {
        $this->entityManager = $entityManager;
        $this->consumerManager = $consumerManager;
        $this->imageManager = $imageManager;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * list of consumers.
     */
    public function indexAction()
    {
        $params = $this->params()->fromQuery();

        $orderBy = isset($params['orderBy']) ? $params['orderBy'] : 'consumerId';
        $order = isset($params['order']) ? $params['order'] : 'asc';
        $page = isset($params['page']) ? $params['page'] : 1;
        $perPage = isset($params['perPage']) ? $params['perPage'] : 10;

        $groupRepository = $this->entityManager
            ->getRepository(Group::class);
        $form = new ConsumerSearchForm($groupRepository);
        $form->setData($params);

        $filter = $form->isValid() ? $form->getData() : [];

        $query = $this->entityManager
            ->getRepository(Consumer::class)
            ->findForPagination($filter, [$orderBy => $order]);

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($perPage);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'consumers' => $paginator,
            'order'     => $order,
            'orderBy'   => $orderBy,
            'filter'    => $filter,
            'form'      => $form,
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
            'baseImagePath' => $this->imageManager->getSaveToDir(),
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

            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

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
                    ['action'=>'view', 'id' => $consumer->getId()]
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
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

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

<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\GroupForm;
use Application\Entity\Group;

// use Application\Repository\GroupRepository;

class GroupController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Group manager.
     * @var Application\Service\GroupManager
     */
    private $groupManager;

    public function __construct($entityManager, $groupManager)
    {
        $this->entityManager = $entityManager;
        $this->groupManager = $groupManager;
    }

    /**
     * This is the default "index" action of the controller. It displays the
     * list of groups.
     */
    public function indexAction()
    {
        $groups = $this->entityManager
            ->getRepository(Group::class)
            ->findBy([], ['groupId'=>'ASC']);
        
        return new ViewModel([
            'groups' => $groups,
        ]);
    }

    /**
     * The "view" action displays a page allowing to view groups's details.
     */
    public function viewAction()
    {
        $group = $this->findModel();
        if (empty($group)) {
            return;
        }
                
        return new ViewModel([
            'group' => $group,
        ]);
    }

    /**
     * The "create" action display a page alllowing to create new group
     * @return [type] [description]
     */
    public function createAction()
    {
        $form = new GroupForm('create', $this->entityManager);

        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            
            $form->setData($data);
            
            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();
                
                // Add group.
                $group = $this->groupManager->addGroup($data);
                
                // Redirect to "view" page
                return $this->redirect()->toRoute(
                    'group',
                    ['action'=>'index']
                );
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * The "edit" action displays a page allowing to edit group.
     */
    public function editAction()
    {
        $group = $this->findModel();
        if (empty($group)) {
            return;
        }
        
        // Create group form
        $form = new GroupForm('update', $this->entityManager, $group);
        
        // Check if group has submitted the form
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            
            $form->setData($data);
            
            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();
                
                // Update the group.
                $this->groupManager->updateGroup($group, $data);
                
                // Redirect to "view" page
                return $this->redirect()->toRoute(
                    'group',
                    ['action'=>'view', 'id'=>$group->getId()]
                );
            }
        } else {
            $form->setData(array(
                    'name'=>$group->getName(),
                ));
        }
        
        return new ViewModel(array(
            'group' => $group,
            'form' => $form
        ));
    }

    /**
     * This "delete" action deletes the given group.
     */
    public function deleteAction()
    {
        $group = $this->findModel();
        if (empty($group)) {
            return;
        }
        
        $this->groupManager->removeGroup($group);

        // Redirect the user to "index" page.
        return $this->redirect()->toRoute('group', ['action'=>'index']);
    }

    protected function findModel()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $group = $this->entityManager
            ->getRepository(Group::class)
            ->find($id);

        if ($group == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return $group;
    }
}

<?php
// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Drug;
use App\Entity\Category;
use App\Entity\Media;
use App\Entity\Pharmacy;
use App\Entity\User;
use App\Form\AdvertType;
use App\Form\DrugType;
use App\Form\MediaType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use EasyCorp\Bundle\EasyAdminBundle\Exception\EntityRemoveException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\NoEntitiesConfiguredException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\NoPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\UndefinedEntityException;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\FilterRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminBatchFormType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminFiltersFormType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminFormType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\Model\FileUploadState;
use Pagerfanta\Pagerfanta;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;


class AdminController extends EasyAdminController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function dashboard(Request $request)
    {
        return $this->render('bundles/EasyAdminBundle/default/layout.html.twig', [

        ]);
    }

    protected function listAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);
        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $parameters = [
            'paginator' => $paginator,
            'fields' => $fields,
            'batch_form' => $this->createBatchForm($this->entity['name'])->createView(),
            'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['list', $this->entity['templates']['list'], $parameters]);
    }

    /**
     * The method that is executed when the user performs a 'new' action on an entity.
     *
     * @return Response|RedirectResponse
     */
    protected function newAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_NEW);
        $entity = $this->executeDynamicMethod('createNew<EntityName>Entity');
        $em = $this->getDoctrine()->getManager();
        if ($entity instanceof Advert) {
            $newForm = $this->get('form.factory')->create(AdvertType::class, $entity);
        } else {
            $easyadmin = $this->request->attributes->get('easyadmin');
            $easyadmin['item'] = $entity;
            $this->request->attributes->set('easyadmin', $easyadmin);
            $fields = $this->entity['new']['fields'];
            $newForm = $this->executeDynamicMethod('create<EntityName>NewForm', [$entity, $fields]);
        }
        $newForm->handleRequest($this->request);
        if ($newForm->isSubmitted() && $newForm->isValid()) {
//            $this->processUploadedFiles($newForm);

//            $this->dispatch(EasyAdminEvents::PRE_PERSIST, ['entity' => $entity]);
            if ($entity instanceof Drug) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                return $this->redirectToReferrer();
            }
            $this->executeDynamicMethod('persist<EntityName>Entity', [$entity, $newForm]);
            $this->dispatch(EasyAdminEvents::POST_PERSIST, ['entity' => $entity]);

            return $this->redirectToReferrer();
        }

//        $this->dispatch(EasyAdminEvents::POST_NEW, [
//            'entity_fields' => $fields,
//            'form' => $newForm,
//            'entity' => $entity,
//        ]);
//        dump($newForm);
//        die;
        $parameters = [
            'form' => $newForm->createView(),
//            'entity_fields' => $fields,
            'entity' => $entity,
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['new', $this->entity['templates']['new'], $parameters]);
    }


    /**
     * The method that is executed when the user performs a 'edit' action on an entity.
     *
     * @return Response|RedirectResponse
     *
     * @throws \RuntimeException
     */
    protected function editAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_EDIT);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        if ($this->request->isXmlHttpRequest() && $property = $this->request->query->get('property')) {
            $newValue = 'true' === mb_strtolower($this->request->query->get('newValue'));
            $fieldsMetadata = $this->entity['list']['fields'];

            if (!isset($fieldsMetadata[$property]) || 'toggle' !== $fieldsMetadata[$property]['dataType']) {
                throw new \RuntimeException(sprintf('The type of the "%s" property is not "toggle".', $property));
            }

            $this->updateEntityProperty($entity, $property, $newValue);

            // cast to integer instead of string to avoid sending empty responses for 'false'
            return new Response((int) $newValue);
        }

        $fields = $this->entity['edit']['fields'];
        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', [$entity, $fields]);
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $editForm->handleRequest($this->request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->processUploadedFiles($editForm);

            $this->dispatch(EasyAdminEvents::PRE_UPDATE, ['entity' => $entity]);
            $this->executeDynamicMethod('update<EntityName>Entity', [$entity, $editForm]);
            $this->dispatch(EasyAdminEvents::POST_UPDATE, ['entity' => $entity]);

            return $this->redirectToReferrer();
        }
//        $this->dispatch(EasyAdminEvents::POST_EDIT);
        $parameters = [
            'form' => $editForm->createView(),
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['edit', $this->entity['templates']['edit'], $parameters]);
    }

    /**
     * The method that is executed when the user performs a 'delete' action to
     * remove any entity.
     *
     * @return RedirectResponse
     *
     * @throws EntityRemoveException
     */
    protected function deleteAction()
    {
        $id = $this->request->query->get('id');
        $entity = $this->entity['name'];
        switch ($entity) {
            case 'User':
                $entity = new User();
                break;
            case 'Advert':
                $entity = new Advert();
                break;
            case 'Category';
                $entity = new Category();
                break;
            case 'Pharmacy':
                $entity = new Pharmacy();
                break;
            case 'Media':
                $entity = new Media();
                break;
            case 'Drug':
                $entity = new Drug();
                break;
        }
        $em = $this->getDoctrine()->getManager();
        $class = get_class($entity);
        $form = $em->getRepository($class)->findOneBy(['id' => $id]);
        $em->remove($form);
        $em->flush();

        return $this->redirectToReferrer();
    }

    protected function executeDynamicMethod($methodNamePattern, array $arguments = [])
    {
        $methodName = str_replace('<EntityName>', $this->entity['name'], $methodNamePattern);
        if (!\is_callable([$this, $methodName])) {
            $methodName = str_replace('<EntityName>', '', $methodNamePattern);
        }

        if (!method_exists($this, $methodName)) {
            throw new \BadMethodCallException(sprintf('The "%s()" method does not exist in the %s class', $methodName, \get_class($this)));
        }

        return \call_user_func_array([$this, $methodName], $arguments);
    }
}
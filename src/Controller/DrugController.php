<?php

namespace App\Controller;

use App\Entity\Pharmacy;
use App\Entity\Drug;
use App\Form\DrugType;
use App\Manager\DrugManager;
use App\Repository\DrugRepository;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\ErrorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\ValueObject\DefaultParameters;


class DrugController extends AbstractFOSRestController
{
    /**
     * @var DrugManager $drugManager
     */
    protected $drugManager;

    /**
     * DrugController constructor.
     * @param DrugManager $dm
     */
    public function __construct(DrugManager $dm)
    {
        $this->drugManager = $dm;

    }

    /**
     * @Rest\Get("/drugs", name="api_drug_list")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return list of drugs existing in pharmacies near my home",
     *     @SWG\Items(ref=@Model(type=Drug::class, groups={"drug"}))
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid username/password":{
     *              "message": "Invalid credentials."
     *          },
     *          "Invalid customer ref/scope":{
     *              "message": "Access Denied"
     *          },
     *     }
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Not Found error",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @Rest\QueryParam(name="criteria", strict=false,   nullable=true)
     * @Rest\QueryParam(name="limit", strict=false,  nullable=true)
     * @Rest\QueryParam(name="offset", strict=false, nullable=true)
     * @SWG\Tag(name="Drug")
     * @param ParamFetcher $paramFetcher
     * @return \FOS\RestBundle\View\View
     * @param DrugRepository $drugRepository
     * @throws \Exception
     */
    public function list(ParamFetcher $paramFetcher, DrugRepository $drugRepository)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $groups = ['drug'];
        $context->setGroups($groups);

        $limit = $paramFetcher->get('limit') ?? $this->getParameter('defaultLimit');
        $offset = $paramFetcher->get('offset') ?? $this->getParameter('defaultOffset');
        $drugs = $this->drugManager->list($paramFetcher->get('criteria'), $limit, $offset, $drugRepository);

        $response =[
            "totalItems" => count($drugs),
            "items" => $drugs
        ];

        $view = $this->view($response, $responseCode);
        $view->setContext($context);

        return $view;
    }

    /**
     * @Rest\Post("/pharmacies/{id}/drugs", name="api_drug_create", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create a drug"
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid username/password":{
     *              "message": "Invalid credentials."
     *          },
     *          "Invalid customer ref/scope":{
     *              "message": "Access Denied"
     *          },
     *     }
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @SWG\Response(
     *     response=406,
     *     description="Form validation error",
     * ),
     * @SWG\Parameter(
     *     name="body",
     *     description="....",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Property(
     *             property="drugName",
     *             type="string",
     *             example="Ibiprofene"
     *         ),
     *         @SWG\Property(
     *             property="bareCode",
     *             type="string",
     *             example="590142357"
     *         ),
     *         @SWG\Property(
     *             property="description",
     *             type="string",
     *             example="contre les maux de tête"
     *         ),
     *         @SWG\Property(
     *             property="price",
     *             type="string",
     *             example="12.99"
     *         ),
     *         @SWG\Property(
     *             property="expiredAt",
     *             type="string",
     *             example="2019-07-29 21:30:34"
     *         )
     *     )
     * ),
     * @SWG\Tag(name="Drug")
     * @ParamConverter("pharmacy", class="App\Entity\Pharmacy")
     * @param ErrorService $errorService
     * @param Pharmacy $pharmacy
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function create(Request $request, Pharmacy $pharmacy, ErrorService $errorService)
    {
        $responseCode = Response::HTTP_OK;
        $drug = new Drug();
        $context = new Context();
        $groups = ['drug', 'create'];
        $context->setGroups($groups);
        try {
            $form = $this->createForm(DrugType::class, $drug);
            $data = json_decode($request->getContent(),true);
            $form->submit($data);

            if ($form->isSubmitted() && $form->isValid()) {
                $drug->setPharmacy($pharmacy);
                $this->drugManager->save($drug);
            } else {
                $errors = $errorService->getErrorsFromForm($form);

                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch(HttpException $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }

        $view = $this->view($drug, $responseCode);
        $view->setContext($context);

        return $view;
    }

    /**
     * @Rest\Put("/pharmacies/{pharmacy_id}/drugs/{id}", name="api_drug_update", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Update a drug"
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid username/password":{
     *              "message": "Invalid credentials."
     *          },
     *          "Invalid customer ref/scope":{
     *              "message": "Access Denied"
     *          },
     *     }
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @SWG\Response(
     *     response=406,
     *     description="Form validation error",
     * ),
     * @SWG\Parameter(
     *     name="body",
     *     description="....",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Property(
     *             property="drugName",
     *             type="string",
     *             example="Ibiprofene"
     *         ),
     *         @SWG\Property(
     *             property="bareCode",
     *             type="string",
     *             example="590142357"
     *         ),
     *         @SWG\Property(
     *             property="description",
     *             type="string",
     *             example="contre les maux de tête"
     *         ),
     *         @SWG\Property(
     *             property="price",
     *             type="string",
     *             example="12.99"
     *         ),
     *         @SWG\Property(
     *             property="expiredAt",
     *             type="string",
     *             example="2019-07-29 21:30:34"
     *         )
     *     )
     * ),
     * @SWG\Tag(name="Drug")
     * @ParamConverter("drug", class="App\Entity\Drug")
     * @Entity("pharmacy", expr="repository.find(pharmacy_id)")
     * @param ErrorService $errorService
     * @param Pharmacy $pharmacy
     * @param Drug $drug
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function update(Request $request, Pharmacy $pharmacy = null, Drug $drug = null, ErrorService $errorService)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $groups = ['drug', 'update'];
        $context->setGroups($groups);

        if (empty($drug) || empty($pharmacy)) {
            throw new HttpException(Response::HTTP_NOT_FOUND,'Resource not found');
        }

        try {
            $form = $this->createForm(DrugType::class, $drug);
            $data = json_decode($request->getContent(),true);
            $form->submit($data);

            if ($form->isSubmitted() && $form->isValid()) {
                $drug->setPharmacy($pharmacy);
                $this->drugManager->save($drug);
            } else {
                $errors = $errorService->getErrorsFromForm($form);

                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch(HttpException $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }

        $view = $this->view($drug, $responseCode);
        $view->setContext($context);

        return $view;
    }


    /**
     * @Rest\Delete("/drugs/{id}", name="api_drug_delete", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Delete a drug"
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid username/password":{
     *              "message": "Invalid credentials."
     *          },
     *          "Invalid customer ref/scope":{
     *              "message": "Access Denied"
     *          },
     *     }
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @SWG\Response(
     *     response=204,
     *     description="Request success but no content on response",
     * ),
     * @SWG\Tag(name="Drug")
     * @ParamConverter("drug", class="App\Entity\Drug")
     * @param Drug $drug
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function remove(Drug $drug)
    {
        $context = new Context();
        $groups = ['drug', 'pharmacy'];
        $context->setGroups($groups);
        if (empty($drug)) {
            throw new HttpException(Response::HTTP_NOT_FOUND,'Resource not found');
        }

        try {
            $this->drugManager->remove($drug);
            $view = $this->view($drug, Response::HTTP_NO_CONTENT);
            $view->setContext($context);
            return $view;
        }catch(HttpException $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
            echo $ex->getMessage();
        }

        $view = $this->view($drug, $responseCode);
        $view->setContext($context);

        return $view;
    }
}
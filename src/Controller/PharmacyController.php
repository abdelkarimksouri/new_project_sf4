<?php

namespace App\Controller;

use App\Form\PharmacyType;
use App\Manager\PharmacyManager;
use App\Repository\PharmacyRepository;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use App\Entity\Pharmacy;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Service\ErrorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PharmacyController extends AbstractFOSRestController
{
    /**
     * @var PharmacyManager
     */
    protected $pharmacyManager;

    /**
     * PharmacyController constructor.
     * @param PharmacyManager $pm
     */
    public function __construct(PharmacyManager $pm)
    {
        $this->pharmacyManager = $pm;

    }

    /**
     * @Rest\Get("/pharmacies", name="api_pharmacy_list")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return list of phamacies near of my home",
     *     @SWG\Items(ref=@Model(type=Pharmacy::class, groups={"pharmacy"}))
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

     * @Rest\QueryParam(name="criteria", strict=false,   nullable=false)
     * @Rest\QueryParam(name="limit", strict=false,  nullable=true)
     * @Rest\QueryParam(name="offset", strict=false, nullable=true)
     * @SWG\Tag(name="Pharmacy")
     * @param ParamFetcher $paramFetcher
     * @param PharmacyRepository $pharmacyRepository
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function list(ParamFetcher $paramFetcher, PharmacyRepository $pharmacyRepository)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $groups = ['pharmacy'];
        $context->setGroups($groups);
        $limit = $paramFetcher->get('limit') ?? $this->getParameter('defaultLimit');
        $offset = $paramFetcher->get('offset') ?? $this->getParameter('defaultOffset');
        $pharmacy = $this->pharmacyManager->list($paramFetcher->get('criteria') , $limit, $offset, $pharmacyRepository);
//        dump($pharmacy[0]->getLogo());
//        die;
        $response = [
            "totalItems" => count($pharmacy),
            "items" => $pharmacy
        ];

        $view = $this->view($response, $responseCode);
        $view->setContext($context);

        return $view;
    }

    /**
     * @Rest\Post("/pharmacies", name="api_pharmacy_create")
     * @SWG\Response(
     *     response=200,
     *     description="Create a pharmacy"
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
     *             property="generatedName",
     *             type="string",
     *             example="pharmacy belleville"
     *         ),
     *         @SWG\Property(
     *             property="isActive",
     *             type="boolean",
     *             example=true
     *         ),
     *         @SWG\Property(
     *             property="isNight",
     *             type="boolean",
     *             example=true
     *         ),
     *         @SWG\Property(
     *             property="isHoliday",
     *             type="boolean",
     *             example=false
     *         ),
     *        @SWG\Property(
     *             property="user",
     *             type="object",
     *             example={
     *                 "email": "abdel@gmail.com",
     *                 "password": "myfarma",
     *                  "roles": {
     *                              "ROLE_USER",
     *                              "ROLE_ADMIN"
     *                           }
     *                  },
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="key", type="string"),
     *                 @SWG\Property(property="value", type="string")
     *             )
     *        ),
     *         @SWG\Property(
     *              property="logo",
     *              type="object",
     *              @SWG\Items(
     *                 type="object"
     *             )
     *          ),
     *         @SWG\Property(
     *             property="address",
     *             type="object",
     *             example={
     *                 "streetNumber": "32",
     *                 "streetName": "rue dufrenoy",
     *                 "streetComplementary": "appartement B2",
     *                 "city": "Paris",
     *                 "zipCode": "75116",
     *                 "country": "1"
     *             },
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="key", type="string"),
     *                 @SWG\Property(property="value", type="string")
     *             )
     *        )
     *     )
     * ),
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer TOKEN",
     *     description="Bearer token",
     * )
     * @SWG\Tag(name="Pharmacy")
     * @param ErrorService $errorService
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function create(Request $request, ErrorService $errorService, UserPasswordEncoderInterface $encoder)
    {
        $responseCode = Response::HTTP_OK;
        $pharmacy = new Pharmacy();
        $context = new Context();
        $groups = ['pharmacy', 'address'];
        $context->setGroups($groups);
        try {
            $form = $this->createForm(PharmacyType::class, $pharmacy);
            $data = json_decode($request->getContent(),true);
            $form->submit($data);
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $pharmacy->getUser();
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $this->pharmacyManager->save($pharmacy);
            } else {
                $errors = $errorService->getErrorsFromForm($form);

                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch(HttpException $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }

        $view = $this->view($pharmacy, $responseCode);
        $view->setContext($context);

        return $view;
    }

    /**
     * @Rest\Put("/pharmacies/{id}", name="api_pharmacy_update", requirements={"id"="\d+"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a pharmacy"
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
     *             property="generatedName",
     *             type="string",
     *             example="MyFarmaName"
     *         ),
     *         @SWG\Property(
     *             property="isActive",
     *             type="boolean",
     *             example="true"
     *         ),
     *         @SWG\Property(
     *             property="isNight",
     *             type="boolean",
     *             example="true"
     *         ),
     *         @SWG\Property(
     *             property="isHoliday",
     *             type="boolean",
     *             example="false"
     *         ),
     *        @SWG\Property(
     *             property="user",
     *             type="object",
     *             example={
     *                 "email": "abdel@gmail.com",
     *                 "password": "myfarma",
     *                  "roles": {
     *                              "ROLE_USER",
     *                              "ROLE_ADMIN"
     *                           }
     *                  },
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="key", type="string"),
     *                 @SWG\Property(property="value", type="string")
     *             )
     *        ),
     *       @SWG\Property(
     *             property="address",
     *             type="object",
     *             example={
     *                 "streetNumber": "32",
     *                 "streetName": "rue dufrenoy",
     *                 "streetComplementary": "appartement B2",
     *                 "city": "Paris",
     *                 "zipCode": "75116",
     *                 "country": "1"
     *             },
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="key", type="string"),
     *                 @SWG\Property(property="value", type="string")
     *             )
     *        )
     *     )
     * ),
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer TOKEN",
     *     description="Bearer token",
     * )
     * @SWG\Tag(name="Pharmacy")
     * @ParamConverter("pharmacy", class="App\Entity\Pharmacy")
     * @param ErrorService $errorService
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function update(Request $request, Pharmacy $pharmacy, ErrorService $errorService)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $groups = ['pharmacy', 'address'];
        $context->setGroups($groups);

        try {
            $form = $this->createForm(PharmacyType::class, $pharmacy);
            $data = json_decode($request->getContent(),true);
            $form->submit($data);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->pharmacyManager->save($pharmacy);
            } else {
                $errors = $errorService->getErrorsFromForm($form);

                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch(HttpException $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }

        $view = $this->view($pharmacy, $responseCode);
        $view->setContext($context);

        return $view;
    }

    /**
     * @Rest\Delete("/pharmacy/{id}", name="api_pharmacy_delete", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Delete a pharmacy"
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
     * @SWG\Tag(name="Pharmacy")
     * @ParamConverter("pharmacy", class="App\Entity\Pharmacy")
     * @param Pharmacy $pharmacy
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function remove(Pharmacy $pharmacy)
    {
        $context = new Context();
        $groups = ['drug', 'pharmacy'];
        $context->setGroups($groups);
        if (empty($pharmacy)) {
            throw new HttpException(Response::HTTP_NOT_FOUND,'Resource not found');
        }

        try {
            $this->pharmacyManager->remove($pharmacy);
            $view = $this->view($pharmacy, Response::HTTP_NO_CONTENT);
            $view->setContext($context);
            return $view;
        }catch(HttpException $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
            echo $ex->getMessage();
        }

        $view = $this->view($pharmacy, $responseCode);
        $view->setContext($context);

        return $view;
    }

}

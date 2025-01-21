<?php

namespace App\Controller;

use App\Data\ArchiveData;
use App\DTO\ArchiveSearchDTO;
use App\Entity\Archive;
use App\Form\ArchiveSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', condition: "context.getMethod() in ['GET']")]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/{archive}', name: 'app_archive')]
    public function archive(#[ValueResolver('archive')]string $archive, Request $request): Response
    {
        $archiveData = new ArchiveData();
        $session = $request->getSession();

        // If archiv dates in archiveData does not exist, archive does not exist
        if(!isset($archiveData->dates[$archive])){
            throw $this->createNotFoundException();
        }

        // Not found exception was not thrown, archiv exist and it will assign archiv to session data
        $session->set('archive', $archive);

        // ArchiveSearchDTO to create form
        $archiveSearchDTO = new ArchiveSearchDTO($archiveData->dates[$archive]['min'], $archiveData->dates[$archive]['max']);
        $form = $this->createForm(ArchiveSearchType::class, $archiveSearchDTO, ['attr' => ['dateArea' => $archiveData->dates[$archive]]]);

        return $this->render('archive/index.html.twig', [
            'controller_name' => 'ArchiveController',
            'archive' => $archive,
            'form' => $form
        ]);
    }

    #[Route('/archive/search', name: 'app_archive_search', methods: ['POST'], priority: 2)]
    public function requestSearch(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        set_time_limit(180);
        session_write_close();
        
        // response data structure
        $responseData = [
            'search' => '',
            'start_date' => '',
            'end_date' => '',
            'data' => [
                'max_count_reached' => [
                    'status' => false,
                    'end_date' => ''
                ],
                'max_count' => 250,
                'count' => 0,
                'list' => []
            ],
            'errors' => [
                'count' => 0,
                'list' => []
            ]
        ];
        //Get current used archive name
        $session = $request->getSession();
        $archive = $session->get('archive', '');
        
        // Get CSRF Token and validate manually. If CSRF token is invalid assign it as error to resonseData
        $submitToken = $request->request->all()['archive_search']['form_token'] ?? $request->request->all()['archive_search']['form_token'] ?? '';
        if (!$this->isCsrfTokenValid('archive_search_token', $submitToken)) {
            $responseData['errors']['list'][] = "Ungültiges Formular Token. Bitte Laden sie die Seite erneut.";
        }
        
        // If archiv dates in archiveData does not exist, archive does not exist
        // return error response data as json, if archive dates does not exist
        $archiveData = new ArchiveData();
        if(!isset($archiveData->dates[$archive])){
            $responseData['errors']['list'][] = "Archiv nicht gefunden.";
            $responseData['errors']['count'] = count($responseData['errors']['list']);
            return new JsonResponse($responseData);
        }

        // Object archiveSearchDTO and archive dates is used to create Archiv Search Form
        $archiveSearchDTO = new ArchiveSearchDTO($archiveData->dates[$archive]['min'], $archiveData->dates[$archive]['max']);
        $archiveDateArea = $archiveData->dates[$archive];
        $form = $this->createForm(ArchiveSearchType::class, $archiveSearchDTO, ['attr' => ['dateArea' => $archiveDateArea]]);
        $form->handleRequest($request);


        // Form validation with ArchiveSearchDTO Object
        $formErrors = $validator->validate($archiveSearchDTO);
        if(count($formErrors) > 0) {
            for($i = 0; $i < count($formErrors); $i++){
                $responseData['errors']['list'][] = $formErrors[$i]->getMessage();
            }
        }
        
        // swap startDate and endDate if startDate greater than endDate
        if($archiveSearchDTO->start_date > $archiveSearchDTO->end_date){
            $tempStartDate = $archiveSearchDTO->start_date;
            $archiveSearchDTO->start_date = $archiveSearchDTO->end_date;
            $archiveSearchDTO->end_date = $tempStartDate;
        }        

        // Return detected errors as json format
        if($responseData['errors']['list']){
            $responseData['errors']['count'] = count($responseData['errors']['list']);
            return new JsonResponse($responseData);
        }

        // Get repository by archiv entity
        $archiveRepository = $entityManager->getRepository(Archive::class);
        // Get result by ArchivSarchDTO Object and archiv name
        $result = $archiveRepository->findArchiveWords($form->getData(), $archive);

        // Return error responseData as json if repository request was failed
        if($result === false){
            $responseData['errors']['list'][] = "Suche fehlgeschlagen.";
            $responseData['errors']['count'] = count($responseData['errors']['list']);
            return new JsonResponse($responseData);
        }
        
        // Creates responseData when repositoy request was successful and returns as jsons format
        $responseData['data']['list'] = $result;
        $responseData['data']['count'] = count($responseData['data']['list']);
        $responseData['search'] = htmlentities($archiveSearchDTO->search, ENT_QUOTES);
        $responseData['start_date'] = $archiveSearchDTO->start_date->format('Y-m-d');
        $responseData['end_date'] = $archiveSearchDTO->end_date->format('Y-m-d');

        if($responseData['data']['max_count'] === $responseData['data']['count']){
            $responseData['data']['max_count_reached']['status'] = true;
            $responseData['data']['max_count_reached']['end_date'] = $result[array_key_last($result)]['date'];
        }

        return new JsonResponse($responseData);
    }
}

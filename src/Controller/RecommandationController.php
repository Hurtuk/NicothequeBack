<?php


namespace App\Controller;


use App\Entity\Movie;
use App\Entity\Recommandation;
use App\Entity\User;
use App\Utils\Enums\Recommandation\RecommandationState;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecommandationController extends AbstractController
{
    /**
     * @Route("/recommandations/new", name="app_recommandations_new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request)
    {
        $recommandation = new Recommandation();

        $form = $this->createFormBuilder($recommandation)
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name'
            ])
            ->add('movie', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => 'title'
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Vu' => RecommandationState::SEEN,
                    'A voir' => RecommandationState::TO_SEE,
                    'Ignoré' => RecommandationState::IGNORED
                ]
            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recommandation = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recommandation);
            $entityManager->flush();

            $this->addFlash('success', 'La recommandation a été créée !');

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('recommandations/new_recommandation.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/recommandations/new/{userId}", name="app_recommandations_new_user")
     */
    public function newForUser(Request $request)
    {
        $userId = $request->get('userId');

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user =  $repository->find($userId);

        if(!empty($user))
        {
            $recommandation = new Recommandation();

            $form = $this->createFormBuilder($recommandation)
                ->add('movie', EntityType::class, [
                    'class' => Movie::class,
                    'choice_label' => 'title'
                ])
                ->add('state', ChoiceType::class, [
                        'choices' => [
                            'Vu' => RecommandationState::SEEN,
                            'A voir' => RecommandationState::TO_SEE,
                            'Ignoré' => RecommandationState::IGNORED
                        ]
                    ])
                ->add('save', SubmitType::class)
                ->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $entityManagaer = $this->getDoctrine()->getManager();
                $recommandation->setUser($user);

                $entityManagaer->persist($recommandation);
                $entityManagaer->flush();

                $this->addFlash('success', 'La recommandation a été créée !');

                return $this->redirectToRoute('app_recommandation_user', ['userId' => $user->getId()]);
            }

            return $this->render('recommandations/new_recommandation_user.html.twig', ['form' => $form->createView(), 'user' => $user]);
        }
    }

    /**
     * @Route("/recommendations/get/{userId}", name="app_recommandation_user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getForUser(Request $request)
    {
        $repositoryRecommandation = $this->getDoctrine()->getRepository(Recommandation::class);
        $repositoryUser = $this->getDoctrine()->getRepository(User::class);

        $userId = $request->get('userId');

        $user = $repositoryUser->find($userId);

        if (!empty($user)) {
            $recommandations = $repositoryRecommandation->findBy(['user' => $user]);

            return $this->render('recommandations/user_recommandations.html.twig', ['recommandations' => $recommandations, 'user' => $user]);
        }
    }

    /**
     * @Route("/recommandations/update/{id}", name="app_recommandation_update")
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Recommandation::class);
        $entityManager = $this->getDoctrine()->getManager();

        $recommandationId = $request->get('id');
        $recommandation = $repository->find($recommandationId);

        $form = $this->createFormBuilder($recommandation)
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Vu' => RecommandationState::SEEN,
                    'A voir' => RecommandationState::TO_SEE,
                    'Ignoré' => RecommandationState::IGNORED
                ]
            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $recommandation = $form->getData();
            $entityManager->persist($recommandation);
            $entityManager->flush();

            $this->addFlash('success', 'Le statut a été mis à jour !');

            return $this->redirectToRoute('app_users_index');
        }

        return $this->render('recommandations/update_recommandation.html.twig', ['recommandation' => $recommandation, 'form' => $form->createView()]);
    }
}
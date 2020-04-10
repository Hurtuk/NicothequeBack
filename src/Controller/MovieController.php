<?php


namespace App\Controller;


use App\Entity\Movie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movies", name="app_movies_index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repository->findAll();

        return $this->render('movies/index.html.twig', ['movies' => $movies]);
    }

    /**
     * @Route("/movies/new", name="app_movies_new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request)
    {
        $movie = new Movie();
        $form = $this->createFormBuilder($movie)
            ->add('title', TextType::class)
            ->add('titleFr', TextType::class)
            ->add('year', TextType::class)
            ->add('categories', TextType::class)
            ->add('directors', TextType::class)
            ->add('actors', TextType::class)
            ->add('overview', TextareaType::class)
            ->add('mark', NumberType::class)
            ->add('length', NumberType::class)
            ->add('owned', CheckboxType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movie);
            $entityManager->flush();

            $this->addFlash('success', 'Le film a été ajouté !');

            return $this->redirectToRoute('app_movies_index');
        }

        return $this->render('movies/new_movie.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/movies/details/{id}", name="app_movies_details")
     */
    public function details(Request $request)
    {
        $id = $request->get('id');
        $repository = $this->getDoctrine()->getRepository(Movie::class);

        $movie = $repository->find($id);

        if(!empty($movie))
        {
            return $this->render('movies/details_movie.html.twig', ['movie' => $movie]);
        }
    }
}
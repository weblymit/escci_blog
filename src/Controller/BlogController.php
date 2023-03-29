<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
	#[Route('/', name: 'app_home')]
	public function index(PostRepository $repo): Response
	{
		//1- je recupere tous les posts
		$posts = $repo->findAll();
		// dd($posts);
		// 2- j'envoie la data la vue
		return $this->render('blog/index.html.twig', compact('posts'));
	}

	#[Route('/post/{id}', name: 'app_show')]
	public function show($id, PostRepository $repo): Response
	{
		// je recupere le poste avec l'id
		$post = $repo->find($id);
		// je passe à la vue
		return $this->render('blog/show.html.twig', compact('post'));
	}

	#[Route('/post/delete/{id}', name: 'app_delete', methods: ['GET', 'DELETE'])]
	public function delete($id, PostRepository $repo, EntityManagerInterface $em): Response
	{
		// 1- je recupere le poste avec l'id
		$post = $repo->find($id);
		// 2 - supprimer post
		$em->remove($post);
		// 3- Vide la chasse
		$em->flush();
		// 4- redirection vers la page d'accueil
		return $this->redirectToRoute('app_home');
	}

	#[Route('/create', name: 'app_create', methods: ['GET', 'POST'])]
	public function create(Request $request, EntityManagerInterface $em): Response
	{
		// 1- create new objet
		$post = new Post;
		// 2- create form
		$form = $this->createForm(PostFormType::class, $post);
		// $showForm = $form->createView();
		// Ajouter post en BDD
		// 1- recuperer data de mes input
		$form->handleRequest($request);
		// 2- soumission du formulaire
		if ($form->isSubmitted() && $form->isValid()) {
			// stock les data du user
			$newPost = $form->getData();
			// verifie si une image a été choisi
			$imagePath = $form->get('url_img')->getData();
			if ($imagePath) {
				// donne a l'image un new name
				$newFileName = uniqid() . '.' . $imagePath->guessExtension();
				// on deplace l'image dans le dossier public/upload
				try {
					// deplacer l'image dans le dossier public/upload
					$imagePath->move(
						$this->getParameter('kernel.project_dir') . '/public/upload',
						$newFileName
					);
				} catch (FileException $e) {
					return new Response($e->getMessage());
				}
				// apers avoir deplacer user image
				// j'envoie l'url en BDD en concatenant le dossier /upload + newfilename
				$newPost->setUrlImg('/upload/' . $newFileName);
			}
			// set le champ created_at avec la date de l'envoi du formulaire
			$newPost->setCreatedAt(new DateTimeImmutable());
			// persiste les data de user entrer
			$em->persist($newPost);
			$em->flush();
			// redirection
			return $this->redirectToRoute('app_home');
		}

		// 3 envoie form dans la vue
		return $this->render('blog/create.html.twig', [
			'showForm' => $form->createView()
		]);
	}
}

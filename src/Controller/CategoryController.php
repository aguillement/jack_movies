<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    /**
     * @Route("/admin/category/add", name="addCategory")
     */
    public function addCategory(Request $request)
    {
        $category = new Category();

        $form = $this->CreateForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($category);

            $entityManager->flush();

            $this->addFlash('success', 'The category has been created!');
            return $this->redirectToRoute('categories');
        }


        return $this->render('category/add.html.twig', [
            'addCategoryForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/categories", name="categories")
     */
    public function categories()
    {

        $rep = $this->getDoctrine()->getRepository(Category::class);
        $categories = $rep->findAll();

        return $this->render('category/categories.html.twig', [
            'categories' => $categories
        ]);
    }
}

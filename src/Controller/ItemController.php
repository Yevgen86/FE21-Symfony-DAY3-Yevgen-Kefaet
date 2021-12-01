<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Item;
// use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ItemController extends AbstractController
{
    #[Route('/items', name: 'items')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Item::class);

        $items = $repository->findAll();

        if(!$items) {
            
            throw $this->createNotFoundException(
                'No Items in Database found!'
            );
        } else {

            return $this->render('item/index.html.twig', [
                'items' => $items,
            ]);
        }

    }

    #[Route('/create', name: 'item_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
         // Here we create an object from the class that we made
         $item = new Item;

         /* Here we will build a form using createFormBuilder and inside this function we will put our object and then we write add then we select the input type then an array to add an attribute that we want in our input field */
         $form = $this->createFormBuilder($item)
         ->add('name', TextType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
         ->add('description', TextareaType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
         ->add('price', NumberType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
         ->add('save', SubmitType::class, array('label'=> 'Create item', 'attr' => array('class'=> 'btn-primary', 'style'=>'margin-bottom:15px')))
         ->getForm();
 
         $form->handleRequest($request);
        
         /* Here we have an if statement, if we click submit and if  the form is valid we will take the values from the form and we will save them in the new variables */
         if($form->isSubmitted() && $form->isValid()){
             //fetching data
             // taking the data from the inputs by the name of the inputs then getData() function
             $name = $form['name']->getData();
             $description = $form['description']->getData();
             $price = $form['price']->getData();
             // $image = $form['image']->getData();
             /* these functions we bring from our entities, every column have a set function and we put the value that we get from the form */
             $item->setName($name);
             $item->setDescription($description);
             $item->setPrice($price);
             // $item->setImage($image);
            
             $em = $doctrine->getManager();   
             $em->persist($item);
             $em->flush();
 
             $this->addFlash('notice','Item Added');
             return $this->redirectToRoute('items');
         }

         return $this->render('item/create.html.twig', array('form' => $form->createView()));      
    }

    #[Route('/edit/{id}', name: 'item_edit')]
    public function edit($id, Request $request, ManagerRegistry $doctrine): Response
    {
        $item = $doctrine->getManager()->getRepository(Item::class)->find($id);

        $form = $this->createFormBuilder($item)
        ->add('name', TextType::class, array('attr'=>array('class'=>'form-control mb-3')))
        ->add('description', TextareaType::class, array('attr'=>array('class'=>'form-control mb-3', 'id'=>'textArea')))
        ->add('price', NumberType::class, array('attr'=>array('class'=>'form-control mb-3')))
        
        ->add('save', SubmitType::class, array('attr'=>array('class'=>'btn btn-success mb-3', 'label'=>'Update item')))->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $price = $form['price']->getData();

            $item->setName($name);
            $item->setDescription($description);
            $item->setPrice($price);

            $em = $doctrine->getManager();
            $em->persist($item);
            $em->flush();

            $this->addFlash('notice', 'item Editted');
            return $this->redirectToRoute('items');
        }
        return $this->render('item/edit.html.twig', array('form'=> $form->createView()));
    }

    #[Route('/item/{id}', name: 'item_details')]
    public function show($id, ManagerRegistry $doctrine): Response
    {
        $item = $doctrine->getManager()->getRepository(Item::class)->find($id);
        
        return $this->render('item/details.html.twig', array('item'=>$item));
    }

    #[Route("/delete/{id}", name:"item_delete")]
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $item = $doctrine->getManager()->getRepository(Item::class)->find($id);
        $em = $doctrine->getManager();
        $em->remove($item);
        $em->flush();

        $this->addFlash('notice', 'Item Removed');

        return $this->redirectToRoute('items');
    }
   
}

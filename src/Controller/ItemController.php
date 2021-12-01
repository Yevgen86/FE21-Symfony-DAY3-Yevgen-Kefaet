<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

// Entities
use App\Entity\Item;
use App\Entity\Status;

// Services
use App\Service\FileUploader;

// FORM Components
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ItemController extends AbstractController
{
    #[Route('/', name: 'items')]
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
    public function create(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
         // Here we create an object from the class that we made
         $item = new Item;

         /* Here we will build a form using createFormBuilder and inside this function we will put our object and then we write add then we select the input type then an array to add an attribute that we want in our input field */
         $form = $this->createFormBuilder($item)
         ->add('name', TextType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
         ->add('description', TextareaType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
         ->add('price', NumberType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
         ->add('fk_status', EntityType::class, [
             'class'=> Status::class,
             'choice_label' => 'name',
         ])
         ->add('pictureUrl', FileType::class, [
            'label' => 'Upload Picture',
            //unmapped means that is not associated to any entity property
            'mapped' => false,
            //not mandatory to have a file
            'required' => false,

            //in the associated entity, so you can use the PHP constraint classes as validators
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/png',
                        'image/jpeg',
                        'image/jpg',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image file' ,
                    ])
                ],
            ])
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
             
             /* these functions we bring from our entities, every column have a set function and we put the value that we get from the form */
             $item->setName($name);
             $item->setDescription($description);
             $item->setPrice($price);
             // $item->setImage($image);
             
             $status = $doctrine->getRepository(Status::class)->find(1);
             $item->setFkStatus($status);
             //pictureUrl is the name given to the input field
             $pictureFile = $form->get('pictureUrl')->getData();
    
            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $item->setImage($pictureFileName);
            }
            //if there was any file sent via the form the file uploader service is triggered and $pictureFileName has the new name from the file and set to the object
             
             $em = $doctrine->getManager();   
             $em->persist($item);
             $em->flush();
             
             $this->addFlash('notice','Item Added');
             return $this->redirectToRoute('items');
         }

         return $this->render('item/create.html.twig', array('form' => $form->createView()));      
    }

    #[Route('/edit/{id}', name: 'item_edit')]
    public function edit($id, Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
        $item = $doctrine->getManager()->getRepository(Item::class)->find($id);

        $form = $this->createFormBuilder($item)
        ->add('name', TextType::class, array('attr'=>array('class'=>'form-control mb-3')))
        ->add('description', TextareaType::class, array('attr'=>array('class'=>'form-control mb-3', 'id'=>'textArea')))
        ->add('price', NumberType::class, array('attr'=>array('class'=>'form-control mb-3')))
        ->add('fk_status', EntityType::class, [
            'class'=> Status::class,
            'choice_label' => 'name',
        ])
        ->add('pictureUrl', FileType::class, [
           'label' => 'Upload Picture',
           //unmapped means that is not associated to any entity property
           'mapped' => false,
           //not mandatory to have a file
           'required' => false,

           //in the associated entity, so you can use the PHP constraint classes as validators
           'constraints' => [
               new File([
                   'maxSize' => '1024k',
                   'mimeTypes' => [
                       'image/png',
                       'image/jpeg',
                       'image/jpg',
                   ],
                   'mimeTypesMessage' => 'Please upload a valid image file' ,
                ])
            ],
        ])
        ->add('save', SubmitType::class, array('attr'=>array('class'=>'btn btn-success mb-3', 'label'=>'Update item')))->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $price = $form['price']->getData();
            $status = $form->get('fk_status')->getData();
            $pictureFile = $form->get('pictureUrl')->getData();
            
            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $item->setImage($pictureFileName);
            }
            
            $item->setName($name);
            $item->setDescription($description);
            $item->setPrice($price);
            $item->setFkStatus($status);
            
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

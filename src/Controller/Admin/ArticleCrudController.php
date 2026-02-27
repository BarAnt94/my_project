<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Dom\Text;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EntityField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('content'),
            TextField::new('price'),
            DateTimeField::new('createdAt','Date de création'),
            ImageField::new('photo')
            ->setBasePath('img_articles/')
            ->setUploadDir('public/img_articles/')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
            AssociationField::new('category')
                ->setCrudController(CategoryCrudController::class)
                ->setFormTypeOption('choice_label', 'name'),
        ];
    }

}

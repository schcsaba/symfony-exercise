<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\Translation\TranslatableMessage;

class CompanyCrudController extends AbstractCrudController
{
    private ManagerRegistry $doctrine;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    public static function getEntityFqcn(): string
    {
        return Company::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('companyName', new TranslatableMessage('easyadmin.company.name')),
            AssociationField::new('user', new TranslatableMessage('easyadmin.user'))
                ->setPermission('ROLE_ADMIN'),
            ImageField::new('companyLogo', new TranslatableMessage('easyadmin.company.logo'))
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->hideWhenCreating()
                ->hideOnIndex(),
            ImageField::new('companyLogo', new TranslatableMessage('easyadmin.company.logo'))
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(true)
                ->hideWhenUpdating(),
            ColorField::new('companyLogoBackgroundColor', new TranslatableMessage('easyadmin.company.logo.backgroundcolor')),
            TextField::new('companyTown', new TranslatableMessage('easyadmin.company.town')),
            UrlField::new('companyWebsite', new TranslatableMessage('easyadmin.company.website')),
            TextField::new('contactLastname', new TranslatableMessage('easyadmin.company.contact.lastname')),
            TextField::new('contactFirstname', new TranslatableMessage('easyadmin.company.contact.firstname')),
            EmailField::new('contactEmail', new TranslatableMessage('easyadmin.company.contact.email')),
            TelephoneField::new('contactPhone', new TranslatableMessage('easyadmin.company.contact.phone'))
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return parent::createEntity($entityFqcn);
        }

        $company = new Company();
        $user = $this->getUser();
        if ($user instanceof User) {
            $company->setUser($user);
        }

        return $company;
    }

    public function configureActions(Actions $actions): Actions
    {
        $company = $this->doctrine->getRepository(Company::class)->findOneByUser($this->getUser());
        if ($company) {
            $actions->setPermission(Action::NEW, 'ROLE_ADMIN');
        }

        $actions->setPermission(Action::SAVE_AND_ADD_ANOTHER, 'ROLE_ADMIN');

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $singular = $this->isGranted('ROLE_ADMIN') ? 'easyadmin.company' : 'easyadmin.mycompany';
        $plural = $this->isGranted('ROLE_ADMIN') ? 'easyadmin.companies' : 'easyadmin.mycompany';
        return $crud
            ->setEntityLabelInSingular($singular)
            ->setEntityLabelInPlural($plural)
            ->setEntityPermission('ADMIN_COMPANY_EDIT')
            ->setSearchFields($this->isGranted('ROLE_ADMIN') ? [
                'companyName',
                'user.username',
                'companyTown',
                'companyWebsite',
                'contactLastname',
                'contactFirstname',
                'contactEmail',
                'contactPhone'
            ] : null);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->isGranted('ROLE_ADMIN')) {
            return $queryBuilder;
        }

        $queryBuilder
            ->andWhere('entity.user = :user')
            ->setParameter('user', $this->getUser());

        return $queryBuilder;
    }
}

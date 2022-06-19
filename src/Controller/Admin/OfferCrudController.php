<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\Offer;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Translation\TranslatableMessage;

class OfferCrudController extends AbstractCrudController
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
        return Offer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab(new TranslatableMessage('easyadmin.offer.general')),
            AssociationField::new('company', new TranslatableMessage('easyadmin.company'))
                ->setPermission('ROLE_ADMIN'),
            TextField::new('title', new TranslatableMessage('easyadmin.offer.title')),
            Field::new('slug')
                ->setDisabled()
                ->hideOnForm(),
            ChoiceField::new('typeOfContract', new TranslatableMessage('easyadmin.offer.typeofcontract'))
                ->renderExpanded()
                ->setChoices([
                    'Full Time' => 'Full Time',
                    'Part Time' => 'Part Time',
                    'Freelance' => 'Freelance'
                ]),
            TextareaField::new('description', new TranslatableMessage('easyadmin.offer.description'))
                ->hideOnIndex(),
            Field::new('createdAt')
                ->setDisabled()
                ->hideOnForm(),
            Field::new('updatedAt')
                ->setDisabled()
                ->hideOnForm(),
            FormField::addTab(new TranslatableMessage('easyadmin.offer.profile')),
            TextareaField::new('profileDescription', new TranslatableMessage('easyadmin.offer.profile.description'))
                ->hideOnIndex(),
            ArrayField::new('competences', new TranslatableMessage('easyadmin.offer.competences'))
                ->hideOnIndex(),
            FormField::addTab(new TranslatableMessage('easyadmin.offer.position')),
            TextareaField::new('positionDescription', new TranslatableMessage('easyadmin.offer.position.description'))
                ->hideOnIndex(),
            ArrayField::new('positionMissions', new TranslatableMessage('easyadmin.offer.position.missions'))
                ->hideOnIndex(),
            ArrayField::new('candidates', new TranslatableMessage('easyadmin.candidates'))
                ->onlyOnDetail()
                ->setTemplatePath('admin/field/offer/detail/candidates.html.twig'),
            AssociationField::new('candidates', new TranslatableMessage('easyadmin.candidates'))
                ->onlyOnIndex()
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return parent::createEntity($entityFqcn);
        }

        $offer = new Offer();
        $user = $this->getUser();
        $company = $user->getCompany();
        if ($company instanceof Company) {
            $offer->setCompany($company);
        }

        return $offer;
    }

    public function configureActions(Actions $actions): Actions
    {
        $company = $this->doctrine->getRepository(Company::class)->findOneByUser($this->getUser());
        if (!$company && !$this->isGranted('ROLE_ADMIN')) {
            $actions->disable(Action::NEW);
        }

        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $plural = $this->isGranted('ROLE_ADMIN') ? 'easyadmin.offers' : 'easyadmin.myoffers';
        return $crud
            ->setEntityLabelInSingular('easyadmin.offer')
            ->setEntityLabelInPlural($plural)
            ->setEntityPermission('ADMIN_OFFER_EDIT');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->isGranted('ROLE_ADMIN')) {
            return $queryBuilder;
        }

        $queryBuilder
            ->andWhere('entity.company = :company')
            ->setParameter('company', $this->getUser()->getCompany());

        return $queryBuilder;
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use App\Entity\Company;
use App\Entity\Offer;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Translation\TranslatableMessage;

class CandidateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Candidate::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname', new TranslatableMessage('easyadmin.candidate.firstname')),
            TextField::new('lastname', new TranslatableMessage('easyadmin.candidate.lastname')),
            TelephoneField::new('phone', new TranslatableMessage('easyadmin.candidate.phone')),
            EmailField::new('email', new TranslatableMessage('easyadmin.candidate.email')),
            TextField::new('cv', new TranslatableMessage('easyadmin.candidate.cv')),
            AssociationField::new('offer', new TranslatableMessage('easyadmin.candidate.offer'))
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->disable(Action::NEW, Action::EDIT);
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('easyadmin.candidate')
            ->setEntityLabelInPlural('easyadmin.candidates')
            ->setEntityPermission('ADMIN_CANDIDATE_VIEW');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->isGranted('ROLE_ADMIN')) {
            return $queryBuilder;
        }

        $queryBuilder
            ->join(Offer::class, 'o')
            ->andWhere('o.company = :company')
            ->setParameter('company', $this->getUser()->getCompany());

        return $queryBuilder;
    }
}

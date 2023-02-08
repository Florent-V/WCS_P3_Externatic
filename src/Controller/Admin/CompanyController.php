<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Form\AdminSearchType;
use App\Form\CompanySwitchType;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Service\CompanyTools;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'app_company_index', methods: ['GET'])]
    public function index(
        Request $request,
        CompanyRepository $companyRepository,
        PaginatorInterface $paginator
    ): Response {

        $form = $this->createForm(AdminSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $queryCompanies = $companyRepository->findCompany($data['search']);
        } else {
            $queryCompanies = $companyRepository->findCompany();
        }

        $companies = $paginator->paginate(
            $queryCompanies,
            $request->query->getInt('page', 1),
            10
        );


        return $this->renderForm('admin/company/index.html.twig', [
            'companies' => $companies,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompanyRepository $companyRepository): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->setIsActive(true);
            $companyRepository->save($company, true);

            return $this->redirectToRoute('admin_app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/company/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/switch', name: 'app_company_switch', methods: ['GET', 'POST'])]
    public function switch(
        Request $request,
        CompanyTools $switchCompany
    ): Response {

        $form = $this->createForm(CompanySwitchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $company = $data['company'];
            $consultant = $data['consultant'];
            $switchCompany->assign($company, $consultant);

            $this->addFlash('success', 'L\'entreprise ' . $company->getName() .
                ' a bien été affectée à ' . $consultant->getUser()->getFirstname() . ' ' .
            $consultant->getUser()->getLastName());

            return $this->redirectToRoute('admin_app_company_switch');
        }

        return $this->renderForm('admin/company/switch.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->save($company, true);

            return $this->redirectToRoute('admin_app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/disable', name: 'app_company_disable', methods: ['GET', 'POST'])]
    public function disable(
        Company $company,
        CompanyRepository $companyRepository,
        CompanyTools $companyTools
    ): Response {

        $companyTools->disable($company);
        if (!$company->isActive()) {
            $this->addFlash('success', 'L\'entreprise ' . $company->getName() . ' a bien été désactivée');
        }
        return $this->redirectToRoute('admin_app_company_index');
    }

    #[Route('/{id}/enable', name: 'app_company_enable', methods: ['GET', 'POST'])]
    public function enable(
        Company $company,
        CompanyRepository $companyRepository,
        CompanyTools $companyTools
    ): Response {

        $companyTools->enable($company);
        if ($company->isActive()) {
            $this->addFlash('success', 'L\'entreprise ' . $company->getName() . ' a bien été activée');
        }
        return $this->redirectToRoute('admin_app_company_index');
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $company->getId(), $request->request->get('_token'))) {
            try {
                $companyRepository->remove($company, true);
            } catch (Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }
        return $this->redirectToRoute('admin_app_company_index', [], Response::HTTP_SEE_OTHER);
    }
}

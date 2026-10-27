<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Schools;

class SchoolsController extends Controller
{
    private Schools $schools;
    public function __construct()
    {
        parent::__construct();
        if (!$this->checkRole('admin')) {
            $this->flash('error');
            $this->redirect('/dashboard');
        }
        $this->schools = new Schools();
    }


    public function index(?int $id = null): void
    {
        $school = null;
        if ($id !== null) {
            $school = $this->schools->find($id);
            if (!$school) {
                $this->flash('error');
                $this->redirect('/admin/schools');
            }
        }
        $data['schools'] = $this->schools->all();
        $data['school'] = $school;
        $data['title'] = 'Manage Schools';
        $this->view('admin/schools', $data);
    }

    public function edit(int $id): void
    {
        $this->index($id);
    }

    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/schools');
            return;
        }
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->flash('error');
            $this->redirect('/admin/schools');
            return;
        }

        $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
        $name = trim($this->toTitle($_POST['name']) ?? '');
        $address = trim($this->toUpper($_POST['address']) ?? '');
        $status = $_POST['status'] ?? 'active';

        if (empty($name) || empty($address)) {
            $this->flash('error');
            $this->redirect($id ? "/admin/schools/edit/{$id}" : '/admin/schools');
            return;
        }

        $existingSchool = $id ? $this->schools->find($id) : null;
        $logoPath = $existingSchool['logo_path'] ?? null;

        if (!empty($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadedPath = $this->handleFileUpload($_FILES['logo'], '/uploads/logos/');
            if ($uploadedPath !== null) {
                if (!empty($existingSchool['logo_path'])) {
                    $this->deleteFile($existingSchool['logo_path']);
                }
                $logoPath = $uploadedPath;
            } else {
                $this->redirect($id ? "/admin/schools/edit/{$id}" : "/admin/schools");
            }
        }

        $data = [
            'name' => $name,
            'address' => $address,
            'logo_path' => $logoPath,
            'status' => $status
        ];

        if ($id) {
            $saved = $this->schools->update($id, $data);
            $type = $saved ? 'success' : 'error';
        } else {
            $saved = $this->schools->create($data);
            $type = $saved ? 'success' : 'error';
        }

        $this->flash($type);
        $this->redirect('/admin/schools');
    }

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/schools');
            return;
        }

        $school = $this->schools->find($id);
        if ($school && !empty($school['logo_path'])) {
            $this->deleteFile($school['logo_path']);
        }
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            error_log('Invalid security token submitted while deleting a school.');
            $this->flash('error');
            $this->redirect('/admin/schools');
            return;
        }

        $deleted = $this->schools->delete($id);
        $this->flash($deleted ? 'success' : 'error');
        $this->redirect('/admin/schools');
    }
}

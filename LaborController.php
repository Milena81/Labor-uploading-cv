<?php

use Phalcon\Mvc\Model;
use Phalcon\Filter;
use Phalcon\Mvc\Model\Transaction\Manager;
use Phalcon\Db\Adapter\Pdo;

class LaborController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Labor');
        $this->auth = $this->session->get('auth');
        $this->filter = new Filter();
        parent::initialize();
    }

    public function updateAction($id)
    {
        $userLabor = Labor::findFirstById($id);
        $this->view->labor = $userLabor;

        if($this->request->isPost())
        {
            $this->db->begin();                     //transaction starting here

            /*fill the fields*/
            $username = $this->request->getPost('username');
            $position = $this->request->getPost('position');

            if($userLabor)
            {
                $userLabor->username = $username;
                $userLabor->position = $position;

                if(!$this->request->hasFiles() && !$userLabor->update())
                {
                    $this->db->rollback();

                    $errors = $userLabor->getMessages();

                    foreach ($errors as $error) {
                        $this->flash->error($error->getMessage());
                    }

                    return $this->response->redirect('labor/index');

                } else {

                    $updatedFiles = $this->request->getUploadedFiles();

                    foreach ($updatedFiles as $file)
                    {
                        $filename = $file->getName();
                        $filetype = $file->getType();
                        $filesize = $file->getSize();

                        if (!$file->isUploadedFile()){
                            continue;
                        }

                        $uploadFile = LaborCV::findFirstByUserId($userLabor->id);

                        $path = "cv/";
                        unlink($path . $uploadFile->filename);

                        if(!$uploadFile){
                            $uploadFile = new LaborCV;
                        }

                        //data from db table labor_cv
                        $uploadFile->user_id = $userLabor->id;
                        $uploadFile->filename = $filename;
                        $uploadFile->filetype = $filetype;
                        $uploadFile->filesize = $filesize;
                        $file->moveTo($path . $filename);

                        if(!$uploadFile->save())
                        {
                            $this->db->rollback();

                            $errors = $userLabor->getMessages();

                            foreach ($errors as $error)
                            {
                                $this->flashSession->error($error->getMessages());
                            }

                            return $this->response->redirect('labor/index');
                        }
                    }

                    $this->db->commit();
                    $this->flash->success("You updated successful!");
                    return $this->response->redirect('labor/index');
                }
            }
            return $this->response->redirect('labor/index');
        }
    }

    public function createAction()
    {
        if($this->request->isPost())
        {
            $this->db->begin();                     //transaction starting here

            /*fill the fields*/
            $username = $this->request->getPost('username');
            $position = $this->request->getPost('position');
            $submit = $this->request->getPost('submit');

            if(isset($submit)){
                $createUser = new Labor();
                $createUser->date_added = time();
                $createUser->username = $username;
                $createUser->position = $position;

                if($createUser->save() && $this->request->hasFiles())
                {
                    $uploadedCv = $this->request->getUploadedFiles();

                    foreach ($uploadedCv as $file){

                        $filename = $file->getName();
                        $filesize = $file->getSize();
                        $filetype = $file->getType();

                        if(!$file->isUploadedFile()){
                            $this->flashSession->error('You have to upload a file!');
                            return $this->response->redirect('labor/index');
                        }

                        $uploadedFile = new LaborCV();
                        $uploadedFile->user_id = $createUser->id;
                        $uploadedFile->filename = $filename;
                        $uploadedFile->filetype = $filetype;
                        $uploadedFile->filesize = $filesize;
                        $moveFile = $file->moveTo('cv/' . $filename);

                        if(!$uploadedFile->create())
                        {
                            $this->db->rollback();

                            $errors = $createUser->getMessages();

                            foreach ($errors as $error) {
                                $this->flash->error($error->getMessages());
                            }
                        }
                    }
                    $this->db->commit();
                    $this->flashSession->success('You successful create new labor information!');
                    return $this->response->redirect('labor/index');

                } else {
                    $this->db->rollback();
                    $errors = $createUser->getMessages();

                    foreach ($errors as $error) {
                        $this->flash->error($error->getMessage());
                    }
                    $this->view->flash = $this->flash;
                    return $this->response->redirect('labor/index');

                }

            }
            $this->view->labors = $this->getAllFiles();
        }
    }

    public function indexAction()
    {
        /* the fields */
        $username = $this->request->getPost('username');
        $position = $this->request->getPost('position');
        $submit = $this->request->getPost('submit');
        if (isset($submit)) {

            if (!filter_var($username, FILTER_DEFAULT)) {
                $this->flash->error('Your username is not valid');
                return true;
            }

            if (!filter_var($position, FILTER_DEFAULT)) {
                $this->flash->error('Your position is not valid');
                return true;
            }

            $newUser = new Labor();
            $newUser->date_added = time();
            $newUser->username = $username;
            $newUser->position = $position;

            if ($newUser->save()) {

                $this->flash->success('You successful added new labor information.');
                return $this->response->redirect('Labor/index');
            } else {

                $errors = $newUser->getMessages();

                foreach ($errors as $error) {
                    $this->flash->error($error->getMessage());
                }
            }
        }
        $this->view->flash = $this->flash;
        $this->view->labors = $this->getAllFiles();
    }

    public function deleteAction($id)
    {

        $laborInformation= Labor::findFirstById($id);
        $filesForTheLabor = LaborCV::findFirstByUserId($laborInformation->id);

        $cvFiles = array();
        $this->db->begin();

        foreach ($filesForTheLabor as $fileCv)
        {
            if(!$filesForTheLabor->delete())
            {
                $this->db->rollback();

                $this->flashSession->error('There is no file to delete!');

                return;
            } else {
                $cvFiles[] = $fileCv->filename;
            }
        }

        if(!$laborInformation->delete())
        {
            $this->db->rollback();
            $this->flashSession->error('There is no user with this ID');
            return;
        }

        $this->db->commit();

        foreach ($cvFiles as $filename)
        {
            $path = "cv/";
            unlink($path . $filename);
        }

        $this->flash->success('You successfuly deleted this book!');
        return $this->response->redirect("labor/index");
    }

    private function getAllFiles()
    {
        $newUser = Labor::find([
            'order' => 'id DESC'
        ]);
        return $newUser;
    }
}




///////////////////////////////////////////////////////////////////
//$available = $this->request->getPost('available') == 'on' ? 1 : 0;
//$status = $this->request->getPost('check') == 'on' ? 1 : 0;


//                $files = $this->request->getUploadedFiles();
//                foreach($files as $file)
//                {
//                    $filename = $file->getName();
//                    $filetype = $file->getType();
//                    $filesize = $file->getSize();
//
//                    if($files->isUploadedFile())
//                    {
//                        $uploadedFile = new LaborCV();
//                        $uploadedFile->filename=$filename;
//                        $uploadedFile->filetype=$filetype;
//                        $uploadedFile->filesize=$filesize;
//                    }
//
//                }


//                return $this->response->redirect('Labor/index');
<?php

class BookmarkController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->view->headTitle('Bookmarks');
    }

    public function indexAction()
    {
        $this->_helper->getHelper('Redirector')->setGotoSimple("list");
    }

    public function listAction()
    {
        $this->view->headTitle('Bookmark - List');
        /*
        $db = $this->getFrontController()
            ->getParam('bootstrap')
            ->getPluginResource('db');
        */

        $table = new Application_Model_Bookmark();
        $query = $table->select()
            ->order("title desc")
            ->limitPage(0, 100);
        $this->view->data = $table->fetchAll($query);
    }

    public function deleteAction()
    {
        $id = intval($this->_request->getParam('id'));
        try {
            $table = new Application_Model_Bookmark();
            $where = $table->getAdapter()->quoteInto('id = ?', $id);
            $table->delete($where);
        } catch(Exception $e) {
            // TODO :)
        }
        $this->_helper->getHelper('Redirector')->setGotoUrl("/bookmark/list");
    }

    public function editAction()
    {
        $this->view->headTitle('Bookmark - Edit item');
        $form = new Default_Form_Bookmark();
        $form->setAction('');

        // elem betoltese, ha van
        if ($id = intval($this->_request->getParam('id'))) {
            $table = new Application_Model_Bookmark();
            $where = $table->select()->where('id = ?', $id);
            $row = $table->fetchRow($where);
            
            if ($row === null) {
                // TODO nincs ilyen sor
                exit('unknown row :(');
            }
            $form->populate($row->toArray());
        }

        // poszt feldolgozasa
        if ($this->_request->isPost()) {
            $postData = $this->_request->getParams();
            $form->populate($postData);
            if ($form->isValid($postData)) {
                // CREATE / UPDATE
                $id = $this->saveBookmark(array(
                    'id'    => $id,
                    'title' => $this->_request->getParam('title'),
                    'url'   => $this->_request->getParam('url')
                ));
                // ha minden oke, megy a redirect
                $this->_helper->getHelper('Redirector')
                    ->setGotoUrl("/bookmark/edit/" . $id);
            } else {
                // TODO hiba a formban!
                var_dump($form->getErrors());
            }
            return $id;
        }
        
        $this->_helper->viewRenderer->setRender('edit');
        $this->view->form = $form;
        $this->view->id = $id;
    }

    // ezt a kódot ki kéne szervezni resource-ba, hogy szép legyen :)
    protected function saveBookmark(array $bookmark)
    {
        try {
            $table = new Application_Model_Bookmark();
            if (array_key_exists('id', $bookmark) and $bookmark['id'] > 0) {
                // update
                $id = $bookmark['id'];
                $where = $table->getAdapter()->quoteInto('id = ?', $id);
                $table->update($bookmark, $where);
            } else {
                // create
                $bookmark['id'] = null;
                $row = $table->createRow();
                $id = $table->insert($bookmark);
            }
        } catch(Zend_Db_Statement_Exception $e) {
            // TODO adatbazis hiba, duplazodo id vagy unique mezovel gond van!?
            var_dump(get_class($e), $e->geMessages());
        } catch(Exception $e) {
            // TODO nem sikerult a mentes
            var_dump(get_class($e), $e->geMessages());
        }

        return $id;
    }

}

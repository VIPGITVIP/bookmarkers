<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Core\App;
use Cake\Collection\Collection;

/**
 * Bookmarks Controller
 *
 * @property \App\Model\Table\BookmarksTable $Bookmarks
 */
class BookmarksController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'conditions' => [
                'Bookmarks.user_id' => $this->Auth->user('id'),
            ]
        ];
     //   $bookmarks = $this->paginate($this->Bookmarks);

     //   $this->set(compact('bookmarks'));
        $this->set('bookmarks', $this->paginate($this->Bookmarks));
        $this->set('_serialize', ['bookmarks']);


     /* ORM STUDY  begin */
     /*
        //�ڑ��P
        $article = $this->Bookmarks
        ->find()
        ->where(['id' => 2])
        ->first();

        //debug($article->description);
        //echo "-------------<br>";
        
        //�ڑ��Q
        $query = TableRegistry::get('bookmarks')->find();

        foreach ($query as $article) {
           //debug($article->title);
           //echo $article->title."<br>";
        }
        //echo "<br>-------------<br>";
        
        //�ڑ��R
        $connection = ConnectionManager::get('default');
        $results = $connection->execute('SELECT * FROM bookmarks')->fetchAll('assoc');

        foreach ($results as $result) {
           //debug($article->title);
           //echo $result['title']."<br>";
        }

        echo "<pre>";
        //var_dump($results);
        echo "</pre>";
        echo App::path('Controller')[0];
        */

     /* ORM STUDY  end */

     /* collection study begin */
/*
     $items = ['a' => 1, 'b' => 2, 'c' => 3];
     $itemsA = ['0' => 555, '1' => 666, '2' => 777];
     $collectionA = new Collection($items);
     $collectionB = collection($items);

     // var_dump($items);
     // echo "-------------<br>";
     // var_dump($collectionA);

      // append()
      $collectionC=$collectionA->append($itemsA);

      $collectionC->each(
          function ($value, $key) {
           echo "<br>"."element $key: $value";
          }
      );

      // map()
      $collectionD=$collectionC->map(
          function ($value, $key) {
           return $value*2;
          }
      );
     
     // each()
      $collectionD->each(
          function ($value, $key) {
           echo "<br>"."collectionD $key: $value";
          }
      );
      
      // toArray()
      echo "<pre>";
      var_dump($collectionD->toArray());
      echo "</pre>";

     echo "<pre>";
     var_dump($collectionD->extract('b')->toArray());
     echo "</pre>";

     $allYoungPeople = $collectionC->every(function ($value,$key) {
       return $key < 222;
     });
     echo "flag:".$allYoungPeople;
*/
     /* collection study end */


       // $this->set('firstname', 'Doug');
       // $this->_smarty->display('index.tpl');
        //$this->display('index.tpl');

        /*
        $smarty = new Smarty();

        $smarty->assign('firstname', 'Doug');
        $smarty->assign('lastname', 'Evans');
        $smarty->assign('meetingPlace', 'New York');

        $smarty->display('index.tpl');
        */

    }

    /**
     * View method
     *
     * @param string|null $id Bookmark id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
            'contain' => ['Users', 'Tags']
        ]);

        $this->set('bookmark', $bookmark);
        $this->set('_serialize', ['bookmark']);



    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bookmark = $this->Bookmarks->newEntity();
        if ($this->request->is('post')) {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->data);
            if ($this->Bookmarks->save($bookmark)) {
                $this->Flash->success(__('The bookmark has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The bookmark could not be saved. Please, try again.'));
            }
        }
   //     $users = $this->Bookmarks->Users->find('list', ['limit' => 200]);
        $tags = $this->Bookmarks->Tags->find('list', ['limit' => 200]);
        $this->set(compact('bookmark', 'users', 'tags'));
        $this->set('_serialize', ['bookmark']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Bookmark id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
            'contain' => ['Tags']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->data);
            if ($this->Bookmarks->save($bookmark)) {
                $this->Flash->success(__('The bookmark has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The bookmark could not be saved. Please, try again.'));
            }
        }
 //       $users = $this->Bookmarks->Users->find('list', ['limit' => 200]);
        $tags = $this->Bookmarks->Tags->find('list', ['limit' => 200]);
        $this->set(compact('bookmark', 'users', 'tags'));
        $this->set('_serialize', ['bookmark']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Bookmark id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bookmark = $this->Bookmarks->get($id);
        if ($this->Bookmarks->delete($bookmark)) {
            $this->Flash->success(__('The bookmark has been deleted.'));
        } else {
            $this->Flash->error(__('The bookmark could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

       // search bookmarks by tags
    public function tags()
    {
        $tags = $this->request->params['pass'];
 
        // Use the BookmarksTable to find tagged bookmarks.
        $bookmarks = $this->Bookmarks->find('tagged', [
            'tags' => $tags
        ]);
 
        // Pass variables into the view template context.
        $this->set([
            'bookmarks' => $bookmarks,
            'tags' => $tags
        ]);
    }
}

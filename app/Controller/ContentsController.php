<?php
class ContentsController extends AppController {
  public $name = 'Contents';
  
  public $helpers = array('Html', 'Form', 'Paginator', 'Session'); 

  public $paginate = array(
    'fields' => array('id', 'title', 'type', 'status', 'author_id', 'created', 'modified', 'Author.username'),
    'limit' => 25
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->deny();
    $this->Auth->allow('display');
  }

  public function display($id = 'home') {
    $content = $this->Content->findByIdOrAlias($id, $id);
    if($content) {
      $this->set('title_for_layout', $content['Content']['title']);
      $this->set('content', $content);
      $this->render($content['Content']['type'] . '/display');
    }
    else if($id == 'home') {
      $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
    }
    else {
      throw new NotFoundException();    
    }
  }

  public function index() {
    $data = $this->paginate();
    $this->set('contents', $data);
  }

  public function delete($id = null) {
    if($this->Content->delete($id)) {
      $this->Session->setFlash('Content has been deleted');
    }
    else {
      $this->Session->setFlash('Content could not be deleted');
    }
    $this->redirect(array('action' => 'index'));
  }

  public function add() {
    if($this->request->is('post')) {
      $this->request->data['Content']['status'] = 'published';
      $this->request->data['Content']['type'] = 'contents';
      $this->request->data['Content']['author_id'] = $this->Auth->user('id');
    
      if($this->Content->save($this->request->data)) {
        $this->Session->setFlash('Content has been added.');
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('Content could not be added.');
      }
    }
  }

  public function menu_map_key($menu_id, $title, $parent_id) {
    return $menu_id . '|' . $title . '|' . $parent_id;
  }

  public function edit($id) {
    $this->Content->id = $id;
    if($this->request->is('get')) {
      $this->request->data = $this->Content->read();
      if(!$this->request->data) {
        throw new NotFoundException();
      }
      $this->request->data['Content']['__menu'] = $this->Content->Menu->get_menu_definition($id);
    }
    else if($this->request->is('put')) {
      if($this->Content->save($this->request->data)) {
        $this->Session->setFlash('Content has been updated.');

        $this->Content->Menu->deleteAll(array('Menu.content_id' => $this->Content->id));

        $menu_map = array();
        $existing_menu_items = $this->Content->Menu->find('all');
        foreach($existing_menu_items as $menu_item) {
          $menu_id = $menu_item['Menu']['menu_id'];
          $title = $menu_item['Menu']['title'];
          $menu_map[$this->menu_map_key($menu_id, $title, null)] = $menu_item['Menu']['id'];          
        }

        $all_menu_defs = $this->request->data['Content']['__menu'];
        $menu_def_count = preg_match_all('/' .
          '\s*([^|;\[\]]*)\s*' .            // menu ID
          '\|' .                            // separator
          '\s*([^|;\[\]]*)\s*' .            // menu item name
          '(?:\|\s*([^|;\[\]]*)\s*)?' .     // optional second level
          '(?:\[\s*([0-9]*)\s*\])?\s*;?' .  // optional index
          '/', $all_menu_defs, $matches);
        if($menu_def_count) {
          for($i = 0; $i < $menu_def_count; $i++) {
            $menu_id = $matches[1][$i];
            $index = $matches[4][$i];
            $title = $matches[2][$i];
            $subtitle = $matches[3][$i];
            $parent_id = null;
            $menu = array('Menu' => array(
              'controller' => 'contents',
              'action' => 'display',
              'parameter' => $this->Content->id,
              'menu_id' => $menu_id,
              'index' => $index,
              'title' => $title,
              'parent_id' => $parent_id,
              'content_id' => $this->Content->id
            ));

            if(!empty($subtitle)) {
              $parent_key = $this->menu_map_key($menu_id, $title, null);
              if(!array_key_exists($parent_key, $menu_map)) {
                $parent_menu = array('Menu' => array(
                  'controller' => 'contents',
                  'action' => 'display',
                  'parameter' => 'home',
                  'menu_id' => $menu_id,
                  'title' => $title,
                  'parent_id' => null));
                $this->Content->Menu->save($parent_menu);
                $menu_map[$parent_key] = $this->Content->Menu->id;
                unset($this->Content->Menu->id);
              }
              $parent_id = $menu_map[$parent_key];
              $title = $subtitle;
              $menu['Menu']['parent_id'] = $parent_id;
              $menu['Menu']['title'] = $title;
            }
            $key = $this->menu_map_key($menu_id, $title, $parent_id);
            if(array_key_exists($key, $menu_map)) {
              $menu['Menu']['id'] = $menu_map[$key];  
            }
            $this->Content->Menu->save($menu);
            $menu_map[$key] = $this->Content->Menu->id;
            unset($this->Content->Menu->id);
          }
        }

        $this->Session->setFlash('Content updated.');
        //$this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('Content could not be updated.');
      }
    }
  }
}
?>

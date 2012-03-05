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

  public function replace_token($match) {
    return $match[0];
  }

  public function replace_tokens($original) {
    $view = new View($this);
    $html = $view->loadHelper('Html');

    $replacement = '';
    $last_offset = 0;    
    $count = preg_match_all('/\$\{\s*([^}:]+)\s*:\s*([^}:]*)\s*\}/', $original, $matches, PREG_OFFSET_CAPTURE);
    if($count) {
      for($i = 0; $i < $count; $i++) {
        $token = $matches[0][$i][0];
        $token_offset = $matches[0][$i][1];
        $token_type = $matches[1][$i][0];
        $token_value = $matches[2][$i][0];

        $replacement .= substr($original, $last_offset, $token_offset - $last_offset);
        $token_replacement = '';
        if($token_type == 'img') {
            $img = $html->image($token_value);
            if(preg_match('/src="([^"]*)"/', $img, $img_match)) {
              $token_replacement = $img_match[1];
            }
        }
        $replacement .= $token_replacement;
        $last_offset = $token_offset + strlen($token);
      }
    }
    $replacement .= substr($original, $last_offset);

    return $replacement;
  }

  public function display($id = 'home') {
    $content = $this->Content->findByIdOrAlias($id, $id);
    if($content) {
      $content['Content']['content'] = $this->replace_tokens($content['Content']['content']);

      $this->set('title_for_layout', $content['Content']['title']);
      $this->set('id', empty($content['Content']['alias']) ? $content['Content']['id'] : $content['Content']['alias']);
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

      $this->loadModel('Menu');
      $this->Menu->update_menu_definition($id, $this->request->data['Content']['__menu']);
    
      if($this->Content->save($this->request->data)) {
        $this->Session->setFlash('Content has been added.');
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('Content could not be added.');
      }
    }
  }

  public function edit($id) {
    $this->loadModel('Menu');

    $this->Content->id = $id;
    if($this->request->is('get')) {
      $this->request->data = $this->Content->read();
      if(!$this->request->data) {
        throw new NotFoundException();
      }
      $this->request->data['Content']['__menu'] = $this->Menu->format_menu_definition($id);
    }
    else if($this->request->is('put')) {
      if($this->Content->save($this->request->data)) {
        $this->Session->setFlash('Content has been updated.');
        $this->Menu->update_menu_definition($id, $this->request->data['Content']['__menu']);
        $this->redirect(array('action' => 'index'));
      }
      else {
        $this->Session->setFlash('Content could not be updated.');
      }
    }
  }
}
?>

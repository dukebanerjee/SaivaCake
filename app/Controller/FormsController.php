<?php
App::uses('TimeHelper', 'View/Helper');
class FormsController extends AppController {
  public $name = 'Forms';

  public $helpers = array('Session', 'Time'); 
  
  public function submit() {
    $redirect = "/contents/display/home";
    foreach($this->request->data as $model => $values) {
      if($model != 'Submit') {
        $this->loadModel($model);
        if($this->Session->check('Auth.User')) {
          $user = $this->Session->read('Auth.User');
          $values['username'] = $user['username'];
        }
        $this->$model->save(array($model => $values));

        $form = $this->Form->findByType($model);
        if($form && $form['Form']['success_page']) {
          $this->redirect($form['Form']['success_page']);
        }
      }
    }

    $this->redirect($redirect);
  }
  
  public function submissions($type) {
    $this->loadModel($type);
    $this->paginate = array('limit' => 25);
    $submissions = $this->paginate($type);
    $this->set('name', $this->$type->alias);
    $this->set('fields', array_keys($this->$type->schema()));
    $this->set('submissions', $submissions);
  }
  
  public function delete($type, $id) {
    $this->loadModel($type);
    if($this->$type->delete($id)) {
      $this->Session->setFlash('Submission has been deleted');
    }
    else {
      $this->Session->setFlash('Submission could not be deleted');
    }
    $this->redirect(array('action' => 'submissions/' . $type));
  }
  
  public function export($type) {
    $this->autoRender = false;
    $this->response->type('csv');
    $time = new TimeHelper(new View($this));
    $timestamp = $time->format('Ymd-His', time());
    $this->response->download($type . '-' . $timestamp . '.csv');
    $this->response->send();

    $this->loadModel($type);
    $fields = array_keys($this->$type->schema());
    foreach($fields as $field) {
      echo '"' . $field . '",';
    }
    echo "\n";
    
    foreach($this->$type->find('all') as $submission) {
      foreach($fields as $field) {
        $value = str_replace('"', '""', $submission[$type][$field]);
        echo '"' . $value . '",';
      }
      echo "\n";
    }
  }
}
?>

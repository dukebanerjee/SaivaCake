<?php
class FormsController extends AppController {
  public $name = 'Forms';
  
  public function submit() {
    $redirect = "/contents/display/home";
    foreach($this->request->data as $model => $values) {
      if($model != 'Submit') {
        $this->loadModel($model);
        $this->$model->save(array($model => $values));

        $form = $this->Form->findByType($model);
        if($form && $form['Form']['success_page']) {
          $this->redirect($form['Form']['success_page']);
        }
      }
    }

    $this->redirect($redirect);
  }
}
?>

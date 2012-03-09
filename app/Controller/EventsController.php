<?php
class EventsController extends AppController {
  public $name = 'Events';

  public $components = array(
    'RequestHandler',
  );

  public function index() {
    $view = new View($this);
    $html = $view->loadHelper('Html');
    $this->set('events', array(
	array('title' => 'Test', 
	      'start' => '2012-03-08 15:30', 
	      'end' => '2012-03-08 16:00', 
              'allDay' => false,
	      'url' => $html->url(array('controller' => 'contents', 'action' => 'display', '4')))
    ));
    $this->set('_serialize', 'events');
  }
}
?>
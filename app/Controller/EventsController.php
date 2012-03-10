<?php
class EventsController extends AppController {
  public $name = 'Events';

  public $components = array(
    'RequestHandler',
  );

  public function index() {
    $view = new View($this);
    $html = $view->loadHelper('Html');
    
    $events = array();
    if(array_key_exists('start', $this->request->query) && 
       array_key_exists('end', $this->request->query)) {
        $start = date('Y-m-d H:i:s', $this->request->query['start']);
        $end = date('Y-m-d H:i:s', $this->request->query['end']);
        $this->Event->bindModel(
          array('belongsTo' => array(
            'Content' => array(
              'className' => 'Content'
            )
          )), false);
        $event_objects = $this->Event->find('all', array(
          'conditions' => array('or' => array(
          'Event.start >=' => $start,
          'Event.end <=' => $end))
        ));
        foreach($event_objects as $event_object) {
          $events[] = array(
            'title' => $event_object['Content']['title'],
            'start' => $event_object['Event']['start'],
            'end' => $event_object['Event']['end'],
            'allDay' => 
              strpos($event_object['Event']['start'], '00:00:00') &&
              strpos($event_object['Event']['end'], '23:59:00'),
              'url' => $html->url(array(
                'controller' => 'contents', 
                'action' => 'display', 
                $event_object['Event']['content_id']
              ))
          );
        }
    }
    
    $this->set('events', $events);
    $this->set('_serialize', 'events');
  }
}
?>

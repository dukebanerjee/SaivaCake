<?php
echo $this->Form->create('Waiver', array(
  'url' => '/forms/submit',
  'class' => 'basic',
  'id' => 'waiver-form'));
?>
<div>
I have read and understand all of the above on this the
<?php
echo $this->Form->day('Waiver.signed_date', array(
  'empty' => '(day)'
));
?>
&nbsp;date of&nbsp;
<?php
echo $this->Form->month('Waiver.signed_date', array(
  'empty' => '(month)'
));
?>
,
<?php
echo $this->Form->year('Waiver.signed_date', 2012, 2020, array(
  'empty' => '(year)'
));
?>
</div>

<?php
echo $this->Form->input('Waiver.volunteer_signature');
echo $this->Form->input('Waiver.volunteer_name');
echo $this->Form->input('Waiver.volunteer_address', array(
  'div' => array('class' => 'address input')
));
echo $this->Form->input('Waiver.volunteer_zip', array('label' => 'ZIP', 
  'div' => array('class' => 'zip input')
));
echo $this->Form->input('Waiver.contact_number', array(
  'label' => 'Phone Number Where You Are Most Easy To Reach',
  'div' => array('class' => 'contact_number input')
));
echo $this->Form->input('Waiver.contact_email', array(
  'label' => 'Email'
));
echo $this->Form->input('Waiver.organization', array(
  'label' => 'Group/Organization (if applicable)',
  'div' => array('class' => 'organization input')
));
echo $this->Html->tag('fieldset',
  $this->Html->tag('legend', 'In case of emergency for, please contact:') .
  $this->Form->input('Waiver.emergency_contact_name', array(
    'label' => 'Name'
  )) .
  $this->Form->input('Waiver.emergency_contact_relationship', array(
    'label' => 'Relationship to Volunteer',
    'div' => array('class' => 'relationship input')
  )) .
  $this->Form->input('Waiver.emergency_contact_address', array(
    'label' => 'Address',
    'div' => array('class' => 'address input')
  )) .
  $this->Form->input('Waiver.emergency_contact_zip', array(
    'label' => 'ZIP',
    'div' => array('class' => 'zip input')    
  )) .
  $this->Form->input('Waiver.emergency_contact_phone', array(
    'label' => 'Phone',
    'div' => array('class' => 'emergency_contact_number input')    
  )),
  array('escape' => false, 'id' => 'emergency')
);
echo $this->Html->tag('fieldset',
  $this->Html->tag('legend', '** If the volunteer is under the age of 18 or a dependent, a parent or legal guardian must sign. **') .
  $this->Form->input('Waiver.emergency_contact_signature', array(
    'label' => 'Emergency Contact/ Guardian Signature',
    'div' => array('class' => 'emergency_contact_signature input') 
  )),
  array('escape' => false)
);
echo $this->Form->end(array('name' => 'Submit'));
?>

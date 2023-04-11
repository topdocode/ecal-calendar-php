# ecal-calendar-php
this package for you using ecal calendar 

#how to install 
composer require topdocode/ecal-calendar-php

Example to use

Calendar 
1. getCalendar($filter=[],$id=null)
2. createCalendar($data)
3. updateCalendar($data=[], $id)
4. deleteCalendar($id)

Event 
1. getEvent($filter=[],$id=null)
2. createEvent($data)
3. updateEvent($data=[], $id)
4. deleteEvent($id)

Servie Example

use Topdocode\EcalCalendar\EcalCalendar

$ecalService = new EcalService($apiKey, $apiSecret)

$ecalService->getCalendar();

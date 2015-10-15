<?php

$tmpl = <<<EMAIL
<p>Hey there!</p>

<p>There is a new pending reservation at {$site_url}:</p>

<p>{$reservable_title}, {$from_date} to {$to_date}, reserved by {$user_email}</p>

<p>You can either accept or decline this reservation at the admin panel of the site: {$reservation_edit_url}.</p>

<p>Best wishes,<br>{$site_name} administrators</p>
EMAIL;

return $tmpl;

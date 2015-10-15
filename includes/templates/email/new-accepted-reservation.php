<?php

$tmpl = <<<TMPL
<p>Hey there!</p>

<p>Your reservation at {$site_name} has been accepted:</p>

<p>{$reservable_title}, {$from_date} to {$to_date}</p>

<p>Please be in touch in case you need more information about your reservation.</p>

<p>Best wishes,<br>{$site_name} administrators</p>
TMPL;

return $tmpl;

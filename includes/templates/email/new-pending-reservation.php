<?php

$tmpl = <<<TMPL
<p>Hey there!</p>

<p>You have a new pending reservation at {$site_url}:</p>

<p>{$reservable_title}, {$from_date} to {$to_date}</p>

<p>You will be notified via email when your reserve is either accepted or declined.</p>

<p>If you wish to cancel your reservation, please contact us via email.</p>

<p>Best wishes,<br>{$site_name} administrators</p>
TMPL;

return $tmpl;

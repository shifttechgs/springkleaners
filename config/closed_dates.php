<?php

/*
| Dates we're closed (holidays, team days off). Nobody can book them: the hero card, the phone booking sheet and
| the full booking form show them as "Closed", the server refuses them, and admin can't schedule onto them.
|
| Add a line per date, 'YYYY-MM-DD' => 'reason' (the reason is for us; clients just see "Closed").
| Past dates can be deleted.
*/

return [
    '2026-12-26' => 'Closed for the holidays',
    '2026-12-27' => 'Closed for the holidays',
];

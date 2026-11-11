<?php

/*
|--------------------------------------------------------------------------
| User Types (Roles)
|--------------------------------------------------------------------------
| key   = value stored in users.user_type
| value = label shown to people in the system
|
| "admin" and "superadmin" can open every page (see EnsureUserHasRole).
| "superadmin" is intentionally NOT listed here, so it cannot be assigned
| from the screen; set it directly in the database if you ever need it.
*/

return [
    'content_admin'  => 'Content Supervisor',
    'creative_admin' => 'Creative Supervisor',
    'content_team'   => 'Content Team',
    'creative_team'  => 'Creative Team',
    'yearbook_team'  => 'Yearbook Team',
    'admin'          => 'Admin',
    'user'           => 'Viewer',
];

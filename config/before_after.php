<?php

/**
 * Before/after gallery entries. Empty until real job photos are supplied —
 * the gallery component and its slots on the homepage and service pages
 * render nothing until this array has entries, so nothing fake ships in
 * the meantime.
 *
 * Each entry:
 *   'before'   => public path to the "before" photo
 *   'after'    => public path to the "after" photo
 *   'caption'  => short description, e.g. "Deep Clean — Milnerton kitchen"
 *   'services' => array of service slugs this pair should appear on
 *                 (omit or leave empty to show only on the homepage)
 *
 * Example once photos exist:
 *   [
 *       'before' => '/images/before-after/deep-clean-kitchen-before.jpg',
 *       'after' => '/images/before-after/deep-clean-kitchen-after.jpg',
 *       'caption' => 'Deep Clean — Milnerton kitchen',
 *       'services' => ['deep-cleaning'],
 *   ],
 */

return [

];

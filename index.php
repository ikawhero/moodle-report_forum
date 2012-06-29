<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Forum report
 *
 * @package    report
 * @subpackage forum
 * @copyright  2012 Michael de Raadt <michaeld@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

// Required files
require_once('../../config.php');


// Get passed parameters
$courseid      = required_param('course', PARAM_INT);
$forumselected = optional_param('forum', null, PARAM_INT);


// Get course and context
$course  = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id);


// Set up page
$urlparams = array('course'=>$courseid);
if ($forumselected) {
    $urlparams['forum'] = $forumselected;
}
$url = new moodle_url('/report/forum/index.php', $urlparams);
$title = get_string('title', 'report_forum');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title($title);
$PAGE->set_pagelayout('report');
$PAGE->set_heading($course->fullname);


// Get the list of forums
$query = "SELECT id, name
          FROM {forum}
          WHERE course = :courseid";
$params = array('courseid' => $courseid);
$forums = $DB->get_records_sql($query, $params);
$selectoptions = array(0 => get_string('allforums', 'report_forum'));
foreach ($forums as $forum) {
    $selectoptions[$forum->id] = $forum->name;
}


// Start the output
echo $OUTPUT->header();
echo $OUTPUT->heading($title);

echo $OUTPUT->box_start('report_forum_selector');
echo get_string('selectforum', 'report_forum').': ';
echo $OUTPUT->help_icon('selectingaforum', 'report_forum');
echo $OUTPUT->box_end();

echo $OUTPUT->footer();

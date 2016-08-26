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
 * Internal library of functions for module recommendation
 *
 * All the recommendation specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_recommendation
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function get_mod_events_by_user($course, $user)
{
	global $DB;

	try {
		return $DB->get_records_sql("
			SELECT 
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_forum') AS forum,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_book') AS book,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_chat') AS chat,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_assign') AS assign,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_choice') AS choice,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_data') AS data,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_feedback') AS feedback,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_folder') AS folder,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_glossary') AS glossary,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_lesson') AS lesson,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_page') AS page,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_quiz') AS quiz,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_resource') AS resource,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_scorm') AS scorm,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_survey') AS survey,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_url') AS url,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_wiki') AS wiki,
				(SELECT COUNT(id) FROM {logstore_standard_log} WHERE courseid=? AND userid=? AND component='mod_workshop') AS workshop
			", array($course, $user, $course, $user, $course, $user, $course, $user, $course, $user, $course, 
				$user, $course, $user, $course, $user, $course, $user, $course, $user, $course, $user, $course, 
				$user, $course, $user, $course, $user, $course, $user, $course, $user, $course, $user, $course, $user)
		);
	} catch(Exception $e) {

	}
}
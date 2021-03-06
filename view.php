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
 * Prints a particular instance of recommendation
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_recommendation
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace recommendation with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... recommendation instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('recommendation', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $recommendation  = $DB->get_record('recommendation', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $recommendation  = $DB->get_record('recommendation', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $recommendation->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('recommendation', $recommendation->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_recommendation\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $recommendation);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/recommendation/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($recommendation->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('recommendation-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($recommendation->intro) {
    echo $OUTPUT->box(format_module_intro('recommendation', $recommendation, $cm->id), 'generalbox mod_introbox', 'recommendationintro');
}

// Replace the following lines with you own code.
echo $OUTPUT->heading('Sistema de Recomendação');




// CODE BEGIN
$users = get_enrolled_users($PAGE->context, 'mod/assignment:submit');

//update values
foreach ($users as $key => $value) {
    $events = get_mod_events_by_user($course->id, $value->id);

    foreach ($events as $value2) {
        foreach ($value2 as $key => $value3) {
            set_user_resource($id, $value->id, $key, $value3);
        }
    }
}


if(count($users) > 0)
{
    echo '<p>Módulos utilizados nesta pesquisa (respectivamente):</p>
            <div class="alert alert-info">
            forum | book | chat | assign | choice | data | feedback | folder | glossary | lesson
             | page | quiz | resource | scorm | survey | url | wiki | workshop
            </div>';

    echo '<table class="table-striped">
            <thead>
                <tr>
                    <th class="text-center">Aluno</th>
                    <th class="text-center">Eventos</th>
                    <th class="text-center">Resultado</th>
                    <th class="text-center">Média</th>
                </tr>
            </thead>
        <tbody>';

    foreach ($users as $key => $value) {
        $sum = 0; $count = 0;
        $events = get_mod_events_by_user($course->id, $value->id);

        foreach ($events as $value2) {
            foreach ($value2 as $value3) { 
                $sum += intval($value3);
                $count++;
            }
        }
        
        $avg = $sum/$count;

        $final = [];
        foreach ($events as $value2) {
            foreach ($value2 as $value3) { 
                if(intval($value3) > $avg) array_push($final, 1);
                else array_push($final, 0);
            }
        }
            
        echo '<tr title="'.$value->firstname.'">
                <td class="text-center"><img src="../../user/pix.php?file=/'.$value->id.'/f3.jpg"/></td>
                <td class="text-center">'.json_encode($events).'</td>
                <td class="text-center">'.json_encode($final).'</td>
                <td class="text-center">'.$avg.'</td>
              </tr>';
    }
    echo '</tbody></table>';
}
else
{
    echo '<div class="alert alert-warning"> Nenhum aluno cadastrado </div>';
}




// Finish the page.
echo $OUTPUT->footer();
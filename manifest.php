<?php
/**
 * Project: Example Scheduler Job for import XML Data to SugarCRM Data Base
 * Original Dev: Antonio Musarra, January 2014
 * @2009-2014 Antonio Musarra <antonio.musarra[at]gmail.com>
 *
 * Desc: Manifest file for installing Schedule Job
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

global $sugar_config;

$upload_dir = $sugar_config['upload_dir'];
$manifest = array(
		'key' => 'ba2b4a8efaf959e6770c4e3e5f429f9ff960b7a5',
		'acceptable_sugar_versions' => array(
				'regex_matches' => array(
						0 => '6.5\.*',
						1 => '7\.*'
				),
		),
		'acceptable_sugar_flavors' => array(
				0 => 'PRO',
				1 => 'ENT',
				2 => 'CE'
		),
		'name'    => 'JobXMLImporter',
		'description'  => 'Scheduler Job for import XML Data to SugarCRM Data Base',
		'is_uninstallable' => true,
		'author'   => 'Antonio Musarra',
		'published_date' => 'January 4, 2014',
		'version'   => '1.0.0',
		'readme' => 'README.txt',
		'type'    => 'module',
);

$installdefs = array(
		'id'  => 'JobXMLImporter_SchedulerJob',
		'post_uninstall' => array(
				'<basepath>/scripts/post_uninstall.php',
		),
		'language' => array (
				0 =>
				array (
						'from' => '<basepath>/jobs/language/en_us.xmlImporterContactsJobs.php',
						'to_module' => 'Schedulers',
						'language' => 'en_us',
				),
				1 =>
				array (
						'from' => '<basepath>/jobs/language/it_it.xmlImporterContactsJobs.php',
						'to_module' => 'Schedulers',
						'language' => 'it_it',
				),
		),
		'scheduledefs' => array (
				array(
						'from' => '<basepath>/jobs/XMLImporterContactsTask.php'
				),
		),
);
?>
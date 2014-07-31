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

if (! defined('sugarEntry') || ! sugarEntry) die('Not A Valid Entry Point');

function post_install() {
	
	/**
	 * Configure a new Task Scheduler for Job
	 */
	$job = BeanFactory::getBean('Schedulers');
	$job->name = 'Job to import XML data into SugarCRM Data Base';
	$job->job = 'function::XmlImporterContactsJobs';
	$job->date_time_start = '2014-01-01 00:00:00';
	$job->job_interval = '*::*::*::*::*';
	$job->status = 'Active';
	$job->catch_up = '1';
	$job->save();
	
	/**
	 * Add a new configuration param
	 */
	$configOveride = new Configurator();
	$configOveride->config['JobXMLImporter_XMLDataFilePath'] = '/SharedFS/CRM/JobXMLImporter/xmldata';
	$configOveride->saveConfig();
}
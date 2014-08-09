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

array_push($job_strings, 'XmlImporterJobs');

function XmlImporterJobs()
{
	$GLOBALS['log']->info('Running: XmlImporterJobs');
	$config = new Configurator();
	$config->loadConfig();
	$xmlDataDir =  $config->config['JobXMLImporter_XMLDataFilePath'];
	
	$GLOBALS['log']->info("Scanning XML Data dir $xmlDataDir...");
	
	$directoryContent = scandir($xmlDataDir);
	
	$GLOBALS['log']->info("Scanning XML Data dir $xmlDataDir... [Found " . count($directoryContent) . " files]");
	
	foreach ($directoryContent as $itemFile) {
		if (is_dir($xmlDataDir . DIRECTORY_SEPARATOR . $itemFile)) continue;
		if (strcasecmp(substr($itemFile, -4), ".xml") != 0) continue;
		
		$GLOBALS['log']->info("Processing $itemFile file...");

		$accountsCollectionObject = simplexml_load_file($xmlDataDir . DIRECTORY_SEPARATOR . $itemFile);
		if ($accountsCollectionObject instanceof  SimpleXMLElement) {
			foreach ($accountsCollectionObject->children()->children() as $accountObject) {
				$GLOBALS['log']->info("Processing account with id $accountObject->id...");
				$accountCRM = BeanFactory::getBean("Accounts", $accountObject->id, array(), false);
				if (!is_null($accountCRM) && ($accountCRM instanceof SugarBean)) {
					$GLOBALS['log']->info("Update record account...");
					$accountCRM->description = $accountObject->description;
					$accountCRM->phone_fax = $accountObject->phone_fax;
					$accountCRM->save();
				} else {
					$GLOBALS['log']->info("Insert new record account...");
					$accountCRM = BeanFactory::newBean("Accounts");
					$accountCRM->name = $accountObject->name;
					$accountCRM->description = $accountObject->description;
					$accountCRM->phone_fax = $accountObject->phone_fax;
					$accountCRM->save();
				}
			}
		}
	}
	
	$GLOBALS['log']->info('End: XmlImporterJobs');

	return true;
}
SugarCRM Scheduler Job XML Importer
======================

## 1. Overview
This repository contains a complete example of a Schedule Job. This job performs the import of data regarding the accounts on the SugarCRM database. The data source from which to import the accounts are in XML format.

*This project are tested on SugarCRM CE 6.5 and SugarCRM Ent 7.2*

## 2. Use Case
A typical use case where the Scheduler SugarCRM might help is shown in the diagram of Figure 1.

![Smaller icon](http://www.dontesta.it/blog/wp-content/uploads/2014/08/SugarCRMSchedulers_1.png "Example Use Case")

**Figure 1 - Clone project and build process**

There exists a requirement such that it is necessary that an external system to SugarCRM (such as a billing system) must be updated (for example once a day) the data of the accounts. The external system sends the data to be updated or inserted via an XML data stream (whose scheme is shared between the two systems), this data stream will subsequently be drawn from SugarCRM. The XML stream will be made available in SugarCRM on a shared directory (via NFS for example).

To implement this use case the best tool to use is the **SugarCRM Scheduler**.

## 3. Scheduler
Sugar provides a Scheduler service that can execute predefined functions asynchronously on a periodic basis. The Scheduler integrates with external UNIX systems and Windows systems to run jobs that are scheduled through those systems. The Scheduler service checks the list of Schedulers defined in the Scheduler Admin screen and executes any that are currently due.

SugarCRM allows you to easily implement their own task which will be executed by the Scheduler. For more information on this topic goto at [Schedulers](http://support.sugarcrm.com/02_Documentation/04_Sugar_Developer/Sugar_Developer_Guide_7.2/70_API/Application/Job_Queue/10_Schedulers/).

## 4. Build
Whenever possible I try to make objects simple and functional to use. From this project, you can easily create the package to install on SugarCRM (via Module Loader) by the **ant** tool. Find below the instructions you need to get the zip package to install on SugarCRM.

```
$ git clone https://github.com/amusarra/SugarCRMJobXMLImporter.git
$ cd SugarCRMJobXMLImporter
$ ant
```
**Listing 1 - Clone project and build process**

The build of the project via ant will create the zip file into the directory dist. See the output of the build to follow.

```
Buildfile: /private/tmp/SugarCRMJobXMLImporter/build.xml

package:
      [zip] Building zip: /private/tmp/SugarCRMJobXMLImporter/dist/JobXMLImporter_SchedulerJob_1.0.0-8.zip

BUILD SUCCESSFUL
Total time: 0 seconds
```
**Listing 2 - Output of the ant build process**

## 5. Install Scheduler Job
The installation procedure should be performed in the usual way. For more information see [Module Loader](http://support.sugarcrm.com/02_Documentation/01_Sugar_Editions/01_Sugar_Ultimate/Sugar_Ultimate_7.2/Administration_Guide/07_Developer_Tools/21_Module_Loader/).

![Smaller icon](http://www.dontesta.it/blog/wp-content/uploads/2014/08/SugarCRMSchedulers_2.png "Display log of the installe")

**Figure 2 - Display log of the installer**

The installation process installs the new job and create a new task associated. In the configuration of SugarCRM (config_override.php) will be added to a new configuration parameter, this parameter specifies the path of sharing XML streams.

![Smaller icon](http://www.dontesta.it/blog/wp-content/uploads/2014/08/SugarCRMSchedulers_3.png "Installed Scheduler Job")

**Figure 3 - Installed Scheduler Job**

```
<?php
/***CONFIGURATOR***/
$sugar_config['JobXMLImporter_XMLDataFilePath'] = '/SharedFS/CRM/JobXMLImporter/xmldata';
/***CONFIGURATOR***/
```
**Listing 3 - New config parameter added by installation process**

Within the directory xmlDataExample (of the project) is a sample XML file.

## 6. The Job in Action
The job performs the following process:

1. Reads the configuration parameter
2. Performs a scan of the shared directory
3. Begin to process one at a time XML streams
4. Start reading the accounts from the XML stream. If the account exists on SugarCRM then updates the data (XML to DB) otherwise create a new account with the data from the XML file.

The [XMLImporterTask.php](https://github.com/amusarra/SugarCRMJobXMLImporter/blob/master/jobs/XMLImporterTask.php) file contains the logic just described.


```
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Running: XmlImporterContactsJobs
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Scanning XML Data dir /SharedFS/CRM/JobXMLImporter/xmldata...
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Scanning XML Data dir /SharedFS/CRM/JobXMLImporter/xmldata... [Found 3 files]
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Processing accounts_data_200140731.xml file...
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Processing account with id 130d7312-7be8-de1a-756a-53ce2dd949f7...
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Query:SELECT TOP 1  accounts.* FROM accounts  WHERE accounts.id = N'130d7312-7be8-de1a-756a-53ce2dd949f7'
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Update record account...
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Query:UPDATE accounts
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Query Execution Time:0.013454914093018
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Processing account with id id1...
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Query:SELECT TOP 1  accounts.* FROM accounts  WHERE accounts.id = N'id1'
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Insert new record account...
Fri Aug  1 00:48:03 2014 [2142][1][INFO] Query:INSERT INTO accounts (id,name,date_entered,date_modified,modified_user_id,created_by,description,deleted,phone_fax)
Fri Aug  1 00:48:03 2014 [2142][1][INFO] End: XmlImporterContactsJobs
```
**Listing 4 - Job in action**

## Disclaimer
The purpose of the project is absolutely focused to teaching. Usefull to show how to implement the use case that we discussed at the beginning.
  
 




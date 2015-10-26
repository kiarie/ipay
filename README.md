# ipay-plugin
IPAY ENROLMENT PLUGIN
------------------------------------------------------------------
Ipay is a payment gateway that allows for clients to pay for goods and services online and using mobile devices.
It supports Mpesa, Airtel Money, Credit Cards and Debit Cards.
This is an enrolment plugin for ipay on moodle an open source project.

Install
-------------------------------------------------------------------
Its as easy as unzipping the package and copying it or upload it to your server in the moodle/enrol folder.
Moodle will then detect the plugin and will require for you to upgrade the moodle database. Click uprgade moodle
database and your plugin is installed.

Enrolment Instances
-------------------------------------------------------------------
To enable the plugin go to your Moodle admin dashboard, Site Administration/plugins and activate your Ipay plugin.
Default is deactivated.
To enable a course to use Ipay as its enrolment instance. Go to Courses look for course administration/users/enrolment methods
and activate it for the desired course put the required settings apply and your good to go!
NOTE: You have to go to a specific course then look for the course administration. Also ensure the self enrolment plugin is disabled
for this course as it will allow for self enrolment without payment.

Settings
-------------------------------------------------------------------

When you install the Ipay Plugin there are some configuration settings required. Set as desired but ensure the Vendor ID is
the one you have been ASSIGNED by IPAY after registration for it will be used in processing the transactions.
There is a hashkey also that you have to generate or let ipay generate for you but IT MUST be shared with Ipay for validation of 
Transactions. NOTE: Its a secret key between your webapp and Ipay therefore not to be shared with any other party.

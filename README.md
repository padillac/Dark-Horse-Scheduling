# Dark Horse Scheduling

*PHP-based web portal for equine facility schedule management and data tracking.*

**IMPORTANT FUNCTIONALITY NOTES**

* Do not have multiple staff or volunteers with the same role in a single class, only one person can currently fill a given role. Make roles more specific!


## Update Log:

### Version 1.6
  *Visual Rework*

* New look to all forms

### Version 1.5
  *Even more robust editing!*

* Both the staff manage class page and the editable daily schedule now have the ability to edit more aspects of a class, such as date and time, as well as adding additional staff and volunteers

* Class types can now be custom-color coded in the schedule

### Version 1.4
  *More robust schedule editing*

* Staff now have more streamlined interfacing between their daily schedule and the manage classes page

* Admin now have access to an editable daily schedule, so any specific class can be edited at a glance

### Version 1.3
  *Automatic emails*

* Volunteers and Staff who record their hours can optionally send email updates to coordinators

* Volunteers and Staff now have a "Submit another shift" button to streamline the process of entering hours

* Staff/Volunteer emails in the directory now appear as clickable links

* Administrators can send mass emails to all staff, volunteers, and clients

* Administrators can permanently delete workers, clients, and horses from the database

### Version 1.2
  *More features and bug fixes*

* Horse care shifts now can have horses associated with them, they will show up in the conflict checker, but will not count as a 'use'

* Arena is now displayed in schedules

* Creating and editing classes now grants the ability to override conflicts if necessary

### Version 1.1
  *Bug fixes and improvements*

* Clicking on version pulls up README file and update log

* Volunteer and Horse directories are now password protected by staff credentials

* Hour entries now support decimal numbers

* Fixed permissions issue that caused reports to not work

* Previously archived 'other objects' can now be recreated through 'create object' page

* Directories now display results alphabetically, and the styles have been updated to better display large amounts of data

* Edit-class function is more stable, won't lose class data when conflicts are not properly resolved. (class data remains unchanged instead)

* New-class function is more stable, if class creation fails for any reason, you can go back to the class creation page and the data you entered will still be there

* Classes now have a display title to make it easier for users to identify distinct classes

* Clients are now presented first on the create/edit class pages

* Volunteers now have flexible class roles, like staff

* Horses now have horse_uses_per_week field, and this data is taken into account by the conflict checker

* Tack Notes and Stirrup Leather Length Notes have been changed to an indefinite number of tack notes and client equipment notes per class, fully customizeable

* Volunteers and Staff can now view their hours for customizeable time periods


### Version 1.0
  *Complete Functionality*

* Complex Class Data Structure
  * Fully customizeable, infinitely extendible
    * *Class type*
    * *Arena*
    * *Horses*
    * *Tacks*
    * *Pads*
    * *Clients*
    * *Leaders*
    * *Sidewalkers*
    * *Staff Roles*

* Conflict-Free Scheduling
  * Automatic conflict detection for all people, horses, spaces, and objects
  * Automatic horse overuse detection
  * Personalized daily schedules instantly generated
    * *Staff*
    * *Volunteers*
    * *Clients*
    * *Horses*

* Volunteer Hour Tracking
  * Ease of data entry

* Staff Hour Tracking
  * Ease of data entry

* Front-Page Availability Checker
  * Instantly see if any person, horse, space, or object is free at any time.

* Directories
  * View public information for all staff, volunteers, and horses

* Reports
  * Complete access to your data
  * Generate comprehensive reports of all your organization's data

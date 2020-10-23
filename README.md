# Project Management Tool (Laravel 6 / Vue / Vuex)

We will build a project management tool. The goal of the project is to create a project schedule based on the tasks that needs to be done.

It should have the following **3 Vue Components :**
- **Task Components :** In this you enter *Task-Name* and *Time* required in hrs. You should be able to *add, edit, delete* tasks easily.
- **Setting Component :** User can enters Total Hours in the day *(example 8 hrs)* / Project Start Date / Workday Starts at Time.
- **Project Schedule Component :** This will be a view only component. Based on the Project Start Date and No. of Hours/day, it will generate a Table that shows Task Schedule Details. On *adding/editing/deleting tasks* in the Task Component, schedule component will update the schedule automatically.

> Eg: **Task 1** - 12 hrs, **Task 2** - 5 hrs, **Task 3** - 9 hrs,
> **Total Working Hours/day** - 08 hrs.
> **Project Start Date** = 30th Oct
>  **Work Start Time/Day** - 09 AM

#### Calculated Schedule should show as follows (Task Name | Task Start Date/Time | Task End Date/Time) :
1. Task 1 | 30th Oct 09 AM | 2nd Nov 01 PM. *(**Note :** That Sat/Sun is holiday hence should be skipped).*
2. Task 2 | 2nd Nov 01 PM | 3rd Nov 10 AM.
3. Task 3 | 3rd Nov 10 AM | 4th Nov 11 PM.

We will only save the tasks and settings in the DB. Schedule should be calculated dynamically each time the page loads.

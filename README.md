# crud-app

**Task**
We need an application for a teacher to assign students to groups for a project.


**Functional requirements**
1. On first visit a teacher should create a new project by providing a title of the project and
a number of groups that will participate in the project and a maximum number of
students per group. Groups should be automatically initialized when a project is created.
2. If the project exists, a teacher can add students using the “Add new student” button.
Each student must have a unique full name.
3. All students are visible on a list.
4. Teacher can delete a student. In such a case, the student should be removed from the
group and project.
5. Teacher can assign a student to any of the groups. Any student can only be assigned to
a single group. In case the group is full, information text should be visible to a teacher.
6. The page is operational and publicly accessible.

**Bonus requirements**
1. Make information on the status page to automatically update every 10 seconds.
2. Implement functional requirement #2 using RESTful API.
3. Add automated test for functional requirement #4.


**Implementation**

Assuming, that teacher can add more than one project, the student list on the project page should not contain all students (this and other projects), but just those who belong to the project. 

If we have Project A and Project B, and the StudentA is assigned to a Group#1 on Project A, we should not see that StudentA on Project B page. That would cause a confusion if Project B have also Group#1 and no students were assigned, but StudentA would have Group#1 next to it, and no indication it is from another project. 

As there was no mention of separate student management with adding a student that does not belong to any project, and the only option to add students is on the project page, this is where I decided, that no student can exist without a project, and used OneToMany relation between Project and Student.

Having all that in mind, the project page lists only students that are added to the project, also they have a unique name per project, meaning students with the same name (or same student) can be added to another project. Also, it was not clear if a Student can be assigned to one group on overall projects, or a Student can be added to one group per project. I chose the second option.

Entity relations:

Project - OneToMany - Student

Project - OneToMany - Group

Group - OneToMany - Students


DB schema
![Untitled](https://user-images.githubusercontent.com/8254886/164407908-42006385-b732-4c9a-97ca-983b65b23c2d.png)


**Alternative implementation**

The alternative solution was to add ManyToMany relation between Project and Student, that way the #2 functional requirement would be met, and the Student would be truly unique. 

But #3 requirement still wouldn’t be correct for the project page (referring to the mockup page). Unless there would be another page for student management, where you could see all students, add them, assign them to one or multiple projects, remove student from projects, but that was not mentioned in the requirements.

**API**

"Add new student" form on project page was implemented using API.

API also can be tested using Postman, sending POST method to url http://your-domain.loc/`api/student/{projectId}`
Body should contain raw JSON:

```
{
    "name": "Peter",
    "surname": "Parker"
}
```

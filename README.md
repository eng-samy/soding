# Soding Assessment 

# In this simple project, it is not just code to create,update,delete and display tasks. it also  implement a legacy tasking system that features dependencies. 

# Each task have an ID, title, status, and parent task ID. A task’s status is IN PROGRESS until it is marked as DONE.

# While any task can be marked as DONE, a “parent” task (one with dependencies) must be marked as COMPLETE automatically when all of its dependencies(and sub-dependencies) are likewise marked as COMPLETE.

# A task is only considered COMPLETE when it’s marked as DONE and either has no dependencies or all of its dependencies are also COMPLETE.

# Any individual task may have any number of dependencies but should never result in a circular dependency. For example, if Task A

depends on Task B, then Task B cannot also depend on Task A.

# Extra Features

# 1- Full Ajax Functions
# 2- One Page App
# 3- Pagination and Filtration by ajax
# 4- Good UI/UX
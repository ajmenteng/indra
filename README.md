# Overview
INDRA stands for Intelligent Network Daily Report Analyzer. It was a project of an automated system check of Intelligent Network (IN) 
system. It is equipped with security and approval system therefore it complied with SOA requirement.
The IN system was hosted on a cluster of servers running UNIX on it. By nature, each server produced a health report twice a day in a raw text format.
What the backend of INDRA did was parsing this health report file using PERL, converted it into XML, and stored in a MySQL database.
Once the data get into the database, the system can manage the data in a more flexible but sophisticated way. 
We added more advanced features that significantly increace efficiency.

# Design
The design of the checklist form reflected the previous layout design of the paperwork. 
We therefore were able to minimize the learning curve as the interface is somewhat our users were already familiar with.

# Features
1. OK and Not OK items will be automatically marked accordingly.
2. There is a snippet from the raw data on every item so a user can double check whether the system has appropriatetly parse and store the data.
3. Approval system by a user's supervisor.
4. Rejection feature along with the additional notes from a supervisor.
5. Various statistic reports were available.

# Installation
1. Setup the LAMP or XAMPP package.
2. Put the files on the public directory.
3. Dump the database file (Sorry we cannot provide this publicly).

# Collaborators
1. Heru Wardhana: Handling the data parsing and preparation.
2. John Wesly: Handling the backend and frontend development.


# IT490 Final Project DMZ Server ReadMe
***
This ReadMe is to enable users to easily recreate and use the servers for the DMZ back-end of this project.
This guide will cover 3 main areas:
1. Installing the files for this server from github
2. Getting the system ready to use.
3. Handling deployment to other Servers
***
## Installing from Github:
Installing this system from github is quite simple. Navigate to the directory that you wish the files to be installed in, and input this command at the terminal:

`git clone https://github.com/amuthiyan/IT490.git`

This should download the files from git to your computer into a directory called 'git'. Navigate into that directory.
Now that you have installed the files from github, you still need to set up the system. That will be covered in the next system.
***
## Setting up the System:
Leaving off from the previous system, you should be within the folder 'git' that you downloaded. Within that directory, you should see two sub-directories. Navigate into the one called 'IT490'. Ignore the other for now.

Within 'IT490', there are a few sub-directories as well. There are also two files called 'ApiServer.service' and 'ApiServerStandby.service'. These will have to be moved into the 'etc/systemd/system' folder. Use the following commands at the terminal to do so:

1. `sudo mv ApiServer.service /etc/systemd/system`
2. `sudo mv ApiServerStandby.service /etc/systemd/system`

These files allow the listeners that do the work of fetching the card data to start automatically. One is the primary, and connected to the primary communication server, and the second is the backup, connected to the backup communicatin server.

To allow the files to run automatically at startup, run the following commands:

1. `sudo systemctl daemon-reload`
2. `sudo systemctl enable ApiServer.service`
3. `sudo systemctl enable ApiServerStandby.service`

Now, to start the listeners, you can either restart the machine, or run the following commands:

1. `sudo systemctl start ApiServer.service`
2. `sudo systemctl start ApiServerStandby.service`

It is important to note that these listeners should only be started after the communication servers are online. Otherwise, they will enter a failed state until the communication servers are started. To check on their state, use the following command:

`sudo systemctl status <listener name>`

This will tell you if the listener is active or not. If not, restart it with this command:

`sudo systemctl restart <listener name>`
***
## Deploying to other servers:
Before deploying, ensure that the deploy server is running on another machine. Also ensure that the machine you are deploying to is defined in your conf.ini file that is located within the Deploy sub-directory. Simply enter your destination machine name into it along with its IP address.

To deploy the package to other servers, you will need to run the following commands from inside the IT490 directory:

1. `cd Deploy/`
2. `sudo php deployClient.php <Destination machine name> <Destination file path>`

And you are done!

# Command line Linux basic![icon_phone.png](./assets/icon_phone.png)

### pwd

Stands for print working directory
Print name of current/working directory

```bash
$ pwd
```

### cd

Stand for:  change directory
Change the current working directory
**Example:**
`cd abc` : go to folder abc from current working directory
`cd /etc` : go to folder etc from root folder
`cd ~`: go to home directory
`cd ..`: up one directory level

### mkdir

Stand for:  make directory
**Example:**
`mkdir manh`: create folder manh
`mkdir manh1 manh2 manh3`: create folders manh1, manh2, manh3
`mkdir -p dir4/dir5/dir6`: create folder dir6

### ls

Stand for:  list
List directory contents
**Example:**
`ls`: show file and folder visible
`ls -l`: show file and folder visible by line
`ls -la`: show all file and folder, including hidden
`ls > output.txt`: save to file output.txt

###echo
Display text
**Example:**
`echo "This is a test"`
`echo "This is a test" > test_1.txt`: write text to file by override content
`echo "I've appended a line!" >> combined.txt`: write file by append content

### cat

Stand for:  concatenate
Concatenate files and print on the standard output
**Example:**
`cat output.txt` : read file output.txt
`cat test_1.txt test_2.txt test_3.txt`: read multiple file

### mv

Stand for:  move
Move (rename) files
**Example:**
`mv combined.txt dir1` : move file combined.txt to folder dir1
`mv dir1/* .`: move all fille/folder in folder dir1 to current directory
`mv backup_combined.txt combined_backup.txt`: rename file backup_combined.txt to combined_backup.txt
`mv combined1.txt combined2.txt dir`: move file combined1.txt and combined2.txt to folder dir

### cp

Stand for:  copy
Copy files and directories
**Example:**
`cp dir4/dir5/dir6/combined.txt .` : copy path dir4/dir5/dir6/combined.txt to current directory
`cp combined.txt backup_combined.txt`: copy file combined.txt to backup_combined.txt

### rm

Stand for:  remove
Remove files or directories
**Example:**
`rm dir4/dir5/dir6/combined.txt combined_backup.txt` : remove file dir4/dir5/dir6/combined.txt and
combined_backup.txt
`rm t*`:  delete file with start filename is character t
`rm t *`: delete file t and all file !
`rm -r dir`: delete anything in folder dir

### wc

Stand for:  word count
Print newline, word, and byte counts for each file
**Example:**
`wc combined.txt` : newline, word, and byte counts if file combined.txt
`wc -l combined.txt`:  show line of file combined.txt
`ls ~ | wc -l`: show line of list current directory, use pipeline character
`cat file.txt | uniq |  wc -l`: read file file.txt, remove line repeated, then count line, usse pipeline charracter

### man

Stand for:  manual
An interface to the system reference manuals
**Example:**
`man cp`
`man mv`

### sort

Sort lines of text files
**Example:**
`sort file1.txt`

###sudo
Stand for:  switch user and do this command
Execute a command as another user

###curl
Stand for:  Client URL
Transfer a URL
**Example:**
`curl https://google.vn`: get source page, save to file index.html
`curl -O https://mysite.com/file.txt`: save file file.txt get from url
`curl -o test.txt https://mysite.com/file.txt`: save file test.txt get from url
`curl -x  sampleproxy.com:8090 -U username:password -O http:// testdomain.com/testfile.tar.gz`: curl for http to get file
`curl –data “text=Hello” https://myDomain.com/firstPage.jsp`: curl for http to send data
`curl --cookie-jar Mycookies.txt https://www.samplewebsite.com /index.html -O`: curl for cookie to get file
`curl --cookie Mycookies.txt https://www. samplewebsite.com`: curl for cookie to upload file
`curl -u username:password -O ftp://sampleftpserver/testfile.tar.gz`: curl for ftp to get file
`curl -u username:password -T testfile.tar.gz ftp://sampleftpserver`: curl for ftp to upload file

###wget
Stand for "GNU Get"
The non-interactive network downloader
**Example:**
`wget http://slynux.org`: get source page, save to file index.html
`wget -O https://mysite.com/file.txt`: save file file.txt get from url

###clear
Clear the terminal screen

###find
Search for files in a directory hierarchy
**Example:**
`find abc.txt`: find file abc.txt in current directory
`find d*`: find find begin with character d

###whoami
Print effective userid

###pwd
Stand for: print working directory
Print name of current/working directory

###date
Print or set the system date and time

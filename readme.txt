Remtcha is a touch based graphical variant of CAPTCHA, providing variations of colors and images with opaque variations.

It has the following files addressing unique purpose.
actcode.py : generates the image for displaypad
bp_php.php : parses the arguments from index.php for Block pad and invokes actcode.py
dp_php.php : parses the argument from index.php for Display pad and invokes bpactcode.py
img        : contains sample opaque images to display pad and block pad
index.php  : source of index.php file for Remtcha
remtcha.php: Generates hash combinations from set of unicode characters
verifykeys.php : verify the characters tapped on the blockpad to that in the display pad
bpactcode.py : Generated images for block pad
createmap.php: Generates html image map and places it on index.php
genImgMap.php: No significant purpose
index.css : css for index.php
json : json file containing the mapping of colors and unicode symbols
session_handler.php : sample file for handling php session
verifykeys_post_handler.php : Sample file to handle captcha results in the server


Software requirements:
Python Pillow
Python3
PHP
Linux : Tested on Ubuntu 16.04
Apache Server


Please follow the instructions to get Remtcha working:

1. In the Remtcha root folder, download google noto fonts and place the ttf files in the following directory
remtcha_root/noto/Noto_hint/

2. Install Python3 

3. Install Python Pillow.

4. change the path for python and the required file in dp_php.php and bp_php.php

Test Information:
Remtcha is tested is PHP version 5.4

After installing the required fonts and Python tool, run index.php, you could see the entire web site to run Remtcha


Credit for this creation:

Communities: Mozilla Developer Network, Linux, Google Noto Fonts Team, StackOverflow, CPanel, GIT Hub, PHP, Python, W3schools
and many more..



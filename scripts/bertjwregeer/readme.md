#Collection of Gource Helper Scripts

This collection of helper scripts are used to generate .mp4 files containing the output from gource, a visualisation tool that generates pretty output from the git log output.

# Requirements

*nux based OS
gource
ffmpeg with x264 support
tmux (not required)

# Setup

Move the four scripts into your local bin directory:

mkdir ~/bin/
move scripts to ~/bin/
chmod +x ~/bin/gource-*.sh
First create a new directory where you are going to store your various repositories:

mkdir ~/repos
Then clone all of the repo's you want to run gource on into that directory:

cd ~/repos
git clone git://...
After this is completed, modify run-all.sh to point to the correct directories or run gource-run.sh directly.

~/bin/gource-run.sh ~/repos ~/gource-output
Now the script will first combine the log output from each of the repos and write a combined.mp4. This basically shows everything that has been done in one huge chart for all of the different repositories, if you would prefer not to do that feel free to remove those lines from gource-run.sh.

After it finishes chugging through the generation of the videos it will let you know. At which point you can upload them to your favourite video sharing website.

# run-all.sh

run-all.sh is a small script that uses Xvfb (X virtual frame buffer) and tmux to run the scripts on a head-less server. Please be aware that the X server runs without auth required, so anyone could connect to it and run X11 applications on it. This will be slower than having a graphics card that does OpenGL, as would be expected.

run-all.sh takes care of either creating a new tmux session, or if one already exists it will simply attach the new windows to it. It is safe to run run-all.sh multiple times, it won't cause the script to be killed or otherwise canceled. For exmaple, I am currently running it from cron late at night when nobody is at the office.
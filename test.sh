vnc_port=11901

Xvfb :1 -screen :1 1920x1080x16 -ac +extension GLX +extension RANDR +render &
sleep 3
fluxbox -display :1&
sleep 3
x11vnc -display :1 -rfbport $vnc_port -forever &

echo 'VNC port is ' $vnc_port

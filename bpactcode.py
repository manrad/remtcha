#    Copyright (C) 2017  Manivannan Radhakannan
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.

#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.

#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.

#    bpactcode.py creates Remtcha Block Pad Image using Pillow Python and Google Noto Fonts

import io
import sys
from PIL.Image import core as _imaging
from PIL import Image, ImageDraw, ImageFont
# This file returns transparent image for Display box
# parameter sequence :
# param 1 : boxtype : dp/bp : display pad or blockpad
# param 2 : ttf font type- name
# param 3 : display pad - Font color and Opacity : RGBA
# param 4 : display pad - Background color and Opacity : RGBA
# param 5 : display pad - Background Image : png format
# param 6 : display pad - Foreground color and Opacity : RGBA
# param 7 : display pad - Foreground Image : png format
# param 8 : block pad - background color : RGBA
# param 9 : block pad - background image : png format  

# dp NotoSans-Bold.ttf 22_55_11_6 77_33_11_4 variator0.png 33_44_11_6 variator_overlay0.png 33_44_11_2 remb0.png 33_44_11_2 
boxtype = sys.argv[1]
fontname = sys.argv[2]

dpfco = sys.argv[3]
dpbgco = sys.argv[4]
dpimg = "img/dp/background/" + sys.argv[5]
dpfgco = sys.argv[6]
dpfgimg = "img/dp/foreground/" + sys.argv[7]
bpbco = sys.argv[8]
bpbgimg = "img/bp/" + sys.argv[9]
bpfco = sys.argv[10]
uccharcount = len(sys.argv) - 11

#print "boxtype "+boxtype +" "+ "fontname "+fontname +" "+ "dpfco "+dpfco +" "+ "dpbgco "+dpbgco +" "+ "dpimg "+ dpimg +" "+ "bpfgco "+ dpfgco +" "+ "dpfgimg "+dpfgimg +" "+ "bpbco "+bpbco +" "+ "bpbgimg "+bpbgimg +"bpfco "+bpfco


sze = (300,300)
# background color and opacity
tc = str.split(bpbco,"_")
opacity =  int(255/10) * int(tc[3])
color = [0,0,0,0]
color[0] = int(tc[0])
color[1] = int(tc[1])
color[2] = int(tc[2])
color[3] = opacity
#configure base image for block pad
base = Image.new('RGBA', sze, tuple(color))

#load the image selected by user for displaybox-->background image
# !!both base and baseimage should be of same size
baseimage = Image.open(bpbgimg).convert('RGBA')

#blend base and baseimage

blendedImage = Image.blend(baseimage,base,opacity)
del baseimage
del base

#create transparent image base to draw text
dptxt = Image.new('RGBA',sze,(255,255,255,0))

# get the font type supporting the language selected
dpfnt = ImageFont.truetype('noto/Noto_hint/'+fontname, 40)

#get a drawingcontext
drawcontext = ImageDraw.Draw(dptxt)

#font color
tc = str.split(bpfco,"_")
#print bpfco + tc[0] + tc[1] + tc[2] + tc[3]
opacity =  int(255/10) * int(tc[3])
color = [0,0,0,0]
color[0] = int(tc[0])
color[1] = int(tc[1])
color[2] = int(tc[2])
color[3] = opacity
iy = 11
width = 0
height =  0
for ix in sys.argv[iy:]:
    drawcontext.multiline_text((width,height), chr(int(ix,16)), font=dpfnt, fill=tuple(color),spacing = 10,align="center")
    width = width + 70
    if(width > 240):
        width = 0;
        height = height + 100

composedimg = Image.alpha_composite(blendedImage, dptxt)
del dpfnt
del blendedImage
del dptxt
del drawcontext

image_buffer = io.BytesIO()
composedimg.save(image_buffer,"PNG")
del composedimg
sys.stdout.buffer.write(image_buffer.getbuffer())
del image_buffer

#composedimg.save(output,"PNG")
#tempimg.save(sys.stdout,"PNG")

#composedimg.save(sys.stdout,"PNG")
#composedimg.show()

    
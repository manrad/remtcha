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

#    actcode.py creates image using Pillow Python and Google Noto Fonts


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

# dp NotoSans-Bold.ttf 22_55_11_6 77_33_11_4 variator0.png 33_44_11_6 variator_overlay0.png 33_44_11_2 remb0.png 44_33_11_2
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


sze = (300,60)
color = (253,45,34)
opacity = 4

#print "boxtype "+boxtype +" "+ "fontname "+fontname +" "+ "dpfco "+dpfco +" "+ "dpbgco "+dpbgco +" "+ "dpimg "+ dpimg +" "+ "bpfgco "+ dpfgco +" "+ "dpfgimg "+dpfgimg +" "+ "bpbco "+bpbco +" "+ "bpbgimg "+bpbgimg


#if dp, set size of image to be 300x60 , else 300x480
#print "boxtype is dp"
sze = (300,60)

# background color and opacity
tc = str.split(dpbgco,"_")
opacity =  int(255/10) * int(tc[3])
color = [0,0,0,0]
color[0] = int(tc[0])
color[1] = int(tc[1])
color[2] = int(tc[2])
color[3] = opacity
#configure base image for display box
base = Image.new('RGBA', sze, tuple(color))

#load the image selected by user for displaybox-->background image
# !!both base and baseimage should be of same size
baseimage = Image.open(dpimg).convert('RGBA')

#blend base and baseimage

blendedImage = Image.blend(baseimage,base,opacity)
del baseimage
del base
#create transparent image base to draw text
dptxt = Image.new('RGBA',sze,(255,255,255,0))

# get the font type supporting the language selected
dpfnt = ImageFont.truetype('noto/Noto_hint/'+fontname, 30)

#get a drawingcontext
drawcontext = ImageDraw.Draw(dptxt)

#font color
tc = str.split(dpfco,"_")
opacity =  int(255/10) * int(tc[3])
color = [0,0,0,0]
color[0] = int(tc[0])
color[1] = int(tc[1])
color[2] = int(tc[2])
color[3] = opacity

iy = 11
width = 5
for ix in sys.argv[iy:]:
    drawcontext.text((width,5), chr(int(ix,16)), font=dpfnt, fill=tuple(color))
    width = width + 50

composedimg = Image.alpha_composite(blendedImage, dptxt)
del blendedImage
del dptxt
tc = str.split(dpfgco,"_")
opacity =  int(255/10) * int(tc[3])
color = [0,0,0,0]
color[0] = int(tc[0])
color[1] = int(tc[1])
color[2] = int(tc[2])
color[3] = opacity
#configure base image for display box
foregroundbase = Image.new('RGBA', sze, tuple(color))

#place the foreground  image above the text
fgimage = Image.open(dpfgimg).convert('RGBA')

fgblendedImage = Image.blend(fgimage,foregroundbase,opacity)

tempimg = Image.alpha_composite(fgblendedImage, composedimg)
del fgimage
del foregroundbase


image_buffer = io.BytesIO()
tempimg.save(image_buffer,"PNG")
del tempimg
sys.stdout.buffer.write(image_buffer.getbuffer())
del image_buffer
#tempimg.save("dp.png","PNG")
#print (tempimg.tobytes())
#sys.stdout.buffer.write(tempimg)
#del tempimg

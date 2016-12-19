#!/usr/bin/env python
#based on http://stackoverflow.com/questions/4458696/finding-out-what-characters-a-font-supports/19438403#19438403
#Generate an html page with all supported fonts

from itertools import chain
import sys

from fontTools.ttLib import TTFont
from fontTools.unicode import Unicode

html = """
<html>
<head>
<style>
@font-face {{
    font-family: custom-glyph;
    src:url('{font_location}');
}}

div {{
    display: inline-block;
    border: 1px solid black;
    width: 120px;
    height: 20px;
}}

.icon {{
    font-family: custom-glyph;
}}

</style>
</head>
<body>
"""

font_file=sys.argv[1]

ttf = TTFont(font_file, 0, verbose=0, allowVID=0,
                ignoreDecompileErrors=True,
                fontNumber=-1)

chars = list(chain.from_iterable([y + (Unicode[y[0]],) for y in x.cmap.items()] for x in ttf["cmap"].tables))
#chars.sort()

#print(list(chars))
#for i in chars:
    #print(i)

# Create an html page with all glyphs
out_file = open('font-glyphs.html','w')
out_file.write(html.format(font_location=font_file))
for i in chars:
    out_file.write('<div>')
    out_file.write('&amp;#x{:X} : '.format(i[0]))
    out_file.write('<span class="icon">&#x{:X};</span>'.format(i[0]))
    out_file.write('</div>\n')
out_file.write('</body></html>')
out_file.close

# Use this for just checking if the font contains the codepoint given as
# second argument:
if(len(sys.argv) > 2):
    char = int(sys.argv[2], 0)
    print(Unicode[char])
    print(char in (x[0] for x in chars))

ttf.close()



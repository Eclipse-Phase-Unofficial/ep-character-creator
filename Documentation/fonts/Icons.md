#Fonts As Icon Storage

This, and many other websites, uses [IcoMoon] icons
IcoMoon packs these icons into font files.
These are not standard unicode icons, so you can't simply use the unicode code points.
Worse, this site uses a special font pack containing only the needed icons.
When IcoMoon created the pack it didn't bother to make sure the icons were in a unicode 'Private Use Area'.
The icons used in this site overlap with normal ASCII characters.

##Using an icon
First, inlcude `icomoon.css`.
Then add  `<span data-icon='&#x(icon_number)'></span>`.  This will be converted to the wanted icon.

Since the icons are treated as a font, you can do fun things to them, like setting `opacity` or changing their size using the `font-size` setting.

##Getting the icon's id
A python program has been included which will generate a web page with all the important font id infromation.
Simply copy the `&#x(font_number)` from the generated page.
A pre-generated page of all icomoon fonts can be found in [icomoon.html]

To generate a new page, first make sure 'python' and 'fontTools' are installed.
If 'fontTools' is not installed but python is, you can run `pip install fontTools`.
Next run `./get_font_information.py '../../src/Creator/version4/fonts/icomoon.ttf'` from the command line.
This will generate a 'font-glyphs.html' file containing the appropriate information.

[icomoon]:      https://icomoon.io
[icomoon.html]: icomoon.html

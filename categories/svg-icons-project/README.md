### Example Using Inkscape:

1. **Open Inkscape**: Load your SVG file.
2. **Select an Icon**: Use the selection tool to click on the icon you want to isolate.
3. **Copy and Paste**: Copy the selected icon (Ctrl+C) and create a new document (Ctrl+N). Paste the icon (Ctrl+V) into the new document.
4. **Adjust the Canvas**: Resize the canvas to fit the icon if necessary.
5. **Save the File**: Go to `File > Save As`, and choose SVG format. Name the file and save it.
6. **Repeat for Other Icons**: Go back to the original document and repeat the process for each icon.

### Using Command Line Tools:

If you have a lot of icons and prefer an automated approach, you can use command-line tools like `svg-split` or write a script in Python using libraries like `svgpathtools` or `lxml` to parse the SVG and split it programmatically.

### Example Python Script:

Here's a simple example of how you might use Python to split an SVG file:

```python
from lxml import etree

def split_svg(svg_file):
    tree = etree.parse(svg_file)
    root = tree.getroot()
    
    icons = root.findall('.//{http://www.w3.org/2000/svg}g')  # Adjust the tag as necessary

    for i, icon in enumerate(icons):
        new_svg = etree.Element('{http://www.w3.org/2000/svg}svg', width="100", height="100")  # Set appropriate width and height
        new_svg.append(icon)
        
        with open(f'icon_{i}.svg', 'wb') as f:
            f.write(etree.tostring(new_svg))

split_svg('your_icons.svg')
```

### Note:
- Make sure to adjust the namespaces and paths according to your SVG structure.
- Always back up your original SVG file before making changes.

If you need further assistance or specific code examples, feel free to ask!
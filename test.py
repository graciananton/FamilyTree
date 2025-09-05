#!/usr/bin/python3
import sys
import cv2
import pkgutil

# Iterate over all modules
for module in pkgutil.iter_modules():
    print(module.name+"<br/>")
    
image = cv2.imread('image.png')
print("basil anton")

if len(sys.argv) > 1:
    name = sys.argv[1]
    print(f"<h1>Hello, {name}!</h1>")

print("</body></html>")
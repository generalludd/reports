#!/bin/bash

find . -type d -exec chmod 755 {} \; -print;find . -type f -exec chmod 644 {} \; -print

#!/bin/sh

cat \
    Resources/public/css/plugins.css \
    Resources/public/css/style.css \
    Resources/public/css/responsive.css \
    > Resources/public/css/min.css

yui-compressor Resources/public/css/min.css > Resources/public/css/2xmin.css

cat \
    Resources/public/js/lozad.min.js \
    Resources/public/js/jquery.js \
    Resources/public/js/plugins.js \
    Resources/public/js/functions.js \
    Resources/public/js/frontend.js \
    > Resources/public/js/min.js


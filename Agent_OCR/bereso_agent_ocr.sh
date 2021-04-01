#!/bin/bash

# ###################################
# Bereso
# BEst REcipe SOftware
# ###################################
# BeReSo OCR Agent
# Version 1.0
# ###################################


# Config
BERESO_URL="http://bereso/" # URL to the BeReSo installation
BERESO_PASSWORD="PASSWORD_FOR_OCR_AGENT" # Password for the OCR agent
export TESSDATA_PREFIX=/usr/share/tesseract-ocr/4.00/tessdata/ # Tesseract tessdata folder
LANGUAGE=deu # Set tesseract processing language


############################################
# NO CONFIG CHANGE NEEDED BELOW THIS LINE
############################################

# Log start date and time
echo $'' >> bereso_agent_ocr.log
start=$(date '+%d/%m/%Y %H:%M:%S');
echo "$start starting..." >> bereso_agent_ocr.log

# Get list with URLs and item ids
OCRLIST=$(curl "$BERESO_URL?module=agent_ocr&action=list&ocr_password=$BERESO_PASSWORD")


# seperate OCRLIST into an array per line
readarray -t OCRITEM <<<"$OCRLIST"


# loop through all items
for i in ${OCRITEM[@]}; do
        # split the item via , into item_id and image_url
        IFS=',' # split by comma
        read -a OCRITEMSPLIT <<< "$i"
        OCRITEMID="${OCRITEMSPLIT[0]}"
        OCRITEMURL="${OCRITEMSPLIT[1]}"

        # build save ocr url
        OCRSAVEURL="$BERESO_URL?module=agent_ocr&action=save&ocr_password=$BERESO_PASSWORD&item=$OCRITEMID"

        # load the image
        curl -o image $OCRITEMURL

        # optimize and convert to tif (input is png or jpg)
        convert image -colorspace Gray -units pixelsperinch -density 300 -depth 8 image.tif

        # start OCR using tesseract
        tesseract -l $LANGUAGE image.tif ocr # start ocr with language $LANGUAGE and save the output in ocr.txt

        # save the ocr text
        OCRTEXT=`cat ocr.txt` # read file ocr.txt
        echo ""
        curl -d "ocr_text=$OCRTEXT" -X POST $OCRSAVEURL
        echo ""


        # delete the temp files
        rm image
        rm image.tif
        rm ocr.txt
done

# Log end date and time
end=$(date '+%d/%m/%Y %H:%M:%S');
echo "$end finished" >> bereso_agent_ocr.log

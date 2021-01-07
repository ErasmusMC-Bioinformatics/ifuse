#!/bin/bash

# Download reference data for fusion gene sequence predicions

cd /var/www/html/R/
mkdir hg18 hg19


for i in {hg18,hg19}
do

cd $i
# download files
wget -q http://hgdownload.cse.ucsc.edu/goldenPath/$i/chromosomes/chr{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,X,Y,M}.fa.gz

# unzip files
gunzip chr*.fa.gz

# reformat data
find chr*.fa -exec sh -c "tmp={} && sed -i '1d' {} && tr -d '\n' < {} > \${tmp%.fa} && rm {};" \;

ls -l
cd ../

done

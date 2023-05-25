# Index Animalium

Data from uBio and Smithsonian on Sherborn Index Animalium

For uBio project see http://www.ubio.org/Sherborne/index.html, data dumps come from http://www.sil.si.edu/DigitalCollections/indexanimalium/Datasets/

For background see: 

Richard, J. M., Pilsk, S. C., & Kalfatovic, M. R. (2016, January 7). Unlocking Index Animalium: From paper slips to bytes and bits. ZooKeys. Pensoft Publishers. [https://doi.org/10.3897/zookeys.550.9673](https://doi.org/10.3897/zookeys.550.9673)

## ION

The folder `ion` contains results of web scraping the ION web site to retrieve to Index Animalium links on ION’s pages.

## uBio

The `bio` folder contains content from the Wayback Machine detailing uBio’s parsing efforts.

## Smithsonian

The folder `smithsonian` has the data dumps from the Smithsonian, which are not CSV files despite the name as the citations contain commas that break CSV parsing.

## Code

My code to parse ION HTML pages, and to parse the Smithsonian “CSV” files.

// Process all images
newImage("Left Mirror", "8-bit Black", 720, 480, 1);
for (i = 1; i <= 1; i++){
//makeRectangle(4,152,114,125);
//run("Crop");
setSlice(i);
run("8-bit"); //properly threshold
setAutoThreshold("Otsu dark");
}

newImage("Right Mirror", "8-bit Black", 720, 480, nSlices);
for (i = 1; i <= 1; i++){
//makeRectangle(547, 160, 114, 107);
//run("Crop");
setSlice(i);
run("8-bit"); //properly threshold
setAutoThreshold("Otsu dark");
}
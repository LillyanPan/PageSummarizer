//Start green with decreasing intensity
newImage("Green", "8-bit Black", 512, 512, nSlices);
for (i = 1; i <= nSlices; i++){
selectWindow("BallMotion.avi");
setSlice(i);
run("Copy");
selectWindow("Green");
setSlice(i);
run("Paste");
scale = 102/101 - i/nSlices; //correction so scale = 1 on first slice; 102/101
run("Multiply...", "value="+scale+" slice");
}

//Yellow increasing intensity
newImage("Yellow", "8-bit Black", 512, 512, nSlices);
for (i = 1; i <= nSlices; i++){
selectWindow("BallMotion.avi");
setSlice(i);
run("Copy");
selectWindow("Yellow");
setSlice(i);
run("Paste");
scale = i/nSlices;
run("Multiply...", "value="+scale+" slice");
}

//merge
run("Merge Channels...", "c2=Green c7=Yellow create");
selectWindow("Composite")
run("Z Project...", "projection=[Max Intensity]");

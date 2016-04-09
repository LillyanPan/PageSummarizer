% Returns center points of select label using normalized cross correlation
function ncrosscorr()

	% Read in file
	fname = '601_proc.tif';
	info = imfinfo(fname);
	frameNum = numel(info);
	cpoints = zeros(frameNum,3);

	% Read images and display them side-by-side
	label = rgb2gray(imread('label.png'));
	for f = 1:frameNum
		image = rgb2gray(imread(fname, f)); %change to just mirror?
		imshowpair(label,image,'montage')

		% Perform cross-correlation and display result as surface
		c = normxcorr2(label,image);
		figure, surf(c), shading flat

		% Find peak in cross-correlation
		[ypeak, xpeak] = find(c==max(c(:)));

		% Account for the padding that normxcorr2 adds
		yoffSet = ypeak-size(label,1);
		xoffSet = xpeak-size(label,2);

		% Record centerpoints (padding included)
		cpoints(k,1) = k;
        cpoints(k,2) = x1Center;
        cpoints(k,3) = y1Center; 
	end
end

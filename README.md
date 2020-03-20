## Disclaimer: this library is in Alpha - not tested. Use it at your own risk!

# Giffhanger
#### (play on words: from ["Cliffhanger"](https://en.wikipedia.org/wiki/Cliffhanger))
It generates Video previews in GIF or Video format.
To use this library you need ffmpeg executable installed

## Usage
Minimal example
```
$giffhanger = new Giffhanger('/path/to/video/file',[
    'output_dimension' => 320
]);
//to generate gif
$giffhanger->generate('/path/to/output.gif');

//to generate video
$giffhanger->generate('/path/to/output.avi');
```

## Options
- **resize_width**: resize width (in pixels) of the output file [default: **640**]
- **crop_ratio**: crop the video following the value passed [default crop is not applied]
- **temp_dir**: define the temp directory used to generate the output file [default is **system temp directory**]
- **frames**: number of "pieces" taken to build the preview [default is **3**]
- **duration**: duration (in seconds) of the preview [default is **6**]
- **bitrate**: bitrate of the video (used only in case of mp4 output) [default **600**]
- **frame_rate**: framerate of the output [default **10**]
